@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
{!! 
    titulo(
        $model->codgrupoproduto,
        [
            url("secao-produto/{$model->FamiliaProduto->SecaoProduto->codsecaoproduto}") => $model->FamiliaProduto->SecaoProduto->secaoproduto,
            url("familia-produto/{$model->FamiliaProduto->codfamiliaproduto}") => $model->FamiliaProduto->familiaproduto,
            url("grupo-produto/$model->codgrupoproduto") => $model->grupoproduto,
            'Alterar'
        ],
        $model->inativo
    ) 
!!}  
    <li class='active'>
        <small>
            <a title="Novo Grupo" href="{{ url("grupo-produto/create?codfamiliaproduto={$model->FamiliaProduto->codfamiliaproduto}") }}"><i class="glyphicon glyphicon-plus"></i></a>
            &nbsp;
            <a title="Detalhes" href="{{ url("grupo-produto/$model->codgrupoproduto") }}"><i class="glyphicon glyphicon-eye-open"></i></a>
        </small>
    </li>
</ol>
<hr>
<br>
{!! Form::model($model, ['method' => 'PATCH', 'class' => 'form-horizontal', 'id' => 'form-grupo-produto', 'action' => ['GrupoProdutoController@update', $model->codgrupoproduto] ]) !!}
    @include('errors.form_error')
    @include('grupo-produto.form', ['submitTextButton' => 'Salvar'])
{!! Form::close() !!}
@stop