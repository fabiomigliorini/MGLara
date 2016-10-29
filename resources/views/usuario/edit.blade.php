@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="<?php echo url('usuario');?>"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>             
            <li><a href="<?php echo url('usuario/create');?>"><span class="glyphicon glyphicon-plus"></span> Novo</a></li>             
            <li><a href="<?php echo url("usuario/$model->codusuario");?>"><span class="glyphicon glyphicon-eye-open"></span> Detalhes</a></li> 
        </ul>
    </div>
</nav>
<ol class="breadcrumb header">
{!! 
    titulo(
        $model->codusuario,
        [
            url("usuario") => 'Usuários',
            url("usuario/$model->codusuario") => $model->usuario,
            'Alterar',
        ],
        $model->inativo
    ) 
!!} 
</ol>
<hr>
<br>
{!! Form::model($model, ['method' => 'PATCH', 'class' => 'form-horizontal', 'id'=>'form-usuario', 'action' => ['UsuarioController@update', $model->codusuario] ]) !!}
    @include('errors.form_error')
    @include('usuario.form', ['submitTextButton' => 'Salvar'])
{!! Form::close() !!}
@stop