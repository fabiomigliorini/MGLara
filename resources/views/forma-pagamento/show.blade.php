@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="{{ url('forma-pagamento') }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>             
            <li><a href="{{ url('forma-pagamento/create') }}"><span class="glyphicon glyphicon-plus"></span> Novo</a></li>             
            <li><a href="{{ url("forma-pagamento/$model->codformapagamento/edit") }}"><span class="glyphicon glyphicon-pencil"></span> Alterar</a></li> 
            <li>
                {!! Form::open(['method' => 'DELETE', 'id'=>'deleteId', 'route' => ['forma-pagamento.destroy', $model->codformapagamento]]) !!}
                <span class="glyphicon glyphicon-trash"></span>
                {!! Form::submit('Excluir') !!}
                {!! Form::close() !!}
            </li>
        </ul>
    </div>
</nav>
<ol class="breadcrumb header">{{ $model->formapagamento }}</ol>
<hr>
<div class="row">
  <div class="col-lg-12">
      <table class="detail-view table table-striped table-condensed"> 
        <tbody>  
          <tr> 
            <th class="col-md-2">#</th> 
            <td class="col-md-10">{{ formataCodigo($model->codformapagamento) }}</td> 
          </tr>
          <tr> 
            <th>Forma pagamento</th> 
            <td>{{ $model->formapagamento }}</td> 
          </tr>
          <tr> 
            <th>Boleto</th> 
            <td>{{ !empty($model->boleto) ? 'Sim' : 'Não' }}</td> 
          </tr>
          <tr> 
            <th>Fechamento</th> 
            <td>{{ !empty($model->fechamento) ? 'Sim' : 'Não' }}</td> 
          </tr>
          <tr> 
            <th>Nota Fiscal</th> 
            <td>{{ !empty($model->notafiscal) ? 'Sim' : 'Não' }}</td> 
          </tr>
          <tr> 
            <th>Parcelas</th> 
            <td>{{ $model->parcelas }}</td> 
          </tr>
          <tr> 
            <th>Dias Entre Parcelas </th> 
            <td>{{ $model->diasentreparcelas }}</td> 
          </tr>
          <tr> 
            <th>Á vista</th> 
            <td>{{ !empty($model->avista) ? 'Sim' : 'Não' }}</td> 
          </tr>
          <tr> 
            <th>Forma de Pagamento ECF</th> 
            <td>{{ $model->formapagamentoecf }}</td> 
          </tr>
          <tr> 
            <th>Entrega</th> 
            <td>{{ !empty($model->entrega) ? 'Sim' : 'Não' }}</td> 
          </tr>
        </tbody> 
      </table>
  </div>    
</div>
<hr>
@include('includes.autor')
@stop