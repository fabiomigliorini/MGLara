@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="{{ url("produto/$model->codproduto") }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>
            <li><a href="{{ url("produto-variacao/create?codproduto=$model->codproduto") }}"><span class="glyphicon glyphicon-plus"></span> Nova</a></li>
        </ul>
    </div>
</nav>
<h1 class="header">
{!! 
    titulo(
        $model->codprodutovariacao,
        [
            url("produto") => 'Produtos',
            url("produto/$produto->codproduto") => $model->Produto->produto,
            empty($model->variacao)?'{Sem Variação}':$model->variacao,
            'Alterar',
        ],
        $model->inativo
    ) 
!!}     
</h1>
<hr>
<br>
{!! Form::model($model, ['method' => 'PATCH', 'class' => 'form-horizontal', 'id' => 'form-produto-variacao', 'action' => ['ProdutoVariacaoController@update', $model->codprodutovariacao] ]) !!}
    @include('errors.form_error')
    @include('produto-variacao.form', ['submitTextButton' => 'Salvar'])
{!! Form::close() !!}
@stop