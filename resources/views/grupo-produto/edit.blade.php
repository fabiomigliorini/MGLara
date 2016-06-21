@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="{{ url("familia-produto/{$model->FamiliaProduto->codfamiliaproduto}") }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>             
            <li><a href="{{ url("grupo-produto/create?codfamiliaproduto={$model->FamiliaProduto->codfamiliaproduto}") }}><span class="glyphicon glyphicon-plus"></span> Novo</a></li>             
            <li><a href="{{ url("grupo-produto/$model->codgrupoproduto") }}"><span class="glyphicon glyphicon-eye-open"></span> Detalhes</a></li>  
        </ul>
    </div>
</nav>
<h1 class="header">
{!! 
    titulo(
        $model->codgrupoproduto,
        [
            ['url' => "secao-produto/{$model->FamiliaProduto->SecaoProduto->codsecaoproduto}", 'descricao' => $model->FamiliaProduto->SecaoProduto->secaoproduto],
            ['url' => "familia-produto/{$model->FamiliaProduto->codfamiliaproduto}", 'descricao' => $model->FamiliaProduto->familiaproduto],
            ['url' => "grupo-produto/$model->codgrupoproduto", 'descricao' => $model->grupoproduto],
            ['url' => null, 'descricao' => 'Alterar']
        ],
        $model->inativo
    ) 
!!}  
</h1>
<hr>
<br>
{!! Form::model($model, ['method' => 'PATCH', 'class' => 'form-horizontal', 'id' => 'form-grupo-produto', 'action' => ['GrupoProdutoController@update', $model->codgrupoproduto] ]) !!}
    @include('errors.form_error')
    @include('grupo-produto.form', ['submitTextButton' => 'Salvar'])
{!! Form::close() !!}
@stop