@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
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
</ol>
<hr>
{!! Form::open(['route'=>'permissao.store', 'method' => 'POST', 'class' => 'form-horizontal', 'id'=>'form-permissao']) !!}
    @include('errors.form_error')
    @include('permissao.form', ['submitTextButton' => 'Salvar'])
 {!! Form::close() !!}    
@stop