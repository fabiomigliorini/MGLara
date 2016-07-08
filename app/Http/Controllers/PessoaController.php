<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use MGLara\Http\Requests;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Http\Controllers\Controller;
use MGLara\Models\Pessoa;
use MGLara\Models\CobrancaHistorico;
use MGLara\Models\RegistroSpc;

class PessoaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $model = Pessoa::filterAndPaginate(
            $request->get('id'), 
            $request->get('pessoa'), 
            $request->get('cnpj'), 
            $request->get('email'), 
            $request->get('telefone'), 
            $request->get('inativo'), 
            $request->get('cidade'), 
            $request->get('grupocliente')
        );
        
        return view('pessoa.index', compact('model'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new Pessoa();
        return view('pessoa.create', compact('model'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model = new Pessoa($request->all());
        
        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);
        
        $model->save();
        Session::flash('flash_create', 'Registro inserido.');
        return redirect("pessoa/$model->codpessoa");    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $model = Pessoa::find($id);
        $cobrancas = CobrancaHistorico::byPessoa($id)->paginate(10);
        $spcs = RegistroSpc::byPessoa($id)->paginate(10);
        return view('pessoa.show', compact('model', 'estados', 'cobrancas', 'spcs'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = Pessoa::findOrFail($id);
        return view('pessoa.edit',  compact('model'));
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
        $model = Pessoa::findOrFail($id);
        $model->fill($request->all());

        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);

        if ($request->input('cliente') == 1) {
            $model->cliente = TRUE;
        } else {
            $model->cliente = FALSE;
        }        

        if ($request->input('fornecedor') == 1) {
            $model->fornecedor = TRUE;
        } else {
            $model->fornecedor = FALSE;
        }        

        if ($request->input('fisica') == 1) {
            $model->fisica = TRUE;
        } else {
            $model->fisica = FALSE;
        }        

        if ($request->input('consumidor') == 1) {
            $model->consumidor = TRUE;
        } else {
            $model->consumidor = FALSE;
        }        

        if ($request->input('creditobloqueado') == 1) {
            $model->creditobloqueado = TRUE;
        } else {
            $model->creditobloqueado = FALSE;
        }        

        if ($request->input('vendedor') == 1) {
            $model->vendedor = TRUE;
        } else {
            $model->vendedor = FALSE;
        }        
        
        $model->save();
        
        Session::flash('flash_update', 'Registro atualizado.');
        return redirect("pessoa/$model->codpessoa"); 
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
            Pessoa::find($id)->delete();
            Session::flash('flash_delete', 'Registro deletado!');
            return Redirect::route('pessoa.index');
        }
        catch(\Exception $e){
            return view('errors.fk');
        }     
    }

    public function listagemJson(Request $request)
    {
        if($request->get('q')) {
            $query = Pessoa::pessoa($request->get('q'))
                    ->select('codpessoa as id', 'pessoa', 'fantasia', 'cnpj', 'inativo')
                    ->paginate(10);
                    //->take(10)->get();
            return response()->json($query/*['items' => $query]*/);
            
        } elseif($request->get('id')) {
            $query = Pessoa::find($request->get('id'));
            return response()->json($query);
        }
    } 
}
