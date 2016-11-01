@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
{!! 
    titulo(
        null,
        [
            url("secao-produto") => 'Seções de Produto',
            'Nova Seção',
        ],
        $model->inativo
    ) 
!!}   
</ol>
<hr>
<br>
{!! Form::model($model, ['method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form-secao-produto', 'route' => 'secao-produto.store']) !!}
    @include('errors.form_error')
    @include('secao-produto.form', ['submitTextButton' => 'Salvar'])
 {!! Form::close() !!}   
@stop