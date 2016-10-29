@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default" id="submenu">
	<div class="container-fluid"> 
		<ul class="nav navbar-nav">
			<li>
				<a href="<?php echo url('permissao');?>"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a>
			</li> 
		</ul>
	</div>
</nav>
<ol class="breadcrumb header">Permissão</ol>
<hr>
{!! Form::open(['route'=>'permissao.store', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
    @include('errors.form_error')
    @include('permissao.form', ['submitTextButton' => 'Salvar'])
 {!! Form::close() !!}    
@stop