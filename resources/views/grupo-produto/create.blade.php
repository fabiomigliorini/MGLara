@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
{!! 
    titulo(
        $parent->SecaoProduto->codsecaoproduto,
        [
            url("secao-produto/{$parent->SecaoProduto->codsecaoproduto}") => $parent->SecaoProduto->secaoproduto,
            url("familia-produto/$parent->codfamiliaproduto") => $parent->familiaproduto,
            'Novo Grupo Produto'
        ],
        null
    ) 
!!} 
</ol>
<hr>
<br>
{!! Form::model($model, ['method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form-grupo-produto', 'route' => ['grupo-produto.store', 'codfamiliaproduto'=> $parent->codfamiliaproduto ]]) !!}
    @include('errors.form_error')
    @include('grupo-produto.form', ['submitTextButton' => 'Salvar'])
 {!! Form::close() !!}   
@stop