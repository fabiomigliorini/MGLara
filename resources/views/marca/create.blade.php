@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
{!! 
    titulo(
        null,
        [
            url("marca") => 'Marcas',
            'Nova Marca',
        ],
        $model->inativo
    ) 
!!}
</ol>
<hr>
<br>
{!! Form::model($model, ['method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form-marca', 'route' => 'marca.store']) !!}
    @include('errors.form_error')
    @include('marca.form', ['submitTextButton' => 'Salvar'])
 {!! Form::close() !!}   
@stop