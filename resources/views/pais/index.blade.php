@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li>
                <a href="{{ url("pais/create") }}"><span class="glyphicon glyphicon-plus"></span> Novo</a>
            </li> 
        </ul>
    </div>
</nav>
<ol class="breadcrumb header">Países</ol>
<hr>
<div class="search-bar">
{!! Form::model(Request::all(), ['route' => 'pais.index', 'method' => 'GET', 'class' => 'form-inline', 'id' => 'pais-search', 'role' => 'search', 'autocomplete' => 'off'])!!}
    <div class="form-group">
        {!! Form::text('codpais', null, ['class' => 'form-control search-cod', 'placeholder' => '#']) !!}
    </div>
    <div class="form-group">
        {!! Form::text('pais', null, ['class' => 'form-control', 'placeholder' => 'País']) !!}
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
            <div class="col-md-4">
            <a href="{{ url("pais/$row->codpais") }}">{{ formataCodigo($row->codpais)}}</a>
            </div>                            
            <div class="col-md-4">
            <a href="{{ url("pais/$row->codpais") }}">{{ $row->pais }}</a>
            </div>                            
            <div class="col-md-4">
            <a href="{{ url("pais/$row->codpais") }}">{{ $row->sigla }}</a>
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
    $("#pais-search").on("change", function (event) {
        var $this = $(this);
        var frmValues = $this.serialize();
        $.ajax({
            type: 'GET',
            url: baseUrl + '/pais',
            data: frmValues
        })
        .done(function (data) {
            $('#items').html(jQuery(data).find('#items').html()); 
        })
        .fail(function () {
            console.log('Erro no filtro');
        });
        event.preventDefault(); 
    });       
});
</script>
@endsection
@stop