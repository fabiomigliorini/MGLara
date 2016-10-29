@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="<?php echo url('marca');?>"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>             
            <li><a href="<?php echo url('marca/create');?>"><span class="glyphicon glyphicon-plus"></span> Novo</a></li>             
            <li><a href="<?php echo url("marca/$model->codmarca");?>"><span class="glyphicon glyphicon-eye-open"></span> Detalhes</a></li>  
        </ul>
    </div>
</nav>
<ol class="breadcrumb header">
{!! 
    titulo(
        $model->codmarca,
        [
            url("marca/$model->codmarca") => $model->marca,
            'Alterar',
        ],
        $model->inativo
    ) 
!!}  
</ol>
<hr>
<br>
{!! Form::model($model, ['method' => 'PATCH', 'class' => 'form-horizontal', 'id' => 'form-marca', 'action' => ['MarcaController@update', $model->codmarca] ]) !!}
    @include('errors.form_error')
    @include('marca.form', ['submitTextButton' => 'Salvar'])
{!! Form::close() !!}
@stop