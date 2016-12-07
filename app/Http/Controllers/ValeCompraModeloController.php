<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $model = new ValeCompraModelo();
        return view('vale-compra-modelo.create', compact('model', 'produto'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd($request->all());
        $model = new ValeCompraModelo($request->all());
        $model->codproduto = $request->input('codproduto');
        
        if ($model->codprodutoembalagem == 0) {
            $model->codprodutoembalagem = null;
        }
        
        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);
        
        $model->save();
        Session::flash('flash_success', "Modelo de Vale Compras '{$model->barras}' criado!");
        return redirect("vale-compra-modelo/$model->codproduto");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*
    public function edit($id)
    {
        $model = ValeCompraModelo::findOrFail($id);
        $produto = $model->Produto;
        return view('vale-compra-modelo.edit',  compact('model', 'produto'));
    }
     * 
     */

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*
    public function update(Request $request, $id)
    {
        $model = ValeCompraModelo::findOrFail($id);
        $codprodutovariacao_original = $model->codprodutovariacao;
        $model->fill($request->all());
        
        if ($model->codprodutoembalagem == 0) {
            $model->codprodutoembalagem = null;
        }
        
        if (!$model->validate()) {
            $this->throwValidationException($request, $model->_validator);
        }
        
        $model->save();

        //Recalcula movimento de estoque caso trocou o codigo de barras de variacao
        if ($model->codprodutovariacao != $codprodutovariacao_original) {
            $this->dispatch((new EstoqueGeraMovimentoProdutoVariacao($model->codprodutovariacao))->onQueue('medium'));
            $this->dispatch((new EstoqueGeraMovimentoProdutoVariacao($codprodutovariacao_original))->onQueue('medium'));
        }
        
        Session::flash('flash_success', "Modelo de Vale Compras '{$model->barras}' atualizado!");
        return redirect("vale-compra-modelo/$model->codproduto");     
    }
     * 
     */

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*
    public function destroy($id)
    {
        try{
            ValeCompraModelo::find($id)->delete();
            $ret = ['resultado' => true, 'mensagem' => 'Modelo de Vale Compras excluído com sucesso!'];
        }
        catch(\Exception $e){
            $ret = ['resultado' => false, 'mensagem' => 'Erro ao excluir Modelo de Vale Compras!', 'exception' => $e];
        }
        return json_encode($ret);
    }
     * 
     */

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
    
}
