@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
{!! 
    titulo(
        null,
        [
            url("vale-compra") => 'Vale Compras',
            url("vale-compra/create") => $model->ValeCompraModelo->modelo,
            'Novo',
        ],
        $model->inativo
    ) 
!!}   
</ol>
<br>
{!! Form::model($model, ['method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form-vale-compra', 'route' => 'vale-compra.store']) !!}
    @include('errors.form_error')
    @include('vale-compra.form', ['submitTextButton' => 'Salvar'])
 {!! Form::close() !!}   
@stop