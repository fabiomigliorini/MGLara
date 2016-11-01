@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
{!! 
    titulo(
        null,
        [
            url("tipo-produto") => 'Tipos de Produto',
            'Novo Tipo de Produto',
        ],
        $model->inativo
    ) 
!!}   
</ol>
<hr>
<br>
{!! Form::model($model, ['method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form-tipo-produto', 'route' => 'tipo-produto.store']) !!}
    @include('errors.form_error')
    @include('tipo-produto.form', ['submitTextButton' => 'Salvar'])
 {!! Form::close() !!}   
@stop