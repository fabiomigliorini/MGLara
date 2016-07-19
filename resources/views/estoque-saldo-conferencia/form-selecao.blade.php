<?php

use MGLara\Models\EstoqueLocal;

$locais = [''=>''] + EstoqueLocal::lists('estoquelocal', 'codestoquelocal')->all();
$disabled = (!empty($model->codestoquesaldo))?'disabled':'';

$fiscal_fmt = (!empty($fiscal) ? 'Fiscal' : 'Físico');

?>
<div class='form-group' style='position:relative'>
    {!! Form::datetimeLocal('data', $data, ['class'=> 'form-control text-center', 'id'=>'data', 'required'=>'required', $disabled=>$disabled, 'placeholder'=>'Data Ajuste']) !!}
</div>

<div class='form-group' style='position:relative'>
    {!! Form::select2EstoqueLocal('codestoquelocal', $codestoquelocal, ['class'=> 'form-control', 'required'=>'required', 'id'=>'codestoquelocal', 'style'=>'width:150px', $disabled=>$disabled]) !!}
</div>

<div class='form-group' style='position:relative'>
    @if (empty($model->codestoquesaldo))
        {!! Form::checkbox('fiscal', NULL, null, ['id'=>'fiscal', 'data-off-text' => 'Físico', 'data-on-text' => 'Fiscal', $disabled=>$disabled]) !!}
    @else
        {!! Form::text('fiscal', $fiscal_fmt, ['class'=> 'form-control text-center', 'id'=>'data', 'required'=>'required', $disabled=>$disabled]) !!}
    @endif
</div>

<div class='form-group' style='position:relative'>
    {!! Form::text('barras', $barras, ['class'=> 'form-control text-right', 'required'=>'required', 'id'=>'barras', $disabled=>$disabled, 'placeholder'=>'Código de Barras']) !!}
</div>

<div class='form-group' style='position:relative'>
    {!! Form::submit('Buscar', array('class' => 'btn btn-primary', 'id'=>'Submit', $disabled=>$disabled)) !!}
</div>
