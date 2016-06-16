@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li>
                <a href="{{ url("secao-produto/create") }}"><span class="glyphicon glyphicon-plus"></span> Novo</a>
            </li> 
        </ul>
    </div>
</nav>
<h1 class="header">Seções de produto</h1>
<hr>
<div class="search-bar">
{!! Form::model(Request::all(), ['route' => 'secao-produto.index', 'method' => 'GET', 'class' => 'form-inline', 'id' => 'secao-produto-search', 'role' => 'search', 'autocomplete' => 'off'])!!}
    <div class="form-group">
        {!! Form::text('codsecaoproduto', null, ['class' => 'form-control search-cod', 'placeholder' => '#']) !!}
    </div>
    <div class="form-group">
        {!! Form::text('secaoproduto', null, ['class' => 'form-control', 'placeholder' => 'Seção']) !!}
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
            <div class="col-md-4">
            <a href="{{ url("secao-produto/$row->codsecaoproduto") }}">{{ formataCodigo($row->codsecaoproduto)}}</a>
            </div>                            
            <div class="col-md-4">
            <a href="{{ url("secao-produto/$row->codsecaoproduto") }}">{{ $row->secaoproduto }}</a>
            </div>                            
            <div class="col-md-4">
            <a href="{{ url("secao-produto/$row->codsecaoproduto") }}">{{ $row->sigla }}</a>
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

    $("#secao-produto-search").on("change", function (event) {
        var $this = $(this);
        var frmValues = $this.serialize();
        $.ajax({
            type: 'GET',
            url: baseUrl + '/secao-produto',
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