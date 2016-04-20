@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li>
                <a href="{{ url("grupo-cliente/create") }}"><span class="glyphicon glyphicon-plus"></span> Novo</a>
            </li> 
        </ul>
    </div>
</nav>
<h1 class="header">Grupos de cliente</h1>
<hr>
<div class="search-bar">
{!! Form::model(Request::all(), ['route' => 'grupo-cliente.index', 'method' => 'GET', 'class' => 'form-inline', 'id' => 'grupo-cliente-search', 'role' => 'search', 'autocomplete' => 'off'])!!}
    <div class="form-group">
        {!! Form::text('codgrupocliente', null, ['class' => 'form-control search-cod', 'placeholder' => '#']) !!}
    </div>
    <div class="form-group">
        {!! Form::text('grupocliente', null, ['class' => 'form-control', 'placeholder' => 'Grupo']) !!}
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
            <div class="col-md-1">
                {{ formataCodigo($row->codgrupocliente)}}
            </div>                            
            <div class="col-md-4">
                <a href="{{ url("grupo-cliente/$row->codgrupocliente") }}">{{ $row->grupocliente }}</a>
            </div>                            
            <div class="col-md-4">
            
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
    $('#grupo-cliente-search').change(function() {
        this.submit();
    });    
});
</script>
@endsection
@stop