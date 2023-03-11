<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use URL;

use MGLara\Http\Controllers\Controller;
use MGLara\Models\Produto;
use MGLara\Models\ProdutoBarra;
use MGLara\Models\ProdutoVariacao;
use MGLara\Models\ProdutoEmbalagem;
use MGLara\Models\NegocioProdutoBarra;
use MGLara\Models\NotaFiscalProdutoBarra;
use MGLara\Models\TipoProduto;
use MGLara\Models\ProdutoHistoricoPreco;

class ProdutoController extends Controller
{
    public function __construct()
    {
        // Permissoes
        $this->middleware('permissao:produto.consulta', ['only' => ['index', 'show']]);
        $this->middleware('permissao:produto.inclusao', ['only' => ['create', 'store']]);
        $this->middleware('permissao:produto.alteracao', ['only' => ['edit', 'update', 'transferirVariacao', 'transferirVariacaoSalvar']]);
        $this->middleware('permissao:produto.exclusao', ['only' => ['delete', 'destroy']]);
        $this->middleware('permissao:produto.inativacao', ['only' => ['inativo']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {

        // Busca filtro da sessao
        $parametros = self::filtroEstatico(
            $request,
            'produto.index',
            ['ativo' => '1'],
            ['criacao_de', 'criacao_ate', 'alteracao_de', 'alteracao_ate']
        );

        $model = Produto::search($parametros)->orderBy('produto', 'ASC')->paginate(20);

        return view('produto.index', compact('model'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $model = new Produto;

        if ($request->get('duplicar'))
        {
            $duplicar = Produto::findOrFail($request->get('duplicar'));
            $model->fill($duplicar->getAttributes());
        }
        else
        {
            $model->codtipoproduto = TipoProduto::MERCADORIA;
            $model->abc = 'C';
        }

        return view('produto.create', compact('model'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $model = new Produto($request->all());

        if(is_null($request->input('importado'))) {
            $model->importado = FALSE;
        }
        if(is_null($request->input('estoque'))) {
            $model->estoque = FALSE;
        }
        if(is_null($request->input('site'))) {
            $model->site = FALSE;
        }

        DB::beginTransaction();

        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);

        try {
            if (!$model->save())
                throw new Exception ('Erro ao Criar Produto!');

            $pv = new ProdutoVariacao();
            $pv->codproduto = $model->codproduto;

            if (!$pv->save())
                throw new Exception ('Erro ao Criar Variação!');

            $pb = new ProdutoBarra();
            $pb->codproduto = $model->codproduto;
            $pb->codprodutovariacao = $pv->codprodutovariacao;
            //$pb->barras = str_pad($model->codproduto, 6, '0', STR_PAD_LEFT);

            if (!$pb->save())
                throw new Exception ('Erro ao Criar Barras!');

            DB::commit();
            Session::flash('flash_success', "Produto '{$model->produto}' criado!");
            return redirect("produto/$model->codproduto");

        } catch (Exception $ex) {
            DB::rollBack();
            $this->throwValidationException($request, $model->_validator);
        }

    }

    public function show(Request $request, $id)
    {
        $model = Produto::findOrFail($id);
        $nfpbs = null;
        $npbs = null;
        $parametros = null;
        $estoque = null;
        switch ($request->get('_div'))
        {
            case 'div-imagens':
                $view = 'produto.show-imagens';
                break;
            case 'div-variacoes':
                $view = 'produto.show-variacoes';
                break;
            case 'div-embalagens':
                $view = 'produto.show-embalagens';
                break;
            case 'div-negocios':
                $parametrosNpb = self::filtroEstatico($request, 'produto.show.npb', [], ['negocio_lancamento_de', 'negocio_lancamento_ate']);
                $npbs = NegocioProdutoBarra::search($parametrosNpb, 10);
                $view = 'produto.show-negocios';
                break;
            case 'div-notasfiscais':
                $parametrosNfpb = self::filtroEstatico($request, 'produto.show.nfpb', [], ['notasfiscais_lancamento_de', 'notasfiscais_lancamento_ate']);
                $nfpbs = NotaFiscalProdutoBarra::search($parametrosNfpb, 10);
                $view = 'produto.show-notasfiscais';
                break;
            case 'div-estoque':
                $estoque = $model->getArraySaldoEstoque();
                $view = 'produto.show-estoque';
                break;
            default:
                $view = 'produto.show';
        }
        $pes = $model->ProdutoEmbalagemS()->orderBy('quantidade')->get();
        $pvs = $model->ProdutoVariacaoS()->orderBy(DB::raw("coalesce(variacao, '')"), 'ASC')->get();

        $ret = view($view, compact('model', 'nfpbs', 'npbs', 'parametros', 'estoque', 'pes', 'pvs'));

        return $ret;
    }

    public function edit($id)
    {
        $model = Produto::findOrFail($id);
        return view('produto.edit',  compact('model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $model = Produto::findOrFail($id);
        $model->fill($request->all());

        if(is_null($request->input('importado'))) {
            $model->importado = FALSE;
        }
        if(is_null($request->input('estoque'))) {
            $model->estoque = FALSE;
        }
        if(is_null($request->input('site'))) {
            $model->site = FALSE;
        }

        DB::beginTransaction();

        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);

        try {
            $preco = $model->getOriginal('preco');

            if (!$model->save())
                throw new Exception ('Erro ao alterar Produto!');
            if($preco != $model->preco) {
                $historico = new ProdutoHistoricoPreco();
                $historico->codproduto  = $model->codproduto;
                $historico->precoantigo = $preco;
                $historico->preconovo   = $model->preco;
                if (!$historico->save())
                    throw new Exception ('Erro ao gravar Historico!');
            }

            DB::commit();
            Session::flash('flash_success', "Produto '{$model->produto}' alterado!");
            return redirect("produto/$model->codproduto");
        } catch (Exception $ex) {
            DB::rollBack();
            $this->throwValidationException($request, $model->_validator);
        }

    }

    public function populaSecaoProduto(Request $request) {
        $model = Produto::find($request->get('id'));
        $retorno = [
            'secaoproduto' => $model->SubGrupoProduto->GrupoProduto->FamiliaProduto->SecaoProduto->codsecaoproduto,
            'familiaproduto' => $model->SubGrupoProduto->GrupoProduto->FamiliaProduto->codfamiliaproduto,
            'grupoproduto' => $model->SubGrupoProduto->GrupoProduto->codgrupoproduto,
            'subgrupoproduto' => $model->SubGrupoProduto->codsubgrupoproduto,
        ];

        return response()->json($retorno);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            Produto::find($id)->delete();
            $ret = ['resultado' => true, 'mensagem' => 'Produto excluído com sucesso!'];
        }
        catch(\Exception $e){
            $ret = ['resultado' => false, 'mensagem' => 'Erro ao excluir produto!', 'exception' => $e];
        }
        return json_encode($ret);
    }


    public function buscaPorBarras(Request $request)
    {
        $barra = ProdutoBarra::buscaPorBarras($request->get('barras'));
        return response()->json($barra);
    }


    public function listagemJson(Request $request)
    //public function listagemJson($texto, $inativo = false, $limite = 20, $pagina = 1)
    {
        $pagina = $request->get('page');
        $limite = $request->get('per_page');
        $inativo = $request->get('inativo');
        // limpa texto
        $ordem = (strstr($request->get('q'), '$'))?'preco ASC, descricao ASC':'descricao ASC, preco ASC';
        $texto = str_replace('$', '', $request->get('q'));
        $texto  = str_replace(' ', '%', trim($request->get('q')));

        // corrige pagina se veio sujeira
        if ($pagina < 1) $pagina = 1;

        // calcula de onde continuar a consulta
        $offset = ($pagina-1)*$limite;

        // inicializa array com resultados
        $resultados = array();

        // se o texto foi preenchido
        #if (strlen($texto)>=3)
        #{
            $sql = "SELECT codprodutobarra as id, codproduto, barras, descricao, sigla, preco, marca, referencia
                          FROM vwProdutoBarra
                         WHERE codProdutoBarra is not null ";

            #if (!$inativo) {
            #    $sql .= "AND Inativo is null ";
            #}

        $sql .= " AND (";

            // Verifica se foi digitado um valor e procura pelo preco
        #    If ((Yii::app()->format->formatNumber(Yii::app()->format->unformatNumber($texto)) == $texto)
        #            && (strpos($texto, ",") != 0)
        #            && ((strlen($texto) - strpos($texto, ",")) == 3))
        #    {
        #            $sql .= "preco = :preco";
        #            $params = array(
        #                    ':preco'=>Yii::app()->format->unformatNumber($texto),
        #                    );
        #}

            //senao procura por barras, descricao, marca e referencia
            #else
            #{
                    $sql .= "barras ilike '%$texto%' ";
                    $sql .= "OR descricao ilike '%$texto%' ";
                    $sql .= "OR marca ilike '%$texto%' ";
                    $sql .= "OR referencia ilike '%$texto%' ";

                    /*$params = array(
                        ':texto'=>'%'.$texto.'%',
                    );*/
            #}

            //ordena
            $sql .= ") ORDER BY $ordem LIMIT $limite OFFSET $offset";

            $resultados = DB::select($sql);

            for ($i=0; $i<sizeof($resultados);$i++)
            {
                    $resultados[$i]->codproduto = \formataCodigo($resultados[$i]->codproduto, 6);
                    $resultados[$i]->preco = \formataNumero($resultados[$i]->preco);
                    if (empty($resultados[$i]->referencia))
                            $resultados[$i]->referencia = "-";
            }

            return response()->json($resultados);

    }

    public function listagemJsonProduto(Request $request)
    {
        if($request->get('q')) {

            $query = DB::table('tblproduto')
                ->join('tblsubgrupoproduto', function($join) {
                    $join->on('tblsubgrupoproduto.codsubgrupoproduto', '=', 'tblproduto.codsubgrupoproduto');
                })
                ->join('tblgrupoproduto', function($join) {
                    $join->on('tblgrupoproduto.codgrupoproduto', '=', 'tblsubgrupoproduto.codgrupoproduto');
                })
                ->join('tblfamiliaproduto', function($join) {
                    $join->on('tblfamiliaproduto.codfamiliaproduto', '=', 'tblgrupoproduto.codfamiliaproduto');
                })
                ->join('tblsecaoproduto', function($join) {
                    $join->on('tblsecaoproduto.codsecaoproduto', '=', 'tblfamiliaproduto.codsecaoproduto');
                })
                ->join('tblmarca', function($join) {
                    $join->on('tblmarca.codmarca', '=', 'tblproduto.codmarca');
                });

                $produto = $request->get('q');

                if (strlen($produto) == 6 & is_numeric($produto)) {
                    $query->where('codproduto', '=', $produto);
                }
                else {
                    $produto = explode(' ', $produto);
                    foreach ($produto as $str) {
                        $query->where('produto', 'ILIKE', "%$str%");
                    }
                }
                $query->select('codproduto as id', 'produto', 'preco', 'referencia', 'tblproduto.inativo', 'tblsecaoproduto.secaoproduto', 'tblfamiliaproduto.familiaproduto', 'tblgrupoproduto.grupoproduto', 'tblsubgrupoproduto.subgrupoproduto', 'tblmarca.marca')
                    ->orderBy('produto', 'ASC')
                    ->paginate(20);

            $dados = $query->get();
            $resultado = [];
            foreach ($dados as $item => $value)
            {
                $resultado[$item]=[
                    'id'        =>  $value->id,
                    'codigo'    => formataCodigo($value->id, 6),
                    'produto'   => $value->produto,
                    'preco'     => formataNumero($value->preco),
                    'referencia'=> $value->referencia,
                    'inativo'   => $value->inativo,
                    'secaoproduto'     => $value->secaoproduto,
                    'familiaproduto'   => $value->familiaproduto,
                    'grupoproduto'     => $value->grupoproduto,
                    'subgrupoproduto'  => $value->subgrupoproduto,
                    'marca'     => $value->marca
                ];
            }
            return response()->json($resultado);
        } elseif($request->get('id')) {
            $query = DB::table('tblproduto')
                    ->where('codproduto', '=', $request->get('id'))
                    ->select('codproduto as id', 'produto', 'referencia', 'preco')
                    ->first();

            return response()->json($query);
        }
    }

    public function listagemJsonDescricao(Request $request)
    {
        $parametros['produto'] = $request->get('q');
        $parametros['codsubgrupoproduto'] = $request->get('codsubgrupoproduto');

        $sql = Produto::search($parametros)
            ->select('produto', 'codproduto')
            ->where('codproduto', '<>',  ($request->get('codproduto')?$request->get('codproduto'):0))
            ->orderBy('produto', 'DESC')
            ->limit(15)
            ->get();

        $resultado = [];
        foreach ($sql as $key => $value) {
            $resultado[] = [
                'produto' => $value['produto'],
                'codproduto' => $value['codproduto']
                ];
        }

        return  response()->json($resultado);
    }

    public function inativar(Request $request)
    {
        try{
            $model = Produto::find($request->get('id'));
            if ($request->get('acao') == 'ativar') {
                $model->inativo = null;
            } else {
                $model->inativo = Carbon::now();
            }

            $model->save();
            $acao = ($request->get('acao') == 'ativar') ? 'ativado' : 'inativado';
            $ret = ['resultado' => true, 'mensagem' => "Produto $model->produto $acao com sucesso!"];
        }
        catch(\Exception $e){
            $ret = ['resultado' => false, 'mensagem' => "Erro ao $acao produto!", 'exception' => $e];
        }
        return json_encode($ret);
    }

    public function estoqueSaldo(Request $request)
    {
        $query = DB::table('tblestoquesaldo')
            ->join('tblestoquelocalprodutovariacao', 'tblestoquelocalprodutovariacao.codestoquelocalprodutovariacao', '=', 'tblestoquesaldo.codestoquelocalprodutovariacao')
            ->where('codproduto', '=', $request->get('codproduto'))
            ->select('customedio', 'saldovalor', 'saldoquantidade');

        if($request->get('codestoquelocal')) $query->where('tblestoquelocalprodutovariacao.codestoquelocal', '=', $request->get('codestoquelocal'));
        if($request->get('fiscal') == 1)
            $query->where('fiscal', '=', true);
        else
            $query->where('fiscal', '=', false);
        $resultado = $query->get();

        return response()->json($resultado);
    }

    public function transferirVariacao(Request $request, $id)
    {
        $model = Produto::findOrFail($id);
        return view('produto.transferir-variacao',  compact('model'));
    }

    public function transferirVariacaoSalvar(Request $request, $id)
    {
        $form = $request->all();

        $validator = Validator::make(
            $form,
            [
                'codproduto'           => "required",
                'codprodutovariacao'   => 'required',
            ],
            [
                'codproduto.required'           => 'Selecione o produto de destino!',
                'codprodutovariacao.required'   => 'Selecione uma variação!',
            ]
        );

        if ($validator->fails()) {
            $this->throwValidationException($request, $validator);
        }

        DB::BeginTransaction();

        foreach($form['codprodutovariacao'] as $codprodutovariacao) {

            $pv = ProdutoVariacao::findOrFail($codprodutovariacao);
            $pv->codproduto = $form['codproduto'];
            $pv->save();

            foreach($pv->ProdutoBarraS as $pb) {

                $pb->codproduto = $form['codproduto'];

                if (!empty($pb->codprodutoembalagem)) {

                    $pe = ProdutoEmbalagem::where([
                        'codproduto' => $form['codproduto'],
                        'quantidade' => $pb->ProdutoEmbalagem->quantidade,
                    ])->first();

                    if (!$pe) {
                        $pe = new ProdutoEmbalagem;
                        $pe->codproduto = $form['codproduto'];
                        $pe->quantidade = $pb->ProdutoEmbalagem->quantidade;
                        $pe->codunidademedida = $pb->ProdutoEmbalagem->codunidademedida;
                        $pe->preco = $pb->ProdutoEmbalagem->preco;
                        $pe->save();
                    }

                    $pb->codprodutoembalagem = $pe->codprodutoembalagem;

                }

                $pb->save();
            }

        }
        //DB::rollback();
        DB::commit();

        return redirect("produto/{$form['codproduto']}");

        dd($validator);
    }

    public function consulta (Request $request, $barras)
    {

        if (!$barras = ProdutoBarra::buscaPorBarras($barras)) {
            return [
                'resultado' => false,
                'mensagem' => 'Nenhum produto localizado!',
            ];
        }

        // Imagens
        $imagens = [];
        foreach ($barras->Produto->ImagemS as $imagem) {
            $imagens[] = [
                'codimagem' => $imagem->codimagem,
                'url' => URL::asset('public/imagens/'.$imagem->observacoes),
            ];
        }
        if (sizeof($imagens) == 0) {
            $imagens[] = [
                'codimagem' => null,
                'url' => URL::asset('public/imagens/semimagem.jpg'),
            ];
        }

        // Variacoes
        $variacoes = [];
        $estoquelocais = [];
        foreach ($barras->Produto->ProdutoVariacaoS()->orderByRaw("variacao asc nulls first")->get() as $pv) {
            $produtobarras = [];
            foreach ($pv->ProdutoBarraS as $pb) {
                $produtobarras[] = [
                    'codprodutobarra' => $pb->codprodutobarra,
                    'codprodutoembalagem' => $pb->codprodutoembalagem,
                    'barras' => $pb->barras,
                    'detalhes' => $pb->variacao,
                    'referencia' => ($pb->referencia??$pb->ProdutoVariacao->referencia)??$pb->ProdutoVariacao->Produto->referencia,
                    'unidademedida' => $pb->UnidadeMedida->sigla,
                    'quantidade' => (!empty($pb->codprodutoembalagem)?(float)$pb->ProdutoEmbalagem->quantidade:null),
                ];
            }
            $saldos = [];
            $saldo = 0;
            foreach ($pv->EstoqueLocalProdutoVariacaoS()->orderBy('codestoquelocal')->get() as $elpv) {
                foreach ($elpv->EstoqueSaldoS()->where('fiscal', false)->get() as $es) {
                    $saldo += (float)$es->saldoquantidade;
                    $estoquelocais[$elpv->codestoquelocal] = [
                        'codestoquelocal' => $elpv->codestoquelocal,
                        'estoquelocal' => $elpv->EstoqueLocal->estoquelocal,
                    ];
                    $saldos[$elpv->codestoquelocal] = [
                        'codestoquesaldo' => $es->codestoquesaldo,
                        'url' => url("estoque-saldo/{$es->codestoquesaldo}"),
                        'codestoquelocal' => $elpv->codestoquelocal,
                        'saldoquantidade' => (float)$es->saldoquantidade,
                        'saldovalor' => (float)$es->saldovalor,
                    ];
                }
            }
            $variacoes[] = [
                'codprodutovariacao' => $pv->codprodutovariacao,
                'referencia' => $pv->referencia??$pv->Produto->referencia,
                'marca' => (!empty($pv->codmarca)?$pv->Marca->marca:null),
                'variacao' => $pv->variacao,
                'barras' => $produtobarras,
                'saldo' => $saldo,
                'saldos' => $saldos,
            ];
        }

        // Embalagens
        $embalagens[] = [
            'codprodutoembalagem' => null,
            'quantidade' => null,
            'unidademedida' => $barras->Produto->UnidadeMedida->unidademedida,
            'preco' => (float)$barras->Produto->preco,
            'precocalculado' => false,
        ];
        foreach ($barras->Produto->ProdutoEmbalagemS()->orderBy('quantidade')->get() as $embalagem) {
            $embalagens[] = [
                'codprodutoembalagem' => $embalagem->codprodutoembalagem,
                'quantidade' => (float)$embalagem->quantidade,
                'unidademedida' => $embalagem->UnidadeMedida->unidademedida,
                'preco' => (float)(!empty($embalagem->preco)?$embalagem->preco:$embalagem->quantidade * $barras->Produto->preco),
                'precocalculado' => empty($embalagem->preco),
            ];
        }

        //dd($barras->Produto->SubGrupoProduto->GrupoProduto->FamiliaProduto->SecaoProduto->codimagem);
        //dd($barras->Produto->SubGrupoProduto->codimagem);

        $produto = [
            'codproduto' => $barras->codproduto,
            'url' => url("produto/{$barras->codproduto}"),
            'codprodutobarra' => $barras->codprodutobarra,
            'barras' => $barras->barras,
            'produto' => $barras->descricao(),
            'inativo' => $barras->Produto->inativo,
            'unidademedida' => $barras->UnidadeMedida->unidademedida,
            'slglaunidademedida' => $barras->UnidadeMedida->sigla,
            'referencia' => ($barras->referencia??$barras->ProdutoVariacao->referencia)??$barras->ProdutoVariacao->Produto->referencia,
            'marca' => [
                'codmarca' => $barras->Marca->codmarca,
                'marca' => $barras->Marca->marca,
                'url' => url("marca/{$barras->Marca->codmarca}"),
                'urlimagem' => (!empty($barras->Marca->codimagem)?URL::asset('public/imagens/'.$barras->Marca->Imagem->observacoes):null),
            ],
            'subgrupoproduto' => [
                'codsubgrupoproduto' => $barras->Produto->codsubgrupoproduto,
                'subgrupoproduto' => $barras->Produto->SubGrupoProduto->subgrupoproduto,
                'urlimagem' => (!empty($barras->Produto->SubGrupoProduto->codimagem)?URL::asset('public/imagens/'.$barras->Produto->SubGrupoProduto->Imagem->observacoes):null),
                'url' => url("sub-grupo-produto/{$barras->Produto->codsubgrupoproduto}"),
            ],
            'grupoproduto' => [
                'codgrupoproduto' => $barras->Produto->SubGrupoProduto->codgrupoproduto,
                'grupoproduto' => $barras->Produto->SubGrupoProduto->GrupoProduto->grupoproduto,
                'urlimagem' => (!empty($barras->Produto->SubGrupoProduto->GrupoProduto->codimagem)?URL::asset('public/imagens/'.$barras->Produto->SubGrupoProduto->GrupoProduto->Imagem->observacoes):null),
                'url' => url("grupo-produto/{$barras->Produto->SubGrupoProduto->codgrupoproduto}"),
            ],
            'familiaproduto' => [
                'codfamiliaproduto' => $barras->Produto->SubGrupoProduto->GrupoProduto->codfamiliaproduto,
                'familiaproduto' => $barras->Produto->SubGrupoProduto->GrupoProduto->FamiliaProduto->familiaproduto,
                'urlimagem' => (!empty($barras->Produto->SubGrupoProduto->GrupoProduto->FamiliaProduto->codimagem)?URL::asset('public/imagens/'.$barras->Produto->SubGrupoProduto->GrupoProduto->FamiliaProduto->Imagem->observacoes):null),
                'url' => url("familia-produto/{$barras->Produto->SubGrupoProduto->GrupoProduto->codfamiliaproduto}"),
            ],
            'secaoproduto' => [
                'codsecaoproduto' => $barras->Produto->SubGrupoProduto->GrupoProduto->FamiliaProduto->codsecaoproduto,
                'secaoproduto' => $barras->Produto->SubGrupoProduto->GrupoProduto->FamiliaProduto->SecaoProduto->secaoproduto,
                'urlimagem' => (!empty($barras->Produto->SubGrupoProduto->GrupoProduto->FamiliaProduto->SecaoProduto->codimagem)?URL::asset('public/imagens/'.$barras->Produto->SubGrupoProduto->GrupoProduto->FamiliaProduto->SecaoProduto->Imagem->observacoes):null),
                'url' => url("secao-produto/{$barras->Produto->SubGrupoProduto->GrupoProduto->FamiliaProduto->codsecaoproduto}"),
            ],
            'preco' => $barras->preco(),
            'imagens' => $imagens,
            'variacoes' => $variacoes,
            'embalagens' => $embalagens,
            'estoquelocais' => $estoquelocais,
        ];


        return [
            'resultado' => true,
            'produto' => $produto,
        ];
        //dd($prod);
    }

    public function quiosque (Request $request)
    {
        return view('produto.quiosque');
    }

    public function revisado($id, Request $request)
    {
        $model = Produto::findOrFail($id);
        if ($request->revisado == 1) {
            $model->update([
                'revisao' => Carbon::now()
            ]);
        } else {
            $model->update([
                'revisao' => null
            ]);
        }
        return $model->fresh();
    }

  }
