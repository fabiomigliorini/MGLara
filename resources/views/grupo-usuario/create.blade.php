@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default" id="submenu">
	<div class="container-fluid"> 
		<ul class="nav navbar-nav">
			<li>
				<a href="<?php echo url('grupo-usuario');?>"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a>
			</li> 
		</ul>
	</div>
</nav>
<ol class="breadcrumb header">
{!! 
    titulo(
        null,
        [
            url('grupo-usuario') => 'Grupo de usuários',
            'Novo grupo'
        ],
        $model->inativo
    ) 
!!}     
</ol>
<hr>
{!! Form::open(['route'=>'grupo-usuario.store', 'method' => 'POST', 'class' => 'form-horizontal', 'id'=>'form-grupo-usuario']) !!}
    @include('errors.form_error')
    @include('grupo-usuario.form', ['submitTextButton' => 'Salvar'])
 {!! Form::close() !!}    
@stop