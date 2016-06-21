@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="<?php echo url("grupo-produto/$request->codgrupoproduto");?>"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>
        </ul>
    </div>
</nav>
<h1 class="header">
{!! 
    titulo(
        $parent->FamiliaProduto->SecaoProduto->codsecaoproduto,
        [
            [
            	'url' => "secao-produto/{$parent->FamiliaProduto->SecaoProduto->codsecaoproduto}", 
            	'descricao' => $parent->FamiliaProduto->SecaoProduto->secaoproduto
        	],
            ['url' => "familia-produto/{$parent->FamiliaProduto->codfamiliaproduto}", 'descricao' => $parent->FamiliaProduto->familiaproduto],
            ['url' => "grupo-produto/{$parent->codgrupoproduto}", 'descricao' => $parent->grupoproduto],
            ['id' => null, 'descricao' => 'Novo Sub Grupo Produto']
        ],
        null
    ) 
!!} 

</h1>
<hr>
<br>
{!! Form::model($model, ['method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form-sub-grupo-produto', 'route' => ['sub-grupo-produto.store', 'codgrupoproduto' => $request->codgrupoproduto] ]) !!}
    @include('errors.form_error')
    @include('sub-grupo-produto.form', ['submitTextButton' => 'Salvar'])
 {!! Form::close() !!}   
@stop