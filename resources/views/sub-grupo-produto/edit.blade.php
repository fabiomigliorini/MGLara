@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="<?php echo url("grupo-produto/$model->codgrupoproduto");?>"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>             
            <li><a href="<?php echo url('sub-grupo-produto/create?codgrupoproduto='.$model->GrupoProduto->codgrupoproduto);?>" ><span class="glyphicon glyphicon-plus"></span> Novo</a></li>             
            <li><a href="<?php echo url("sub-grupo-produto/$model->codsubgrupoproduto");?>"><span class="glyphicon glyphicon-eye-open"></span> Detalhes</a></li> 
        </ul>
    </div>
</nav>
<h1 class="header">
{!! 
    titulo(
        $model->codsubgrupoproduto,
        [
            [
            	'url' => "secao-produto/{$model->GrupoProduto->FamiliaProduto->SecaoProduto->codsecaoproduto}", 
            	'descricao' => $model->GrupoProduto->FamiliaProduto->SecaoProduto->secaoproduto
        	],
            ['url' => "familia-produto/{$model->GrupoProduto->FamiliaProduto->codfamiliaproduto}", 	'descricao' => $model->GrupoProduto->FamiliaProduto->familiaproduto],
            ['url' => "grupo-produto/{$model->GrupoProduto->codgrupoproduto}", 			'descricao' => $model->GrupoProduto->grupoproduto],
            ['url' => "sub-grupo-produto/$model->codsubgrupoproduto", 					'descricao' => $model->subgrupoproduto],
            ['url' => null, 'descricao' => 'Alterar']
        ],
        $model->inativo
    ) 
!!} 
</h1>
<hr>
<br>
{!! Form::model($model, ['method' => 'PATCH', 'class' => 'form-horizontal', 'id' => 'form-sub-grupo-produto', 'action' => ['SubGrupoProdutoController@update', $model->codsubgrupoproduto] ]) !!}
    @include('errors.form_error')
    @include('sub-grupo-produto.form', ['submitTextButton' => 'Salvar'])
{!! Form::close() !!}
@stop