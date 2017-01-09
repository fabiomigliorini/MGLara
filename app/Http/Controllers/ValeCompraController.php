<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use MGLara\Http\Controllers\Controller;

use MGLara\Models\ValeCompra;
use MGLara\Models\ValeCompraModelo;
use MGLara\Models\ValeCompraProdutoBarra;
use MGLara\Models\ValeCompraFormaPagamento;

use MGLara\Library\EscPrint\EscPrintValeCompra;

use MGLara\Models\Titulo;


class ValeCompraController extends Controller
{
    public function __construct()
    {
        /*
        $this->middleware('permissao:vale-compra.inclusao', ['only' => ['create', 'store']]);
        $this->middleware('permissao:vale-compra.alteracao', ['only' => ['edit', 'update']]);
        $this->middleware('permissao:vale-compra.exclusao', ['only' => ['delete', 'destroy']]);
         * 
         */
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $parametros = self::filtroEstatico(
            $request, 
            'vale-compra.index', 
            [
                'ativo' => 1,
                'codusuariocriacao' => Auth::user()->codusuario
            ], 
            [
                'criacao_de', 
                'criacao_ate'
            ]);
        $model = ValeCompra::search($parametros)
            ->orderBy('criacao', 'DESC')
            ->orderBy('alteracao', 'DESC')
            ->paginate(20);
        return view('vale-compra.index', compact('model', 'parametros'));
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $model = ValeCompra::findOrFail($id);
		$imprimir = ($request->get('imprimir') == 'true')?true:false;
        
        return view('vale-compra.show', compact('model', 'imprimir'));
    }

    
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (!empty($request->get('codvalecompramodelo'))) {
            $modelo = ValeCompraModelo::findOrFail($request->get('codvalecompramodelo'));
            $model = new ValeCompra($modelo->getAttributes());
            $model->codpessoa = \MGLara\Models\Pessoa::CONSUMIDOR;
            $model->codvalecompramodelo = $request->get('codvalecompramodelo');
            foreach ($modelo->ValeCompraModeloProdutoBarraS as $m_prod) {
                $prods[] = new ValeCompraProdutoBarra($m_prod->getAttributes());
            }
            return view('vale-compra.create', compact('model', 'prods'));
        }
        $modelos = ValeCompraModelo::
            whereNull('tblvalecompramodelo.inativo')
            ->join('tblpessoa', 'tblpessoa.codpessoa', '=', 'tblvalecompramodelo.codpessoafavorecido')
            ->orderBy('tblpessoa.fantasia', 'ASC')
            ->orderBy('modelo', 'ASC')
            ->get();
        return view('vale-compra.create-seleciona-modelo', compact('modelos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        
        $dados = $request->all();
        
        $model = new ValeCompra($dados);
        $model->totalprodutos = array_sum($dados['item_total']);
        $model->total = $model->totalprodutos - $model->desconto;
        
        if (!$model->validate()) {
            $this->throwValidationException($request, $model->_validator);
        }
        
        if ($model->save()) {
            foreach ($dados['item_codprodutobarra'] as $key => $codprodutobarra) {
                if (empty($codprodutobarra)) {
                    continue;
                }
                
                $prod = new ValeCompraProdutoBarra([
                    'codvalecompra' => $model->codvalecompra,
                    'codprodutobarra' => $codprodutobarra,
                    'quantidade' => $dados['item_quantidade'][$key],
                    'preco' => $dados['item_preco'][$key],
                    'total' => $dados['item_total'][$key],
                ]);
                
                $prod->save();
            }
            
            $pag = new ValeCompraFormaPagamento([
                'codvalecompra' => $model->codvalecompra,
                'codformapagamento' => $dados['codformapagamento'],
                'valorpagamento' => $model->total,
            ]);
            
            $pag->save();
            
            // Gera Contas a Receber
            if (!$pag->FormaPagamento->avista) {
                $acumulado = 0;
                for ($i=1; $i<=$pag->FormaPagamento->parcelas; $i++) {
                    if ($i == $pag->FormaPagamento->parcelas) {
                        $valor = $pag->valorpagamento - $acumulado;
                    } else {
                        $valor = floor($pag->valorpagamento / $pag->FormaPagamento->parcelas);
                        if ($valor == 0) {
                            $valor = round($pag->valorpagamento / $pag->FormaPagamento->parcelas, 2);
                        }
                    }
                    $vencimento = ($i==1)?$model->criacao->addDays($pag->FormaPagamento->diasentreparcelas):$vencimento->addDays($pag->FormaPagamento->diasentreparcelas);
                    $acumulado += $valor;
                    $numero = str_pad($model->codvalecompra, 8, '0', STR_PAD_LEFT);
                    $numero = "V{$numero}-{$i}/{$pag->FormaPagamento->parcelas}";
                    
                    $titulo = new Titulo();
                    $titulo->numero = $numero;
                    $titulo->codpessoa = $model->codpessoa;
                    $titulo->codfilial = $model->codfilial;
                    $titulo->codvalecompraformapagamento = $pag->codvalecompraformapagamento; //Venda Vale
                    $titulo->debito = $valor;
                    $titulo->codtipotitulo = 240; //Débito Cliente
                    $titulo->codcontacontabil = 82; //Venda Vale
                    $titulo->transacao = $model->criacao;
                    $titulo->sistema = $model->criacao;
                    $titulo->emissao = $model->criacao;
                    $titulo->vencimento = $vencimento;
                    $titulo->vencimentooriginal = $vencimento;
                    $titulo->save();
                }
            }
            
            // Gera Titulo de Credito
            $numero = str_pad($model->codvalecompra, 8, '0', STR_PAD_LEFT);
            $numero = "V{$numero}-CR";

            $titulo = new Titulo();
            $titulo->numero = $numero;
            $titulo->codpessoa = $model->codpessoafavorecido;
            $titulo->codfilial = $model->codfilial;
            $titulo->credito = $model->total;
            $titulo->codtipotitulo = 3; //Vale Compras
            $titulo->codcontacontabil = 83; //Credito Vale
            $titulo->transacao = $model->criacao;
            $titulo->sistema = $model->criacao;
            $titulo->emissao = $model->criacao;
            $titulo->vencimento = $model->criacao->addYear(1);
            $titulo->vencimentooriginal = $titulo->vencimento;
            $titulo->save();
            
            $model->codtitulo = $titulo->codtitulo;
            $model->save();
            
        }
        
        Session::flash('flash_success', "Vale Compras '" . formataCodigo($model->codvalecompra) . "' criado!");
        
        DB::commit();
        return redirect("vale-compra/$model->codvalecompra?imprimir=true");
    }
   
    public function inativar(Request $request)
    {
        if ($request->get('acao') != 'inativar') {
            return json_encode(['resultado' => false, 'mensagem' => 'Impossível reativar vale estornado!']);
        }
        try{
            DB::BeginTransaction();
            $model = ValeCompra::findOrFail($request->get('id'));
            if (!$model->Titulo->estornar()) {
                return json_encode(['resultado' => false, 'mensagem' => "Erro ao estornar titulo de Crédito '{$model->Titulo->numero}'!"]);
            }
            
            foreach ($model->ValeCompraFormaPagamentoS as $pag) {
                foreach ($pag->TituloS as $titulo) {
                    if (!$titulo->estornar()) {
                        return json_encode(['resultado' => false, 'mensagem' => "Erro ao estornar titulo '{$titulo->numero}'!"]);
                    }
                }
            }
            
            $model->inativo = Carbon::now();
            
            if (!$model->save()) {
                return json_encode(['resultado' => false, 'mensagem' => "Erro ao salvar data de inativacao!"]);
            }
            
            DB::Commit();
            $ret = ['resultado' => true, 'mensagem' => "Vale " . formataCodigo($model->codvalecompra) . " inativado com sucesso!"];
        }
        catch(\Exception $e){
            $ret = ['resultado' => false, 'mensagem' => "Erro ao inativar vale!", 'exception' => $e];
        }
        return json_encode($ret);
    }
    
    public function imprimir($id, Request $request) 
    {
        // Pega modelo
        $model = ValeCompra::findOrFail($id);
        
        // Se inativo retorna 403
        if (!empty($model->inativo)) {
            abort(403, 'Não é permitido imprimir vale inativado!');
        }
        
        // Monta Relatorio
        $rel = new EscPrintValeCompra($model);
        $rel->prepara();
        
        // Imprime
		if ($request->get('imprimir') == 'true') {
			$rel->imprimir();
        }
        
        // Retorna relatorio em formato HTML
        return $rel->converteHtml();
    }
    
}
