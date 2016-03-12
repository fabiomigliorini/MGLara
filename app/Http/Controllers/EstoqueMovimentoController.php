<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use MGLara\Http\Controllers\Controller;
use MGLara\Models\EstoqueMovimento;
use MGLara\Models\EstoqueMovimentoTipo;
use MGLara\Models\EstoqueMes;
use MGLara\Models\EstoqueLocal;

use Carbon\Carbon;
use Illuminate\Support\Facades\Input;

class EstoqueMovimentoController extends Controller
{
    
    public function __construct()
    {
        $this->datas = [];
        $this->numericos = [];
    }    
    
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
    public function create(Request $request)
    {
        $em = EstoqueMes::find($request->codestoquemes);
        $el = EstoqueLocal::lists('estoquelocal', 'codestoquelocal')->all();
        $tipos = EstoqueMovimentoTipo::lists('descricao', 'codestoquemovimentotipo')->all();
        $options = EstoqueMovimentoTipo::all();
        return view('estoque-movimento.create', compact('tipos', 'request', 'options', 'em', 'el'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->converteDatas(['data' => $request->input('data')]);
        $this->converteNumericos([
            'entradaquantidade' => $request->input('entradaquantidade'),
            'saidaquantidade' => $request->input('saidaquantidade'),
            'entradavalor' => $request->input('entradavalor'),
            'saidavalor' => $request->input('saidavalor')
        ]);

        $model = new EstoqueMovimento($request->all());
        
        $em = EstoqueMes::buscaOuCria(
                $model->EstoqueMes->EstoqueSaldo->codproduto, 
                $model->EstoqueMes->EstoqueSaldo->codestoquelocal, 
                $model->EstoqueMes->EstoqueSaldo->fiscal, 
                $model->data);
        
        $model->codestoquemes = $em->codestoquemes;
        
        if (!$model->validate()) {
            $this->throwValidationException($request, $model->_validator);
        }

        //Cria registro de Origem
        if (!empty($model->EstoqueMovimentoTipo->codestoquemovimentotipoorigem))
        {
            $origem = new EstoqueMovimento();
            //$origem = $model->EstoqueMovimentoOrigem;

            $em_origem = EstoqueMes::buscaOuCria(
                    $request->input('codproduto'), 
                    $request->input('codestoquelocal'), 
                    $model->EstoqueMes->EstoqueSaldo->fiscal, 
                    $model->data);

            $origem->codestoquemes = $em_origem->codestoquemes;
            $origem->codestoquemovimentotipo = $model->EstoqueMovimentoTipo->codestoquemovimentotipoorigem;
            $origem->data = $model->data;
            $origem->entradaquantidade = $model->saidaquantidade;
            $origem->entradavalor = $model->saidavalor;
            $origem->saidaquantidade = $model->entradaquantidade;
            $origem->saidavalor = $model->entradavalor ; 
            $origem->manual = true;
            $origem->save();
            $origem->EstoqueMes->EstoqueSaldo->recalculaCustoMedio();
            $model->codestoquemovimentoorigem = $origem->codestoquemovimento;
        }   
        
        $model->manual = TRUE;
        $model->save();
        $model->EstoqueMes->EstoqueSaldo->recalculaCustoMedio();
        Session::flash('flash_create', 'Registro inserido.');
        return redirect("estoque-mes/$model->codestoquemes");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = EstoqueMovimento::find($id);
        return view('estoque-movimento.show', compact('model'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = EstoqueMovimento::findOrFail($id);
        $tipos = EstoqueMovimentoTipo::lists('descricao', 'codestoquemovimentotipo')->all();
        $options = EstoqueMovimentoTipo::all();
        $el = EstoqueLocal::lists('estoquelocal', 'codestoquelocal')->all();

        return view('estoque-movimento.edit',  compact('model', 'tipos', 'options', 'el'));        
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
        $this->converteDatas(['data' => $request->input('data')]);
        $this->converteNumericos([
            'entradaquantidade' => $request->input('entradaquantidade'),
            'saidaquantidade' => $request->input('saidaquantidade'),
            'entradavalor' => $request->input('entradavalor'),
            'saidavalor' => $request->input('saidavalor')
        ]);
        
        $model = EstoqueMovimento::findOrFail($id);
        $model->fill($request->all());
        if (!$model->validate()) {
            $this->throwValidationException($request, $model->_validator);
        }
        
        $em = EstoqueMes::buscaOuCria(
                $model->EstoqueMes->EstoqueSaldo->codproduto, 
                $model->EstoqueMes->EstoqueSaldo->codestoquelocal, 
                $model->EstoqueMes->EstoqueSaldo->fiscal, 
                $model->data);
        
        $model->codestoquemes = $em->codestoquemes;        
        
         //Cria registro de Origem
        if (!empty($model->EstoqueMovimentoTipo->codestoquemovimentotipoorigem))
        {
            $origem = $model->EstoqueMovimentoOrigem;

            $em_origem = EstoqueMes::buscaOuCria(
                    $request->input('codproduto'), 
                    $request->input('codestoquelocal'), 
                    $model->EstoqueMes->EstoqueSaldo->fiscal, 
                    $model->data);

            $origem->codestoquemes = $em_origem->codestoquemes;
            $origem->codestoquemovimentotipo = $model->EstoqueMovimentoTipo->codestoquemovimentotipoorigem;
            $origem->data = $model->data;
            $origem->entradaquantidade = $model->saidaquantidade;
            $origem->entradavalor = $model->saidavalor;
            $origem->saidaquantidade = $model->entradaquantidade;
            $origem->saidavalor = $model->entradavalor ; 
            $origem->manual = true;
            $origem->save();
            $origem->EstoqueMes->EstoqueSaldo->recalculaCustoMedio();
            $model->codestoquemovimentoorigem = $origem->codestoquemovimento;
        }         
        
        
        $model->save();
        $model->EstoqueMes->EstoqueSaldo->recalculaCustoMedio();
        Session::flash('flash_update', 'Registro atualizado.');
        return redirect("estoque-mes/$model->codestoquemes");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {

        try{
            $model = EstoqueMovimento::find($id);
            $model->delete();
            Session::flash('flash_delete', 'Registro deletado!');
            return redirect("estoque-mes/$model->codestoquemes");
        }
        catch(\Exception $e){
            return view('errors.fk');
        }        
    }
}
