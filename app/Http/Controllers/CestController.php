<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Http\Controllers\Controller;
use MGLara\Models\Cest;
use MGLara\Models\Ncm;

class CestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
    public function edit($id)
    {
        //
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
        //
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
    
    public function ajax(Request $request) {
        
        if($request->get('codncm')) {
            $ncm = Ncm::find($request->get('codncm'));
            $cests = $ncm->cestsDisponiveis();            
            $resultados = [];
            foreach($cests as $cest)
            {
                foreach ($cest as $value) {
                    $resultados[] = array(
                        'id' => $value->codcest,
                        'ncm' => formataNcm($value->Ncm->ncm),
                        'cest' => formataCest($value->cest),
                        'descricao' => $value->descricao,
                    );
                }
            }            
            return response()->json($resultados);
            
        } elseif($request->get('id')) {
            $model = Cest::find($request->get('id'));
            return response()->json($model);
        }
    }
}