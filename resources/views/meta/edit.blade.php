@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
{!! 
    titulo(
        $model->codproduto,
        [
            url("meta") => 'Metas',
            url("meta/$model->codmeta") => formataData($model->periodofinal, 'EC'),
            'Alterar',
        ],
        null
    ) 
!!}     
</ol>
{!! Form::model($model, [
    'method' => 'PATCH', 
    'class' => 'form-horizontal', 
    'id' => 'form-meta', 
    'action' => ['MetaController@update', $model->codmeta] 
]) !!}
    @include('errors.form_error')
    @include('meta.form', ['submitTextButton' => 'Salvar'])
{!! Form::close() !!}
@stop