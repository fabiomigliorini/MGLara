<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Http\Controllers\Controller;
use MGLara\Models\Imagem;
use MGLara\Models\Produto;
use Illuminate\Support\Facades\Input;

class ImagemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('imagem.index');
    }

    public function produto(Request $request)
    {
        $model = Produto::find($request->produto);
        return view('imagem.produto', compact('model'));
    }
    
    public function produtoStore(Request $request)
    {
        $model = Produto::find($request->get('id'));
        $codimagem = Input::file('imagem');
        $extensao = $codimagem->getClientOriginalExtension();
        
        $imagem = new Imagem();
        $imagem->save();
        
        $imagem_update = Imagem::findOrFail($imagem->codimagem);
        $imagem_update->observacoes = $imagem->codimagem.'.'.$extensao;
        $imagem_update->save();
        
        $diretorio = './public/imagens';
        $arquivo = $imagem->codimagem.'.'.$extensao;       
        
        $codimagem->move($diretorio, $arquivo);    
        $model->ImagemS()->attach($imagem->codimagem);
        Session::flash('flash_update', 'Imagem inserida.');
        return redirect('produto/'.$request->get('id')); 
    }

    public function produtoDelete(Request $request, $id)
    {
        try {
            $model = Produto::find($id);
            $model->ImagemS()->detach($request->get('imagem'));
	    Session::flash('flash_delete', 'Imagem deletada!');
	    return redirect("produto/$id"); 
        }
        catch(\Exception $e){
            return view('errors.fk');
        }         
    }

        /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = Imagem::find($id);
        return view('imagem.show', compact('model'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        //dd($request);
        $Model = '\MGLara\Models\\' . $request->get('model');
        $model = $Model::find($request->get('id'));
        
        return view('imagem.edit', compact('model', 'request'));
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
        $Model = '\MGLara\Models\\' . $request->get('model');
        $model = $Model::findOrFail($id);

        $codimagem = Input::file('codimagem');
        $extensao = $codimagem->getClientOriginalExtension();
        
        $imagem = new Imagem();
        $imagem->save();
        
        $imagem_update = Imagem::findOrFail($imagem->codimagem);
        $imagem_update->observacoes = $imagem->codimagem.'.'.$extensao;
        $imagem_update->save();
        
        $diretorio = './public/imagens';
        $arquivo = $imagem->codimagem.'.'.$extensao;       
        
        $codimagem->move($diretorio, $arquivo);    
        
        $model->codimagem = $imagem->codimagem;
        
        $model->save();
        Session::flash('flash_update', 'Registro atualizado.');
        
        return redirect(modelUrl($request->get('model')).'/'.$id);  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
