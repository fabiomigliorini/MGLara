@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="{{ url("secao-produto/$request->codsecaoproduto") }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>
        </ul>
    </div>
</nav>
<h1 class="header">
{!! 
    titulo(
        null,
        [
            ['url' => "secao-produto/$parent->codsecaoproduto", 'descricao' => $parent->secaoproduto],
            ['id' => null, 'descricao' => "Nova Família"]
        ],
        null
    ) 
!!}      
</h1>
<hr>
<br>
{!! Form::model($model, ['method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form-familia-produto', 'route' => ['familia-produto.store', 'codsecaoproduto'=> $request->codsecaoproduto]]) !!}
    @include('errors.form_error')
    @include('familia-produto.form', ['submitTextButton' => 'Salvar'])
{!! Form::close() !!}   
@stop