@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
{!! 
    titulo(
        $produto->codproduto,
        [
            url("produto") => 'Produtos',
            url("produto/$produto->codproduto") => $produto->produto,
            'Nova Embalagem',
        ],
        $model->inativo,
        6
    ) 
!!}     
</ol>
<br>
{!! Form::model($model, ['method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form-produto-embalagem', 'route' => ['produto-embalagem.store', 'codproduto' => $produto->codproduto]]) !!}
    @include('errors.form_error')
    @include('produto-embalagem.form', ['submitTextButton' => 'Salvar'])
 {!! Form::close() !!}   
@stop