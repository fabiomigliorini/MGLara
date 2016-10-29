@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
{!! 
    titulo(
        null,
        [
            url('produto') => 'Produtos',
            'Novo Produto'
        ],
        $model->inativo
    ) 
!!}   
</ol>
{!! Form::model($model, ['method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form-produto', 'route' => 'produto.store', 'autocomplete'=>'off']) !!}
    @include('errors.form_error')
    @include('produto.form', ['submitTextButton' => 'Salvar'])
 {!! Form::close() !!}   
@stop