@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="{{ url("pais/$model->codpais") }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>             
            <li><a href="{{ url("estado/create?codpais=$model->codpais") }}"><span class="glyphicon glyphicon-plus"></span> Novo</a></li>             
            <li><a href="{{ url("estado/$model->codestado/edit") }}"><span class="glyphicon glyphicon-pencil"></span> Alterar</a></li> 
            <li>
                {!! Form::open(['method' => 'DELETE', 'id'=>'deleteId', 'route' => ['estado.destroy', $model->codestado]]) !!}
                <span class="glyphicon glyphicon-trash"></span>
                {!! Form::submit('Excluir') !!}
                {!! Form::close() !!}
            </li>
        </ul>
    </div>
</nav>
<h1 class="header">{{ $model->estado }}</h1>
<hr>
<div class="row">
  <div class="col-lg-12">
      <table class="detail-view table table-striped table-condensed"> 
        <tbody>  
          <tr> 
            <th class="col-md-2">#</th> 
            <td class="col-md-10">{{ formataCodigo($model->codestado) }}</td> 
          </tr>
          <tr> 
            <th>País</th> 
            <td>{{ $model->Pais->pais }}</td> 
          </tr>
          <tr> 
            <th>Estado</th> 
            <td>{{ $model->estado }}</td> 
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
<hr>
<h2>Cidades <span class="titulo-btn-novo"><a class="btn btn-default" href="{{ url("cidade/create?codestado=$model->codestado") }}"><i class=" glyphicon glyphicon-plus"></i> Novo</a></span></h2>
<hr>
<div class="search-bar">
{!! Form::model(Request::all(), ['method' => 'GET', 'class' => 'form-inline', 'id' => 'cidade-search', 'role' => 'search', 'autocomplete' => 'off'])!!}
    <div class="form-group">
        {!! Form::text('codcidade', null, ['class' => 'form-control search-cod', 'placeholder' => '#']) !!}
    </div>
    <div class="form-group">
        {!! Form::text('cidade', null, ['class' => 'form-control', 'placeholder' => 'Cidade']) !!}
    </div>
    <div class="form-group">
        {!! Form::text('codigooficial', null, ['class' => 'form-control', 'placeholder' => 'Codigo IBGE']) !!}
    </div>
    <button type="submit" class="btn btn-default">Buscar</button>
{!! Form::close() !!}
</div>
<br>
<div id="registros">
  <div class="list-group" id="items">
    @foreach($cidades as $row)
      <div class="list-group-item">
        <div class="row item">
            <div class="col-md-2">
                <a href="{{ url("cidade/$row->codcidade") }}">{{ formataCodigo($row->codcidade) }}</a>
            </div>                            
            <div class="col-md-2">
                <a href="{{ url("cidade/$row->codcidade") }}">{{ $row->cidade }}</a>
            </div>
            <div class="col-md-2">
                {{ $row->codigooficial }}
            </div>
            <div class="col-md-6">
                
            </div>
        </div>
      </div>    
    @endforeach
    @if (count($cidades) === 0)
        <h3>Nenhum registro encontrado!</h3>
    @endif    
  </div>
  {!! $cidades->appends(Request::all())->render() !!}
</div>
@section('inscript')
<script type="text/javascript">
$(document).ready(function() {
    $('ul.pagination').removeClass('hide');
    $('#cidade-search').change(function() {
        this.submit();
    });    
});
</script>
@endsection
@stop