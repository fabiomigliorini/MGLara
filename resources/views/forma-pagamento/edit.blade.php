@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="{{ url('forma-pagamento') }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>             
            <li><a href="{{ url('forma-pagamento/create') }}"><span class="glyphicon glyphicon-plus"></span> Novo</a></li>             
            <li><a href="{{ url("forma-pagamento/$model->codformapagamento") }}"><span class="glyphicon glyphicon-eye-open"></span> Detalhes</a></li>  
        </ul>
    </div>
</nav>
<h1 class="header">Alterar tipo de produto: {{$model->formapagamento}}</h1>
<hr>
<br>
{!! Form::model($model, ['method' => 'PATCH', 'class' => 'form-horizontal', 'id' => 'form-forma-pagamento', 'action' => ['FormaPagamentoController@update', $model->codformapagamento] ]) !!}
    @include('errors.form_error')
    @include('forma-pagamento.form', ['submitTextButton' => 'Salvar'])
{!! Form::close() !!}
@stop