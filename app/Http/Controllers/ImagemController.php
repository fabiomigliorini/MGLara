<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;
use MGLara\Http\Requests;
use MGLara\Http\Controllers\Controller;
use MGLara\Models\Imagem;

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

    public function produto($id)
    {
        $model = Produto::find($id);
        
        
        return view('imagem.produto', compact('model'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $id)
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        //dd($request);
        $Model = '\MGLara\Models\\' . $request->get('model');
        $model = $Model::find($id);
        
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
        $diretorio = 'public/images';
                
        $Model = '\MGLara\Models\\' . $request->get('model');
        $model = new $Model($request->all());

        // Upload
        //$request->file->move($diretorio, $model->codimagem);
        //$request->file('codimagem')->move($diretorio, $model->codimagem);
        
        $imagem = new Imagem();
        $imagem->save();
        
        
        #if (!$model->validate())
        #    $this->throwValidationException($request, $model->_validator);
        
        $model->codimagem = $imagem->codimagem;
        dd($model);
        $model->save();
        Session::flash('flash_create', 'Registro inserido.');
        return redirect('marca');  
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
