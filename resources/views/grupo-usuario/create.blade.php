@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
	<div class="container-fluid"> 
		<ul class="nav navbar-nav">
			<li>
				<a href="<?php echo url('grupo-usuario');?>"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a>
			</li> 
		</ul>
	</div>
</nav>
<h1 class="header">
{!! 
    titulo(
        null,
        [
            url('produto') => 'Grupo de usuários',
            'Novo grupo'
        ],
        $model->inativo
    ) 
!!}     
</h1>
<hr>
{!! Form::open(['route'=>'grupo-usuario.store', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
    @include('errors.form_error')
    @include('grupo-usuario.form', ['submitTextButton' => 'Salvar'])
 {!! Form::close() !!}    
@stop