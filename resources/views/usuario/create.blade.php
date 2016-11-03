@extends('layouts.default')
@section('content')
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