@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
{!! 
    titulo(
        $model->Produto->codproduto,
        [
            url("produto") => 'Produtos',
            url("produto/$produto->codproduto") => $model->Produto->produto,
            empty($model->variacao)?'{Sem Variação}':$model->variacao,
            'Alterar',
        ],
        $model->inativo,
        6
    ) 
!!}     
</ol>
<br>
{!! Form::model($model, ['method' => 'PATCH', 'class' => 'form-horizontal', 'id' => 'form-produto-variacao', 'action' => ['ProdutoVariacaoController@update', $model->codprodutovariacao] ]) !!}
    @include('errors.form_error')
    @include('produto-variacao.form', ['submitTextButton' => 'Salvar'])
{!! Form::close() !!}
@stop