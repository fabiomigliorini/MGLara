@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="<?php echo url('ncm');?>"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>             
            <li><a href="<?php echo url('ncm/create');?>"><span class="glyphicon glyphicon-plus"></span> Nova</a></li>             
            <li><a href="<?php echo url("ncm/$model->codncm");?>"><span class="glyphicon glyphicon-eye-open"></span> Detalhes</a></li>  
        </ul>
    </div>
</nav>
<h1 class="header">
{!! 
    titulo(
        $model->codncm,
        [
            url("ncm/$model->codncm") => $model->descricao,
            'Alterar',
        ],
        $model->inativo
    ) 
!!}  
</h1>
<hr>
<br>
{!! Form::model($model, ['method' => 'PATCH', 'class' => 'form-horizontal', 'id' => 'form-ncm', 'action' => ['NcmController@update', $model->codncm] ]) !!}
    @include('errors.form_error')
    @include('ncm.form', ['submitTextButton' => 'Salvar'])
{!! Form::close() !!}
@stop