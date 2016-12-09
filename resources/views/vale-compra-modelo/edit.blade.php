@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
    {!! 
        titulo(
            $model->codvalecompramodelo,
            [
                url("vale-compra-modelo") => 'Modelos de Vale Compras',
                url("vale-compra-modelo", $model->codvalecompramodelo) => $model->modelo,
                'Editar'
            ],
            $model->inativo
        ) 
    !!}    
</ol>
<br>
{!! Form::model($model, ['method' => 'PATCH', 'class' => 'form-horizontal', 'id' => 'form-vale-compra-modelo', 'action' => ['ValeCompraModeloController@update', $model->codvalecompramodelo] ]) !!}
    @include('errors.form_error')
    @include('vale-compra-modelo.form', ['submitTextButton' => 'Salvar'])
{!! Form::close() !!}
@stop