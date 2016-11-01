@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
{!! 
    titulo(
        $parent->codsecaoproduto,
        [
            url("secao-produto/$parent->codsecaoproduto") => $parent->secaoproduto,
            "Nova Família"
        ],
        null
    ) 
!!}      
</ol>
<hr>
<br>
{!! Form::model($model, ['method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form-familia-produto', 'route' => ['familia-produto.store', 'codsecaoproduto'=> $request->codsecaoproduto ]]) !!}
    @include('errors.form_error')
    @include('familia-produto.form', ['submitTextButton' => 'Salvar'])
{!! Form::close() !!}   
@stop