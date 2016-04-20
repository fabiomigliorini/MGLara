@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="{{ url('grupo-cliente') }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>             
            <li><a href="{{ url('grupo-cliente/create') }}"><span class="glyphicon glyphicon-plus"></span> Novo</a></li>             
            <li><a href="{{ url("grupo-cliente/$model->codgrupocliente/edit") }}"><span class="glyphicon glyphicon-pencil"></span> Alterar</a></li> 
            <li>
                {!! Form::open(['method' => 'DELETE', 'id'=>'deleteId', 'route' => ['grupo-cliente.destroy', $model->codgrupocliente]]) !!}
                <span class="glyphicon glyphicon-trash"></span>
                {!! Form::submit('Excluir') !!}
                {!! Form::close() !!}
            </li>
        </ul>
    </div>
</nav>
<h1 class="header">{{ $model->grupocliente }}</h1>
<hr>
<div class="row">
  <div class="col-lg-12">
      <table class="detail-view table table-striped table-condensed"> 
        <tbody>  
          <tr> 
            <th class="col-md-2">#</th> 
            <td class="col-md-10">{{ formataCodigo($model->codgrupocliente) }}</td> 
          </tr>
          <tr> 
            <th>Grupo cliente</th> 
            <td>{{ $model->grupocliente }}</td> 
          </tr>
        </tbody> 
      </table>
  </div>    
</div>
<hr>
@include('includes.autor')
@stop