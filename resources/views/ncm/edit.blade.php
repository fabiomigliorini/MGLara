@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
{!! 
    titulo(
        $model->codncm,
        [
            url("ncm/$model->codncm") => $model->descricao,
            'Alterar',
        ],
        $model->inativo
    ) 
!!}
    <li class='active'>
        <small>
            <a title="Novo" href="{{ url('ncm/create') }}"><i class="glyphicon glyphicon-plus"></i></a>
            &nbsp;
            <a title="Detalhes" href="{{ url("ncm/$model->codncm") }}"><i class="glyphicon glyphicon-eye-open"></i></a>
        </small>
    </li>   

</ol>
<hr>
<br>
{!! Form::model($model, ['method' => 'PATCH', 'class' => 'form-horizontal', 'id' => 'form-ncm', 'action' => ['NcmController@update', $model->codncm] ]) !!}
    @include('errors.form_error')
    @include('ncm.form', ['submitTextButton' => 'Salvar'])
{!! Form::close() !!}
@stop