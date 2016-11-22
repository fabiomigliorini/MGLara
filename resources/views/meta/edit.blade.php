@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
{!! 
    titulo(
        $model->codmarca,
        [
            url("marca/$model->codmarca") => $model->marca,
            'Alterar',
        ],
        $model->inativo
    ) 
!!}
    <li class='active'>
        <small>
            <a title="Novo" href="<?php echo url('marca/create');?>"><i class="glyphicon glyphicon-plus"></i></a>
            &nbsp;
            <a title="Detalhes" href="<?php echo url("marca/$model->codmarca");?>"><i class="glyphicon glyphicon-eye-open"></i></a>
        </small>
    </li>   
</ol>
<hr>
<br>
{!! Form::model($model, ['method' => 'PATCH', 'class' => 'form-horizontal', 'id' => 'form-marca', 'action' => ['MarcaController@update', $model->codmarca] ]) !!}
    @include('errors.form_error')
    @include('marca.form', ['submitTextButton' => 'Salvar'])
{!! Form::close() !!}
@stop