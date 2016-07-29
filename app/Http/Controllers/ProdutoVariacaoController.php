<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

use MGLara\Http\Controllers\Controller;

use MGLara\Models\ProdutoVariacao;
use MGLara\Models\ProdutoBarra;
use MGLara\Models\Produto;


class ProdutoVariacaoController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $model = new ProdutoVariacao();
        $produto = Produto::findOrFail($request->codproduto);
        return view('produto-variacao.create', compact('model', 'produto'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model = new ProdutoVariacao($request->all());
        
        $model->codproduto = $request->input('codproduto');
        
        DB::beginTransaction();
        
        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);
        
        try {
            if (!$model->save())
                throw new Exception ('Erro ao Criar Variação!');

            $pb = new ProdutoBarra();
            $pb->codproduto = $model->codproduto;
            $pb->codprodutovariacao = $model->codprodutovariacao;
            $pb->barras = str_pad($model->codproduto, 6, '0', STR_PAD_LEFT);

            if (!empty($model->variacao))
                $pb->barras .= '-' . str_pad($model->codprodutovariacao, 8, '0', STR_PAD_LEFT);
            
            if (!$pb->save())
                throw new Exception ('Erro ao Criar Barras!');
            
            $i = 0;
            foreach ($model->Produto->ProdutoEmbalagemS as $pe)
            {
                $pb = new ProdutoBarra();
                $pb->codproduto = $model->codproduto;
                $pb->codprodutovariacao = $model->codprodutovariacao;
                $pb->codprodutoembalagem = $pe->codprodutoembalagem;
                $pb->barras = str_pad($model->codproduto, 6, '0', STR_PAD_LEFT);
                
                if (!empty($model->variacao))
                    $pb->barras .= '-' . str_pad($model->codprodutovariacao, 8, '0', STR_PAD_LEFT);
                
                $pb->barras .= '-' . formataNumero($pe->quantidade, 0);
                
                echo $pb->barras;
                echo '<hr>';
                
                if (!$pb->save())
                    throw new Exception ("Erro ao Criar Barras da embalagem {$pe->descricao}!");
                
                $i++;
            }
            
            
            DB::commit();
            Session::flash('flash_success', "Variação '{$model->variacao}' criada!");
            return redirect("produto/$model->codproduto");               
            
        } catch (Exception $ex) {
            DB::rollBack();
            $this->throwValidationException($request, $model->_validator);              
        }
        
        return redirect("produto/$model->codproduto");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = ProdutoVariacao::findOrFail($id);
        $produto = $model->Produto;
        return view('produto-variacao.edit',  compact('model', 'produto'));
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
        $model = ProdutoVariacao::findOrFail($id);
        $model->fill($request->all());
        
        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);
        
        $model->save();
        Session::flash('flash_success', "Variação '{$model->variacao}' alterada!");
        return redirect("produto/$model->codproduto");     
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
            ProdutoVariacao::find($id)->delete();
            $ret = ['resultado' => true, 'mensagem' => 'Variação excluída com sucesso!'];
        }
        catch(\Exception $e){
            $ret = ['resultado' => false, 'mensagem' => 'Erro ao excluir variação!', 'exception' => $e];
        }
        return json_encode($ret);
    }

    public function listagemJson(Request $request) 
    {
        
        if (!empty($request->id)) {
            
            $model = ProdutoVariacao::findOrFail($request->id);
            $ret['id'] = $model->codprodutovariacao;
            $ret['variacao'] = empty($variacao)?'{ Sem Variacao}':$variacao;
            
        } else {
            
            $regs = ProdutoVariacao::where('codproduto', '=', $request->codproduto)->lists('variacao', 'codprodutovariacao');
            
            foreach ($regs as $id => $variacao) {
                $ret[] = [
                    'id' => $id, 
                    'variacao' => empty($variacao)?'{ Sem Variacao}':$variacao
                ];
            }
        }
        
        return  response()->json($ret);
    }
    
}
