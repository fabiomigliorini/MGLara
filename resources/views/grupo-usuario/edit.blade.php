@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="<?php echo url('grupo-usuario');?>"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>             
            <li><a href="<?php echo url('grupo-usuario/create');?>"><span class="glyphicon glyphicon-plus"></span> Novo</a></li>             
            <li><a href="<?php echo url("grupo-usuario/$model->codgrupousuario");?>"><span class="glyphicon glyphicon-eye-open"></span> Detalhes</a></li> 
        </ul>
    </div>
</nav>
<ol class="breadcrumb header">
{!! 
    titulo(
        $model->codgrupousuario,
        [
            url("grupo-usuario/$model->codgrupousuario") => $model->grupousuario,
            'Alterar',
        ],
        $model->inativo
    ) 
!!}  
</ol>
<hr>
<br>
{!! Form::model($model, ['method' => 'PATCH', 'id'=>'form-grupo-usuario', 'class' => 'form-horizontal', 'action' => ['GrupoUsuarioController@update', $model->codgrupousuario] ]) !!}
    @include('errors.form_error')
    @include('grupo-usuario.form', ['submitTextButton' => 'Salvar'])
{!! Form::close() !!}
@stop