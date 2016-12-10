@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
    {!! 
        titulo(
            $model->codvalecompra,
            [
                url("vale-compra") => 'Vale Compras',
                url("vale-compra", $model->codvalecompra) => $model->modelo,
                'Editar'
            ],
            $model->inativo
        ) 
    !!}    
</ol>
<br>
{!! Form::model($model, ['method' => 'PATCH', 'class' => 'form-horizontal', 'id' => 'form-vale-compra', 'action' => ['ValeCompraController@update', $model->codvalecompra] ]) !!}
    @include('errors.form_error')
    @include('vale-compra.form', ['submitTextButton' => 'Salvar'])
{!! Form::close() !!}
@stop