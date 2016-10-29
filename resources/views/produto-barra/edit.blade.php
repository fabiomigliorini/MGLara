@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
{!! 
    titulo(
        $model->Produto->codproduto,
        [
            url("produto") => 'Produtos',
            url("produto/$produto->codproduto") => $model->Produto->produto,
            $model->barras,
            'Alterar',
        ],
        $model->inativo,
        6
    ) 
!!}     
</ol>
<br>
{!! Form::model($model, ['method' => 'PATCH', 'class' => 'form-horizontal', 'id' => 'form-produto-barra', 'action' => ['ProdutoBarraController@update', $model->codprodutobarra] ]) !!}
    @include('errors.form_error')
    @include('produto-barra.form', ['submitTextButton' => 'Salvar'])
{!! Form::close() !!}
@stop