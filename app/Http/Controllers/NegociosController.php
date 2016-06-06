<?php

namespace MGLara\Http\Controllers;

use MGLara\Http\Controllers\Controller;

class NegociosController extends Controller
{
    public function index()
    {
        return view('negocios.index');
    }
}
