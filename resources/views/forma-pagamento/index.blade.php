@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li>
                <a href="{{ url("forma-pagamento/create") }}"><span class="glyphicon glyphicon-plus"></span> Novo</a>
            </li> 
        </ul>
    </div>
</nav>
<ol class="breadcrumb header">Formas de pagamento</ol>
<hr>
<div class="search-bar">
{!! Form::model(Request::all(), ['route' => 'forma-pagamento.index', 'method' => 'GET', 'class' => 'form-inline', 'id' => 'forma-pagamento-search', 'role' => 'search', 'autocomplete' => 'off'])!!}
    <div class="form-group">
        {!! Form::text('codformapagamento', null, ['class' => 'form-control search-cod', 'placeholder' => '#']) !!}
    </div>
    <div class="form-group">
        {!! Form::text('formapagamento', null, ['class' => 'form-control', 'placeholder' => 'Forma de pagamento']) !!}
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
                {{ formataCodigo($row->codformapagamento)}}
            </div>                            
            <div class="col-md-3">
                <a href="{{ url("forma-pagamento/$row->codformapagamento") }}">{{ $row->formapagamento }}</a>
            </div>                            
            <div class="col-md-1">
                {{ !empty($row->boleto) ? 'Boleto' : '' }}
            </div>                            
            <div class="col-md-1">
                {{ !empty($row->fechamento) ? 'Fechamento' : '' }}
            </div>                            
            <div class="col-md-1">
                {{ !empty($row->notafiscal) ? 'Nota Fiscal' : '' }}
            </div>                            
            <div class="col-md-1">
                {{ !empty($row->avista) ? 'Á Vista' : 'Á prazo' }}
            </div>                            
            <div class="col-md-1">
                {{ $row->parcelas }}
            </div>                            
            <div class="col-md-1">
                {{ $row->diasentreparcelas }}
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
    $('#forma-pagamento-search').change(function() {
        this.submit();
    });    
});
</script>
@endsection
@stop