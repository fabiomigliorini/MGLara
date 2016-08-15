<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use MGLara\Http\Controllers\Controller;
use MGLara\Models\Negocio;
use MGLara\Models\NegocioStatus;
use MGLara\Models\Pessoa;

class NegocioController extends Controller
{
    public function index(Request $request)
    {
        return redirect()->away("/MGsis/index.php?r=negocio/index");
        $model = Negocio::orderBy('criacao', 'desc')->paginate(20);

        return view('negocios.index', compact('model'));
    }

    public function view($id)
    {
        $model = Negocio::find($id);

        return view('negocios.view', compact('model'));
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

        $user = Auth::user();

        $model = new Negocio($request->all());
        $model->setAttribute('lancamento', new \DateTime());
        $model->setAttribute('codusuario', $user->codusuario);
        $model->setAttribute('codnegociostatus', NegocioStatus::ABERTO);
        // $model->setAttribute('codfilial', $user->codfilial);
        // $model->codnaturezaoperacao = NaturezaOperacao::VENDA;
        // $model->setAttribute('codnaturezaoperacao', NaturezaOperacao::VENDA);
        // $model->setAttribute('codpessoa, Pessoa::CONSUMIDOR);

        if ($model->getAttribute('codnaturezaoperacao')) {
            $naturezaOperacao = NaturezaOperacao::find($model->getAttribute('codnaturezaoperacao'));
            $model->setAttribute('codoperacao', $naturezaOperacao->codoperacao);
        }

        if (!$model->validate()) {
            $this->throwValidationException($request, $model->_validator);
        }

        $model->save();

        return redirect()->route('negocios::view', [$model])->with('status', 'Registro inserido.');
    }
    
    public function show(Request $request, $id) 
    {
        return redirect()->away("/MGsis/index.php?r=negocio/view&id=$id");
    }
}
