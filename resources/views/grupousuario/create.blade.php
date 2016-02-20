@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
	<div class="container-fluid"> 
		<ul class="nav navbar-nav">
			<li>
				<a href="<?php echo url('grupousuario');?>"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a>
			</li> 
		</ul>
	</div>
</nav>
<h1 class="header">Grupo de usuário</h1>
<hr>
{!! Form::open(['route'=>'grupousuario.store', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
    @include('errors.form_error')
    @include('grupousuario.form', ['submitTextButton' => 'Salvar'])
 {!! Form::close() !!}    
@stop