@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
{!! 
    titulo(
        $produto->codproduto,
        [
            url("produto") => 'Produtos',
            url("produto/$produto->codproduto") => $produto->produto,
            'Novo Código de Barras',
        ],
        $model->inativo,
        6
    ) 
!!}     
</ol>
<br>
{!! Form::model($model, ['method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form-produto-barra', 'route' => ['produto-barra.store', 'codproduto' => $produto->codproduto]]) !!}
    @include('errors.form_error')
    @include('produto-barra.form', ['submitTextButton' => 'Salvar'])
 {!! Form::close() !!}   
@stop