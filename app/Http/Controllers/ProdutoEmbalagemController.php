<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

use MGLara\Http\Controllers\Controller;

use MGLara\Models\ProdutoEmbalagem;
use MGLara\Models\Produto;
use MGLara\Models\ProdutoBarra;
use MGLara\Models\ProdutoHistoricoPreco;

class ProdutoEmbalagemController extends Controller
{
    public function __construct()
    {
        $this->datas = [];
        $this->numericos = [];
        $this->middleware('permissao:produto-embalagem.inclusao', ['only' => ['create', 'store']]);
        $this->middleware('permissao:produto-embalagem.alteracao', ['only' => ['edit', 'update']]);
        $this->middleware('permissao:produto-embalagem.exclusao', ['only' => ['delete', 'destroy']]);        
    }         
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $model = new ProdutoEmbalagem();
        $produto = Produto::findOrFail($request->codproduto);
        return view('produto-embalagem.create', compact('model', 'produto'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->converteNumericos([
            'preco' => $request->input('preco'),
            'quantidade' => $request->input('quantidade')
        ]);
        $model = new ProdutoEmbalagem($request->all());
        $model->codproduto = $request->input('codproduto');
        
        DB::beginTransaction();
        
        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);
        
        try {
            if (!$model->save())
                throw new Exception ('Erro ao Criar Embalagem!');
            
            $i = 0;
            foreach ($model->Produto->ProdutoVariacaoS as $pv)
            {
                $pb = new ProdutoBarra();
                $pb->codproduto = $model->codproduto;
                $pb->codprodutovariacao = $pv->codprodutovariacao;
                $pb->codprodutoembalagem = $model->codprodutoembalagem;
                
                if (!$pb->save())
                    throw new Exception ('Erro ao Criar Barras!');
                
                $i++;
            }
            
            DB::commit();
            Session::flash('flash_success', "Embalagem '{$model->descricao}' criada!");
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
        $model = ProdutoEmbalagem::findOrFail($id);
        $produto = $model->produto;
        return view('produto-embalagem.edit',  compact('model', 'produto'));
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
        $this->converteNumericos([
            'preco' => $request->input('preco'),
            'quantidade' => $request->input('quantidade')
        ]);
        
        $model = ProdutoEmbalagem::findOrFail($id);
        $model->fill($request->all());
        
        DB::beginTransaction();
        
        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);
        
        try {
            $preco = $model->getOriginal('preco');
            
            if (!$model->save())
                throw new Exception ('Erro ao alterar Embalagem!');
            
            if($preco != $model->preco) {
                $historico = new ProdutoHistoricoPreco();
                $historico->codproduto  = $model->Produto->codproduto;
                $historico->codprodutoembalagem  = $model->codprodutoembalagem;
                $historico->precoantigo = $preco;
                $historico->preconovo   = $model->preco;
                
                if (!$historico->save())
                    throw new Exception ('Erro ao gravar Historico!');
            }
            
            DB::commit();
            Session::flash('flash_success', "Embalagem '{$model->descricao}' alterada!");
            return redirect("produto/$model->codproduto");        
            
        } catch (Exception $ex) {
            DB::rollBack();
            $this->throwValidationException($request, $model->_validator);              
        }        
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
            ProdutoEmbalagem::find($id)->delete();
            $ret = ['resultado' => true, 'mensagem' => 'Embalagem excluída com sucesso!'];
        }
        catch(\Exception $e){
            $ret = ['resultado' => false, 'mensagem' => 'Erro ao excluir embalagem!', 'exception' => $e];
        }
        return json_encode($ret);
    }    
}
