@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
{!! 
    titulo(
        $model->codsecaoproduto,
        [
            url("secao-produto/$model->codsecaoproduto") => $model->secaoproduto,
            'Alterar',
        ],
        $model->inativo
    ) 
!!}     
    <li class='active'>
        <small>
            <a title="Nova Seção" href="{{ url('secao-produto/create') }}"><i class="glyphicon glyphicon-plus"></i></a>
            &nbsp;
            <a title="Detalhes" href="{{ url("secao-produto/$model->codsecaoproduto") }}"><i class="glyphicon glyphicon-eye-open"></i></a>
        </small>
    </li>
</ol>
<hr>
<br>
{!! Form::model($model, ['method' => 'PATCH', 'class' => 'form-horizontal', 'id' => 'form-secao-produto', 'action' => ['SecaoProdutoController@update', $model->codsecaoproduto] ]) !!}
    @include('errors.form_error')
    @include('secao-produto.form', ['submitTextButton' => 'Salvar'])
{!! Form::close() !!}
@stop