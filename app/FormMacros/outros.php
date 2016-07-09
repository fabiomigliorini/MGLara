<?php

use Collective\Html\FormFacade;

/* TRIBUTAÇÃO */
Form::macro('date', function($name, $selected = null, $options = [])
{
    die('aqui');
    /*
    $tributacoes = [''=>''] + MGLara\Models\Tributacao::orderBy('tributacao')->lists('tributacao', 'codtributacao')->all();
    return Form::select2($name, $tributacoes, $selected, $options);
     * 
     */
});
