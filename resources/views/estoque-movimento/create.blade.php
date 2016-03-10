@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
        <!--<li><a href="<?php echo url('estoque-movimento');?>"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>-->
            <li><a href="<?php echo url('estoque-movimento/create');?>"><span class="glyphicon glyphicon-plus"></span> Novo</a></li>             
        </ul>
    </div>
</nav>
<h1 class="header">Estoque movimento manual</h1>
<hr>
<br>
{!! Form::open(['route'=>'estoque-movimento.store', 'method' => 'POST', 'class' => 'form-horizontal', 'onsubmit' => 'onSubmit()']) !!}
    @include('errors.form_error')
    @include('estoque-movimento.form', ['submitTextButton' => 'Salvar'])
 {!! Form::close() !!}   
@stop