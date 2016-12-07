@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
{!! 
    titulo(
        null,
        [
            url("vale-compra-modelo") => 'Modelos de Vale Compras',
            'Novo',
        ],
        $model->inativo
    ) 
!!}   
</ol>
<br>
{!! Form::model($model, ['method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form-vale-compra-modelo', 'route' => 'vale-compra-modelo.store']) !!}
    @include('errors.form_error')
    @include('vale-compra-modelo.form', ['submitTextButton' => 'Salvar'])
 {!! Form::close() !!}   
@stop