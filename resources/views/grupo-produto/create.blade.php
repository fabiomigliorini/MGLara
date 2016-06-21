@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="{{ url("familia-produto/$parent->codfamiliaproduto") }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>
        </ul>
    </div>
</nav>
<h1 class="header">
{!! 
    titulo(
        $parent->SecaoProduto->codsecaoproduto,
        [
            ['url' => "secao-produto/{$parent->SecaoProduto->codsecaoproduto}", 'descricao' => $parent->SecaoProduto->secaoproduto],
            ['url' => "familia-produto/$parent->codfamiliaproduto", 'descricao' => $parent->familiaproduto],
            ['id' => null, 'descricao' => 'Novo Grupo Produto']
        ],
        null
    ) 
!!} 
</h1>
<hr>
<br>
{!! Form::model($model, ['method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form-grupo-produto', 'route' => 'grupo-produto.store']) !!}
    @include('errors.form_error')
    @include('grupo-produto.form', ['submitTextButton' => 'Salvar'])
 {!! Form::close() !!}   
@stop