@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
{!! 
    titulo(
        null,
        [
            url("unidade-medida") => 'Unidades de Medida',
            'Nova unidade de medida',
        ],
        $model->inativo
    ) 
!!}       
</ol>
<hr>
<br>
{!! Form::model($model, ['method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form-unidade-medida', 'route' => 'unidade-medida.store']) !!}
    @include('errors.form_error')
    @include('unidade-medida.form', ['submitTextButton' => 'Salvar'])
 {!! Form::close() !!}   
@stop