@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
{!! 
    titulo(
        null,
        [
            url('grupo-usuario') => 'Grupos de Usuários',
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