@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="{{ url("produto") }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>
        </ul>
    </div>
</nav>
<h1 class="header">
{!! 
    titulo(
        null,
        [
            url('produto') => 'Produtos',
            'Novo Produto'
        ],
        $model->inativo
    ) 
!!}   
</h1>
<hr>
{!! Form::model($model, ['method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form-produto', 'route' => 'produto.store', 'autocomplete'=>'off']) !!}
    @include('errors.form_error')
    @include('produto.form', ['submitTextButton' => 'Salvar'])
 {!! Form::close() !!}   
@stop