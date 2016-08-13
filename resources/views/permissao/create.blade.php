@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
	<div class="container-fluid"> 
		<ul class="nav navbar-nav">
			<li>
				<a href="<?php echo url('permissao');?>"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a>
			</li> 
		</ul>
	</div>
</nav>
<h1 class="header">
{!! 
    titulo(
        null,
        [
            url("permissao") => 'Permissões',
            'Nova Permissão',
        ],
        $model->inativo
    ) 
!!}   
</h1>
<hr>
{!! Form::open(['route'=>'permissao.store', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
    @include('errors.form_error')
    @include('permissao.form', ['submitTextButton' => 'Salvar'])
 {!! Form::close() !!}    
@stop