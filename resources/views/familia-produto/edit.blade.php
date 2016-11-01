@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
{!! 
    titulo(
        $model->codfamiliaproduto,
        [
            url("secao-produto/$model->codsecaoproduto") => $model->SecaoProduto->secaoproduto,
            url("familia-produto/$model->codfamiliaproduto") => $model->familiaproduto,
            'Alterar'
        ],
        $model->inativo
    ) 
!!} 
    <li class='active'>
        <small>
            <a title="Nova Família" href="{{ url("familia-produto/create?codsecaoproduto=$model->codsecaoproduto") }}"><i class="glyphicon glyphicon-plus"></i></a>
            &nbsp;
            <a title="Detalhes" href="{{ url("familia-produto/$model->codfamiliaproduto") }}"><i class="glyphicon glyphicon-eye-open"></i></a>
        </small>
    </li>
</ol>
<hr>
<br>
{!! Form::model($model, ['method' => 'PATCH', 'class' => 'form-horizontal', 'id' => 'form-familia-produto', 'action' => ['FamiliaProdutoController@update', $model->codfamiliaproduto] ]) !!}
    @include('errors.form_error')
    @include('familia-produto.form', ['submitTextButton' => 'Salvar'])
{!! Form::close() !!}
@stop