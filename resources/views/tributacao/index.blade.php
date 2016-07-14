@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li>
                <a href="{{ url("tributacao/create") }}"><span class="glyphicon glyphicon-plus"></span> Novo</a>
            </li> 
        </ul>
    </div>
</nav>
<h1 class="header">Tributações</h1>
<hr>
<div class="search-bar">
{!! Form::model(Request::all(), ['route' => 'tributacao.index', 'method' => 'GET', 'class' => 'form-inline', 'id' => 'tributacao-search', 'role' => 'search', 'autocomplete' => 'off'])!!}
    <div class="form-group">
        {!! Form::text('codtributacao', null, ['class' => 'form-control search-cod', 'placeholder' => '#']) !!}
    </div>
    <div class="form-group">
        {!! Form::text('tributacao', null, ['class' => 'form-control', 'placeholder' => 'Tributação']) !!}
    </div>
    <button type="submit" class="btn btn-default">Buscar</button>
{!! Form::close() !!}
</div>

<br>
<div id="registros">
  <div class="list-group list-group-striped list-group-hover" id="items">
    @foreach($model as $row)
      <div class="list-group-item">
        <div class="row item">
            <div class="col-md-1">
                {{ formataCodigo($row->codtributacao) }}
            </div>                            
            <div class="col-md-2">
                <a href="{{ url("tributacao/$row->codtributacao") }}">{{ $row->tributacao }}</a>
            </div>                            
            <div class="col-md-1">
                {{ $row->aliquotaicmsecf }}
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
    $('#tributacao-search').change(function() {
        this.submit();
    });    
});
</script>
@endsection
@stop