@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
{!! 
    titulo(
        $parent->FamiliaProduto->SecaoProduto->codsecaoproduto,
        [
            url("secao-produto/{$parent->FamiliaProduto->SecaoProduto->codsecaoproduto}") => $parent->FamiliaProduto->SecaoProduto->secaoproduto,
            url("familia-produto/{$parent->FamiliaProduto->codfamiliaproduto}") => $parent->FamiliaProduto->familiaproduto,
            url("grupo-produto/{$parent->codgrupoproduto}") => $parent->grupoproduto,
            'Novo Sub-Grupo de Produto'
        ],
        null
    ) 
!!} 
</ol>
<hr>
<br>
{!! Form::model($model, ['method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form-sub-grupo-produto', 'route' => ['sub-grupo-produto.store', 'codgrupoproduto' => $request->codgrupoproduto ]]) !!}
    @include('errors.form_error')
    @include('sub-grupo-produto.form', ['submitTextButton' => 'Salvar'])
 {!! Form::close() !!}   
@stop