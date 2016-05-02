@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li>
                <a href="{{ url('imagem/lixeira') }}"><i class="glyphicon glyphicon-trash"></i> Lixeira</a>
            </li> 
        </ul>
    </div>
</nav>
<h1 class="header">Imagens</h1>
<hr>
<div class="search-bar">
{!! Form::model(Request::all(), ['route' => 'imagem.index', 'method' => 'GET', 'class' => 'form-inline', 'id' => 'imagem-search', 'role' => 'search'])!!}
    <div class="form-group">
        <select class="form-control" name="inativo" id="inativo">
            <option value=""></option>
            <option value="0">Todos</option>
            <option value="1" selected="selected">Ativos</option>
            <option value="2">Inativos</option>
        </select>
    </div>      
    <button type="submit" class="btn btn-default">Buscar</button>
{!! Form::close() !!}
</div>

<br>
<div id="registros">
    <div id="imagens" class="row">
    @foreach($model as $row)
        <div class="imagem-grid-item col-xs-2">
            <a href="{{ url("imagem/{$row->codimagem}") }}" class="thumbnail @if(!empty($row->inativo)) inativo @endif">
                <img src="<?php echo URL::asset('public/imagens/'.$row->observacoes);?>" class="img-responsive">
            </a>
        </div>          
    @endforeach
    @if (count($model) === 0)
        <h3>Nenhum registro encontrado!</h3>
    @endif    
  </div>
  <?php echo $model->appends(Request::all())->render();?>
</div>
@section('inscript')
<style type="text/css">
.img-responsive {
    height: 115px !important;
}
.thumbnail.inativo {
    border: 1px solid #c4170c;
}
</style>
<script type="text/javascript">
$(document).ready(function() {
    $('.pagination').addClass('hide');
    var loading_options = {
        finishedMsg: "<div class='end-msg'>Fim dos registros</div>",
        msgText: "<div class='center'>Carregando mais itens...</div>",
        img: 'public/img/ajax-loader.gif'
    };
    $('#imagens').infinitescroll({
        loading : loading_options,
        navSelector : "#registros .pagination",
        nextSelector : "#registros .pagination li.active + li a",
        itemSelector : "#imagens div.imagem-grid-item"
    });    
});  
</script>
@endsection
@stop