<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;
use MGLara\Http\Controllers\Controller;
use MGLara\Models\Negocio;

class NegociosController extends Controller
{
    public function index(Request $request)
    {
        $model = Negocio::orderBy('criacao', 'desc')->paginate(20);

        return view('negocios.index', compact('model'));
    }

    public function create(Request $request)
    {
        $filialCollection           = Filial::filiaisOrdenadoPorNome()->get();
        $estoqueLocalCollection     = EstoqueLocal::comFilialOrganizadoPorNomeDaFilial()->get();
        $naturezaOperacaoCollection = NaturezaOperacao::ordenadoPorNome()->get();
        $pessoaCollection           = Pessoa::ordenadoPorNome()->paginate(10);
        $vendedoresCollection       = Pessoa::vendedoresOrdenadoPorNome()->paginate(10);

        return view('negocios.create', compact(
            'filialCollection',
            'estoqueLocalCollection',
            'naturezaOperacaoCollection',
            'pessoaCollection',
            'vendedoresCollection'
        ));
    }

    public function store(Request $request)
    {

        $model = new Negocio($request->all());

        dd($model->validate());

        if (!$model->validate()) {
            $this->throwValidationException($request, $model->_validator);
        }

        $model->save();
        Session::flash('flash_create', 'Registro inserido.');

        return redirect(URL::route('negocios::index'));
    }
}
