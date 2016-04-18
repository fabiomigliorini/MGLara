@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="{{ url('pais') }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>             
            <li><a href="{{ url('pais/create') }}"><span class="glyphicon glyphicon-plus"></span> Novo</a></li>             
            <li><a href="{{ url("pais/$model->codpais/edit") }}"><span class="glyphicon glyphicon-pencil"></span> Alterar</a></li> 
            <li>
                {!! Form::open(['method' => 'DELETE', 'id'=>'deleteId', 'route' => ['pais.destroy', $model->codpais]]) !!}
                <span class="glyphicon glyphicon-trash"></span>
                {!! Form::submit('Excluir') !!}
                {!! Form::close() !!}
            </li>
        </ul>
    </div>
</nav>
<h1 class="header">{{ $model->pais }}</h1>
<hr>
<div class="row">
  <div class="col-lg-12">
      <table class="detail-view table table-striped table-condensed"> 
        <tbody>  
          <tr> 
            <th class="col-md-2">#</th> 
            <td class="col-md-10">{{ formataCodigo($model->codpais) }}</td> 
          </tr>
          <tr> 
            <th>País</th> 
            <td>{{ $model->pais }}</td> 
          </tr>
          <tr> 
            <th>Sigla</th> 
            <td>{{ $model->sigla }}</td> 
          </tr>
        </tbody> 
      </table>
  </div>    
</div>
<hr>
@include('includes.autor')
<hr>
<h2>Estados</h2>
<hr>
<div class="search-bar">
{!! Form::model(Request::all(), ['method' => 'GET', 'class' => 'form-inline', 'id' => 'estado-search', 'role' => 'search', 'autocomplete' => 'off'])!!}
    <div class="form-group">
        {!! Form::text('codestado', null, ['class' => 'form-control search-cod', 'placeholder' => '#']) !!}
    </div>
    <div class="form-group">
        {!! Form::text('estado', null, ['class' => 'form-control', 'placeholder' => 'Estado']) !!}
    </div>
    <div class="form-group">
        {!! Form::text('codigooficial', null, ['class' => 'form-control', 'placeholder' => 'Codigo']) !!}
    </div>
    <button type="submit" class="btn btn-default">Buscar</button>
{!! Form::close() !!}
</div>
<br>
<div id="registros">
  <div class="list-group" id="items">
    @foreach($estados as $row)
      <div class="list-group-item">
        <div class="row item">
            <div class="col-md-2">
                <a href="{{ url("estado/$row->codestado") }}">{{ formataCodigo($row->codestado) }}</a>
            </div>                            
            <div class="col-md-2">
                <a href="{{ url("estado/$row->codestado") }}">{{ $row->estado }}</a>
            </div>
            <div class="col-md-2">
                {{ $row->codigooficial }}
            </div>
            <div class="col-md-6">
                
            </div>
        </div>
      </div>    
    @endforeach
    @if (count($estados) === 0)
        <h3>Nenhum registro encontrado!</h3>
    @endif    
  </div>
  {!! $estados->appends(Request::all())->render() !!}
</div>
@section('inscript')
<script type="text/javascript">
$(document).ready(function() {
    $('ul.pagination').removeClass('hide');
    $('#estado-search').change(function() {
        this.submit();
    });    
});
</script>
@endsection
@stop