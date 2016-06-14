<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use MGLara\Http\Requests;
use MGLara\Http\Controllers\Controller;

use MGLara\Models\NegocioProdutoBarra;

class NegocioProdutoBarraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $npbs = NegocioProdutoBarra::search(
            $request->get('id'),
            $request->get('saida_de'),
            $request->get('saida_ate')
        );
        
        /*
        $dados = [];
        foreach ($npbs as $npb) {
            $dados[] = [
                'lancamento'=> formataData($npb->Negocio->lancamento, 'L'),
                'filial' => ['filial' => ''],
                'naturezaoperacao' => ['naturezaoperacao' => ''],
                'pessoa' => ['codpessoa'=>'', 'fantasia'=>''],
                'quantidade' => '',
                'unidademedida'=> ['sigla'=> ''],
                'produtoembalagem' => ['quantidade'=>''],
                'valorunitario' => '',
                'precounitario' => '',
                'codprodutobarra' => '',
                'produtobarra' => ['barras'=>''],
                'negocio' => ['codnegocio' => '']
            ];
        }
        
        
        array_merge([$npbs], $dados);
        dd($npbs);
        */
        
        //return response()->json($npbs);
        return view('negocio-produto-barra.index', compact('npbs'));
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
}
