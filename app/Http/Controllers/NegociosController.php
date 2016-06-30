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
        $model = Negocio::orderBy('criacao', 'desc')->paginate(200);

        return view('negocios.create', compact('model'));
    }
}
