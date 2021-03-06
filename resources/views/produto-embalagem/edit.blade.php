@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
{!! 
    titulo(
        $model->Produto->codproduto,
        [
            url("produto") => 'Produtos',
            url("produto/$produto->codproduto") => $model->Produto->produto,
            $model->descricao,
            'Alterar',
        ],
        $model->inativo,
        6
    ) 
!!}     
</ol>
<br>
{!! Form::model($model, ['method' => 'PATCH', 'class' => 'form-horizontal', 'id' => 'form-produto-embalagem', 'action' => ['ProdutoEmbalagemController@update', $model->codprodutoembalagem] ]) !!}
    @include('errors.form_error')
    @include('produto-embalagem.form', ['submitTextButton' => 'Salvar'])
{!! Form::close() !!}
@stop