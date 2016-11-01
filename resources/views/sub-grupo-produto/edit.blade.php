@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
{!! 
    titulo(
        $model->codsubgrupoproduto,
        [
            url("secao-produto/{$model->GrupoProduto->FamiliaProduto->SecaoProduto->codsecaoproduto}") => $model->GrupoProduto->FamiliaProduto->SecaoProduto->secaoproduto,
            url("familia-produto/{$model->GrupoProduto->FamiliaProduto->codfamiliaproduto}") => $model->GrupoProduto->FamiliaProduto->familiaproduto,
            url("grupo-produto/{$model->GrupoProduto->codgrupoproduto}") => $model->GrupoProduto->grupoproduto,
            url("sub-grupo-produto/$model->codsubgrupoproduto") => $model->subgrupoproduto,
            'Alterar'
        ],
        $model->inativo
    ) 
!!}
    <li class='active'>
        <small>
            <a title="Novo Sub Grupo" href="<?php echo url('sub-grupo-produto/create?codgrupoproduto='.$model->GrupoProduto->codgrupoproduto);?>"><i class="glyphicon glyphicon-plus"></i></a>
            &nbsp;
            <a title="Detalhes" href="<?php echo url("sub-grupo-produto/$model->codsubgrupoproduto");?>"><i class="glyphicon glyphicon-eye-open"></i></a>
        </small>
    </li>

</ol>
<hr>
<br>
{!! Form::model($model, ['method' => 'PATCH', 'class' => 'form-horizontal', 'id' => 'form-sub-grupo-produto', 'action' => ['SubGrupoProdutoController@update', $model->codsubgrupoproduto] ]) !!}
    @include('errors.form_error')
    @include('sub-grupo-produto.form', ['submitTextButton' => 'Salvar'])
{!! Form::close() !!}
@stop