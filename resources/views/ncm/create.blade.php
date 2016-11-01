@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
{!! 
    titulo(
        null,
        [
            url("ncm") => 'NCM',
            'Nova NCM',
        ],
        $model->inativo
    ) 
!!}
</ol>
<hr>
<br>
{!! Form::model($model, ['method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form-ncm', 'route' => 'ncm.store']) !!}
    @include('errors.form_error')
    @include('ncm.form', ['submitTextButton' => 'Salvar'])
 {!! Form::close() !!}   
@stop