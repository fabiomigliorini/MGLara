@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li>
                <a href="<?php echo url('usuario');?>"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a>
            </li> 
        </ul>
    </div>
</nav>
<ol class="breadcrumb header">
{!! 
    titulo(
        null,
        [
            url('usuario') => 'Usuários',
            'Novo usuário'
        ],
        $model->inativo
    ) 
!!} 
</ol>
<hr>
{!! Form::open(['route'=>'usuario.store', 'method' => 'POST', 'class' => 'form-horizontal', 'id'=>'form-usuario']) !!}
    @include('errors.form_error')
    @include('usuario.form', ['submitTextButton' => 'Salvar'])
 {!! Form::close() !!}    
@stop