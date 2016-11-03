@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
{!! 
    titulo(
        $model->codgrupousuario,
        [
            url("grupo-usuario/$model->codgrupousuario") => $model->grupousuario,
            'Alterar',
        ],
        $model->inativo
    ) 
!!}  
    <li class='active'>
        <small>
            <a title="Novo Grupo" href="{{ url('grupo-usuario/create') }}"><i class="glyphicon glyphicon-plus"></i></a>
            &nbsp;
            <a title="Detalhes" href="{{ url("grupo-usuario/$model->codgrupousuario") }}"><i class="glyphicon glyphicon-eye-open"></i></a>
        </small>
    </li>   

</ol>
<hr>
<br>
{!! Form::model($model, ['method' => 'PATCH', 'id'=>'form-grupo-usuario', 'class' => 'form-horizontal', 'action' => ['GrupoUsuarioController@update', $model->codgrupousuario] ]) !!}
    @include('errors.form_error')
    @include('grupo-usuario.form', ['submitTextButton' => 'Salvar'])
{!! Form::close() !!}
@stop