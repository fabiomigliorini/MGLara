@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="{{ url("produto/{$model->Produto->codproduto}") }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>
        </ul>
    </div>
</nav>
<h1 class="header">
{!! 
    titulo(
        $model->codprodutoembalagem,
        [
            url("produto") => 'Produtos',
            url("produto/$produto->codproduto") => $model->Produto->produto,
            $model->descricao,
            'Alterar',
        ],
        $model->inativo
    ) 
!!}     
</h1>
<hr>
<br>
{!! Form::model($model, ['method' => 'PATCH', 'class' => 'form-horizontal', 'id' => 'form-produto-embalagem', 'action' => ['ProdutoEmbalagemController@update', $model->codprodutoembalagem] ]) !!}
    @include('errors.form_error')
    @include('produto-embalagem.form', ['submitTextButton' => 'Salvar'])
{!! Form::close() !!}
@stop