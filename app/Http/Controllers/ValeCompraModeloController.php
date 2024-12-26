<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use MGLara\Http\Controllers\Controller;

use MGLara\Models\ValeCompraModelo;
use MGLara\Models\ValeCompraModeloProdutoBarra;


class ValeCompraModeloController extends Controller
{
    public function __construct()
    {
        /*
        $this->middleware('permissao:vale-compra-modelo.inclusao', ['only' => ['create', 'store']]);
        $this->middleware('permissao:vale-compra-modelo.alteracao', ['only' => ['edit', 'update']]);
        $this->middleware('permissao:vale-compra-modelo.exclusao', ['only' => ['delete', 'destroy']]);
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
        $parametros = self::filtroEstatico($request, 'vale-compra-modelo.index', ['ativo' => 1]);
        $model = ValeCompraModelo::search($parametros)
            ->orderBy('ano', 'DESC')
            ->orderBy('codpessoafavorecido', 'ASC')
            ->orderBy('turma', 'ASC')
            ->orderBy('modelo', 'ASC')
            ->paginate(20);
        return view('vale-compra-modelo.index', compact('model'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = ValeCompraModelo::findOrFail($id);
        return view('vale-compra-modelo.show', compact('model'));
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $model = new ValeCompraModelo();
        return view('vale-compra-modelo.create', compact('model'));
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

        $model = new ValeCompraModelo($dados);
        $model->totalprodutos = array_sum($dados['item_total']);
        $model->total = ((float)$model->totalprodutos) - ((float)$model->desconto);

        if (!$model->validate()) {
            $this->throwValidationException($request, $model->_validator);
        }

        if ($model->save()) {
            foreach ($dados['item_codprodutobarra'] as $key => $codprodutobarra) {
                if (empty($codprodutobarra)) {
                    continue;
                }

                $prod = new ValeCompraModeloProdutoBarra([
                    'codvalecompramodelo' => $model->codvalecompramodelo,
                    'codprodutobarra' => $codprodutobarra,
                    'quantidade' => $dados['item_quantidade'][$key],
                    'preco' => $dados['item_preco'][$key],
                    'total' => $dados['item_total'][$key],
                ]);

                $prod->save();
            }
        }

        Session::flash('flash_success', "Modelo de Vale Compras '{$model->modelo}' criado!");

        DB::commit();
        return redirect("vale-compra-modelo/$model->codvalecompramodelo");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = ValeCompraModelo::findOrFail($id);
        $produto = $model->Produto;
        return view('vale-compra-modelo.edit',  compact('model'));
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
        $dados = $request->all();
        $model = ValeCompraModelo::findOrFail($id);
        $model->fill($dados);
        $model->totalprodutos = array_sum($dados['item_total']);
        $model->total = floatval($model->totalprodutos) - floatval($model->desconto);

        if (!$model->validate()) {
            $this->throwValidationException($request, $model->_validator);
        }

        DB::beginTransaction();

        if ($model->save()) {
            $codvalecompramodeloprodutobarra = [];
            foreach ($dados['item_codprodutobarra'] as $key => $codprodutobarra) {
                if (empty($codprodutobarra)) {
                    continue;
                }
                $dados_prod = [
                    'codvalecompramodelo' => $model->codvalecompramodelo,
                    'codprodutobarra' => $codprodutobarra,
                    'quantidade' => $dados['item_quantidade'][$key],
                    'preco' => $dados['item_preco'][$key],
                    'total' => $dados['item_total'][$key],
                ];

                if (!empty($dados['item_codvalecompramodeloprodutobarra'][$key])) {
                    $prod = ValeCompraModeloProdutoBarra::findOrFail($dados['item_codvalecompramodeloprodutobarra'][$key]);
                    $prod->fill($dados_prod);
                } else {
                    $prod = new ValeCompraModeloProdutoBarra($dados_prod);
                }

                $prod->save();
                $codvalecompramodeloprodutobarra[] = $prod->codvalecompramodeloprodutobarra;
            }

            $model->ValeCompraModeloProdutoBarraS()->whereNotIn('codvalecompramodeloprodutobarra', $codvalecompramodeloprodutobarra)->delete();
        }
        DB::commit();
        Session::flash('flash_success', "Modelo de Vale Compras '{$model->modelo}' atualizado!");
        return redirect("vale-compra-modelo/$model->codvalecompramodelo");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $model = ValeCompraModelo::find($id);
            foreach ($model->ValeCompraModeloProdutoBarraS as $vcmpb) {
                $vcmpb->delete();
            }
            if ($model->delete()) {
                DB::commit();
                $ret = ['resultado' => true, 'mensagem' => 'Modelo de Vale Compras excluído com sucesso!'];
            } else {
                $ret = ['resultado' => false, 'mensagem' => 'Erro ao excluir Modelo de Vale Compras!'];
            }
        } catch (\Exception $e) {
            $ret = ['resultado' => false, 'mensagem' => 'Erro ao excluir Modelo de Vale Compras!', 'exception' => $e];
        }
        return json_encode($ret);
    }

    /*
    public function listagemJson(Request $request)
    {
        if($request->get('q')) {

            $query = DB::table('vwprodutobarra');

            $tokens = $request->get('q');

            // Decide Ordem
            $ordem = (strstr($tokens, '$'))?
                    'vwprodutobarra.preco ASC, vwprodutobarra.produto ASC, vwprodutobarra.quantidade ASC nulls first, vwprodutobarra.descricao asc':
                    'vwprodutobarra.produto ASC, vwprodutobarra.quantidade ASC nulls first, vwprodutobarra.descricao asc';

            // Limpa string
            $tokens = str_replace('$', ' ', $tokens);
            $tokens = trim(preg_replace('/(\s\s+|\t|\n)/', ' ', $tokens));
            $tokens = explode(' ', $tokens);

            // Percorre todas strings
            foreach ($tokens as $str) {
                $query->where(function ($q2) use ($str) {

                    $q2->where('descricao', 'ILIKE', "%$str%");

                    if ($str == formataNumero((float) str_replace(',', '.', $str), 2)) {
                        $q2->orWhere('preco', '=', (float) str_replace(',', '.', $str));
                    } else {
                        if (strlen($str) == 6 & is_numeric($str)) {
                            $q2->orWhere('codproduto', '=', $str);
                        }
                        if (is_numeric($str)) {
                            $q2->orWhere('barras', 'ilike', "%$str%");
                        }
                    }
                });
            }

            switch ($request->get('ativo')) {

                case 2: //Inativo
                    $query->whereNotNull('inativo');
                    break;

                case 1: //Ativo
                    $query->whereNull('inativo');
                    break;

                case 9: //Todos
                default:

            }

            $query->select('codprodutobarra', 'descricao', 'sigla', 'codproduto', 'barras', 'preco', 'referencia', 'inativo', 'secaoproduto', 'familiaproduto', 'grupoproduto', 'subgrupoproduto', 'marca')
                ->orderByRaw($ordem)
                ->paginate(20);

            $dados = $query->get();
            //dd($query);
            $resultado = [];
            foreach ($dados as $item => $value)
            {
                $resultado[$item]=[
                    'id'               => $value->codprodutobarra,
                    'barras'           => $value->barras,
                    'codproduto'       => formataCodigo($value->codproduto, 6),
                    'produto'          => $value->descricao,
                    'preco'            => formataNumero($value->preco),
                    'referencia'       => $value->referencia,
                    'inativo'          => $value->inativo,
                    'secaoproduto'     => $value->secaoproduto,
                    'familiaproduto'   => $value->familiaproduto,
                    'grupoproduto'     => $value->grupoproduto,
                    'subgrupoproduto'  => $value->subgrupoproduto,
                    'marca'            => $value->marca,
                    'unidademedida'    => $value->sigla,
                ];
            }

            return $resultado;

        } elseif($request->get('id')) {

            $query = DB::table('tblproduto')
                    ->where('codprodutobarra', '=', $request->get('id'))
                    ->select('codprodutobarra as id', 'produto', 'barras', 'referencia', 'preco')
                    ->first();

            return $query;
        }
    }
    */

    public function inativar(Request $request)
    {
        try {
            $model = ValeCompraModelo::findOrFail($request->get('id'));
            if ($request->get('acao') == 'ativar') {
                $model->inativo = null;
            } else {
                $model->inativo = Carbon::now();
            }
            $model->save();
            $acao = ($request->get('acao') == 'ativar') ? 'ativado' : 'inativado';
            $ret = ['resultado' => true, 'mensagem' => "Vale $model->vale $acao com sucesso!"];
        } catch (\Exception $e) {
            $ret = ['resultado' => false, 'mensagem' => "Erro ao $acao vale!", 'exception' => $e];
        }
        return json_encode($ret);
    }
}
