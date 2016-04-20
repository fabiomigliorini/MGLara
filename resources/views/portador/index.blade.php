@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li>
                <a href="{{ url("portador/create") }}"><span class="glyphicon glyphicon-plus"></span> Novo</a>
            </li> 
        </ul>
    </div>
</nav>
<h1 class="header">Portadores</h1>
<hr>
<div class="search-bar">
{!! Form::model(Request::all(), ['route' => 'portador.index', 'method' => 'GET', 'class' => 'form-inline', 'id' => 'portador-search', 'role' => 'search', 'autocomplete' => 'off'])!!}
    <div class="form-group">
        {!! Form::text('codportador', null, ['class' => 'form-control search-cod', 'placeholder' => '#']) !!}
    </div>
    <div class="form-group">
        {!! Form::text('portador', null, ['class' => 'form-control', 'placeholder' => 'Portador']) !!}
    </div>
    <div class="form-group">
        <div style="width: 180px">{!! Form::select('codbanco', $bancos, ['class'=> 'form-control'], ['id' => 'codbanco', 'style'=>'width:100%']) !!}</div>
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
                {{ formataCodigo($row->codportador)}}
            </div>                            
            <div class="col-md-3">
                <a href="{{ url("portador/$row->codportador") }}">{{ $row->portador }}</a>
            </div>                            
            <div class="col-md-1">
                {{ $row->Banco->banco or '' }}
            </div>                            
            <div class="col-md-2">
                {{ $row->Filial->filial or '' }}
            </div>                            
            <div class="col-md-1">
                {{ !empty($row->emiteboleto) ? 'Boleto' : '' }}
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
    $('#portador-search').change(function() {
        this.submit();
    });
    $('#codbanco').select2({
        allowClear:true,
        closeOnSelect:true,
        placeholder: 'Banco'
    })<?php echo (app('request')->input('codbanco') ? ".select2('val'," .app('request')->input('codbanco').");" : ';'); ?>
});
</script>
@endsection
@stop