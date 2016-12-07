@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
{!! 
    titulo(
        $model->codtipoproduto,
        [
            url("tipo-produto/$model->codtipoproduto") => $model->tipoproduto,
            'Alterar',
        ],
        $model->inativo
    ) 
!!}
    <li class='active'>
        <small>
            <a title="Novo Tipo" href="{{ url('tipo-produto/create') }}"><span class="glyphicon glyphicon-plus"></span></a>
            &nbsp;
            <a title="Detalhes" href="{{ url("tipo-produto/$model->codtipoproduto") }}"><span class="glyphicon glyphicon-eye-open"></span></a>
        </small>
    </li>   
</ol>
<hr>
<br>
{!! Form::model($model, ['method' => 'PATCH', 'class' => 'form-horizontal', 'id' => 'form-tipo-produto', 'action' => ['TipoProdutoController@update', $model->codtipoproduto] ]) !!}
    @include('errors.form_error')
    @include('tipo-produto.form', ['submitTextButton' => 'Salvar'])
{!! Form::close() !!}
@stop