@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li>
                <a href="{{ url("unidade-medida/create") }}"><span class="glyphicon glyphicon-plus"></span> Novo</a>
            </li> 
        </ul>
    </div>
</nav>
<h1 class="header">Unidades de medida</h1>
<hr>
<div class="search-bar">
{!! Form::model(Request::all(), ['route' => 'unidade-medida.index', 'method' => 'GET', 'class' => 'form-inline', 'id' => 'unidade-medida-search', 'role' => 'search', 'autocomplete' => 'off'])!!}
    <div class="form-group">
        {!! Form::text('codunidademedida', null, ['class' => 'form-control search-cod', 'placeholder' => '#']) !!}
    </div>    
    <div class="form-group">
        {!! Form::text('unidademedida', null, ['class' => 'form-control', 'placeholder' => 'Unidade de medida']) !!}
    </div>
    <button type="submit" class="btn btn-default">Buscar</button>
{!! Form::close() !!}
</div>

<br>
<div id="registros">
  <div class="list-group" id="items">
    @foreach($model as $row)
      <div class="list-group-item">
        <div class="row item">
            <div class="col-md-2">
                <a href="{{ url("unidade-medida/$row->codunidademedida") }}">{{ formataCodigo($row->codunidademedida) }}</a>
            </div>                            
            <div class="col-md-3">
                <a href="{{ url("unidade-medida/$row->codunidademedida") }}">{{ $row->unidademedida }}</a>
            </div>                            
            <div class="col-md-2">
                <a href="{{ url("unidade-medida/$row->codunidademedida") }}">{{ $row->sigla }}</a>
            </div>                            
        </div>
      </div>    
    @endforeach
    @if (count($model) === 0)
        <h3>Nenhum registro encontrado!</h3>
    @endif    
  </div>
  <?php echo $model->appends(Request::all())->render();?>
</div>
@section('inscript')
<script type="text/javascript">
$(document).ready(function() {
    $('ul.pagination').removeClass('hide');
    $('#unidade-medida-search').change(function() {
        this.submit();
    });    
});
</script>
@endsection
@stop