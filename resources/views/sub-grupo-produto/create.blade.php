@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="<?php echo url("grupo-produto/$request->codgrupoproduto");?>"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>
        </ul>
    </div>
</nav>
<h1 class="header">Novo Sub Grupo Produto</h1>
<hr>
<br>
{!! Form::model($model, ['method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form-sub-grupo-produto', 'route' => ['sub-grupo-produto.store', 'codgrupoproduto' => $request->codgrupoproduto] ]) !!}
    @include('errors.form_error')
    @include('sub-grupo-produto.form', ['submitTextButton' => 'Salvar'])
 {!! Form::close() !!}   
@stop