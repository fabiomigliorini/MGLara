@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
{!! 
    titulo(
        $model->codusuario,
        [
            url("usuario") => 'Usuários',
            url("usuario/$model->codusuario") => $model->usuario,
            'Alterar',
        ],
        $model->inativo
    ) 
!!} 
    <li class='active'>
        <small>
            <a title="Novo" href="{{ url('usuario/create') }}"><i class="glyphicon glyphicon-plus"></i></a>
            &nbsp;
            <a title="Detalhes" href="{{ url("usuario/$model->codusuario") }}"><i class="glyphicon glyphicon-eye-open"></i></a>
        </small>
    </li>   
</ol>
<hr>
<br>
{!! Form::model($model, ['method' => 'PATCH', 'class' => 'form-horizontal', 'id'=>'form-usuario', 'action' => ['UsuarioController@update', $model->codusuario] ]) !!}
    @include('errors.form_error')
    @include('usuario.form', ['submitTextButton' => 'Salvar'])
{!! Form::close() !!}
@stop