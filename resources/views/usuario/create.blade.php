@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
	<div class="container-fluid"> 
		<ul class="nav navbar-nav">
			<li>
				<a href="<?php echo url('usuario');?>"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a>
			</li> 
		</ul>
	</div>
</nav>
<h1 class="header">Usuário</h1>
<hr>
{!! Form::open(['route'=>'usuario.store', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
    @include('errors.form_error')
    @include('usuario.form', ['submitTextButton' => 'Salvar'])
 {!! Form::close() !!}    
@stop