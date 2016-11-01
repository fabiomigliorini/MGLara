@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
    {!! 
        titulo(
            $model->codunidademedida,
            [
                url("unidade-medida/$model->codunidademedida") => $model->unidademedida,
                'Alterar',
            ],
            $model->inativo
        ) 
    !!}
    <li class='active'>
        <small>
            <a title="Nova Unidade de Medida" href="{{ url('unidade-medida/create') }}"><span class="glyphicon glyphicon-plus"></span></a>
            &nbsp;
            <a title="Detalhes" href="{{ url("unidade-medida/$model->codunidademedida") }}"><span class="glyphicon glyphicon-eye-open"></span></a>
        </small>
    </li>   

</ol>
<hr>
<br>
{!! Form::model($model, ['method' => 'PATCH', 'class' => 'form-horizontal', 'id' => 'form-unidade-medida', 'action' => ['UnidadeMedidaController@update', $model->codunidademedida] ]) !!}
    @include('errors.form_error')
    @include('unidade-medida.form', ['submitTextButton' => 'Salvar'])
{!! Form::close() !!}
@stop