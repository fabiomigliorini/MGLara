@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
{!! 
    titulo(
        $model->codpermissao,
        [
            url("permissao/$model->codpermissao") => $model->observacoes,
            'Alterar',
        ],
        $model->inativo
    ) 
!!}
    <li class='active'>
        <small>
            <a title="Novo" href="{{ url('permissao/create') }}"><i class="glyphicon glyphicon-plus"></i></a>
            &nbsp;
            <a title="Detalhes" href="{{ url("permissao/$model->codpermissao") }}"><i class="glyphicon glyphicon-eye-open"></i></a>
        </small>
    </li>   
</ol>
<hr>
<br>
{!! Form::model($model, ['method' => 'PATCH', 'class' => 'form-horizontal', 'id'=>'form-permissao', 'action' => ['PermissaoController@update', $model->codpermissao] ]) !!}
    @include('errors.form_error')
    @include('permissao.form', ['submitTextButton' => 'Salvar'])
{!! Form::close() !!}
@stop