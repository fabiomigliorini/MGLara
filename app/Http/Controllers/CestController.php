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
        
        $ncm = Ncm::find($request->get('codncm'));
        $cests = $ncm->cestsDisponiveis();
        if($request->get('codncm')) {

            $resultados = [];
            dd($cests);
            foreach($cests as $cest)
            {
                $resultados[] = array(
                    'id' => $cest->codcest,
                    'ncm' => formataNcm($cest->Ncm->ncm),
                    'cest' => formataCest($cest->cest),
                    'descricao' => $cest->descricao,
                );
            }            
            return response()->json($resultados);
            
        } elseif($request->get('id')) {
            $model = Cest::find($request->get('id'));
            return response()->json($model);
        }
    }
}
