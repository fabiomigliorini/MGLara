@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="{{ url("estado/$model->codestado") }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>             
            <li><a href="{{ url("cidade/create?codestado=$model->codestado") }}"><span class="glyphicon glyphicon-plus"></span> Novo</a></li>             
            <li><a href="{{ url("cidade/$model->codcidade/edit") }}"><span class="glyphicon glyphicon-pencil"></span> Alterar</a></li> 
            <li>
                {!! Form::open(['method' => 'DELETE', 'id'=>'deleteId', 'route' => ['cidade.destroy', $model->codcidade]]) !!}
                <span class="glyphicon glyphicon-trash"></span>
                {!! Form::submit('Excluir') !!}
                {!! Form::close() !!}
            </li>
        </ul>
    </div>
</nav>
<h1 class="header">{{ $model->cidade }}</h1>
<hr>
<div class="row">
  <div class="col-lg-12">
      <table class="detail-view table table-striped table-condensed"> 
        <tbody>  
          <tr> 
            <th class="col-md-2">#</th> 
            <td class="col-md-10">{{ formataCodigo($model->codcidade) }}</td> 
          </tr>
          <tr> 
            <th>Estado</th> 
            <td>{{ $model->Estado->estado }}</td> 
          </tr>
          <tr> 
            <th>Sigla</th> 
            <td>{{ $model->sigla }}</td> 
          </tr>          
          <tr> 
            <th>Código IBGE</th> 
            <td>{{ $model->codigooficial }}</td> 
          </tr>
        </tbody> 
      </table>
  </div>    
</div>
<hr>
@include('includes.autor')
@stop