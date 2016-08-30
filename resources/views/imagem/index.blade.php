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
<h1 class="header">
    {!! titulo(null, 'Imagens', null) !!}
    <a class="btn btn-primary pull-right" role="button" data-toggle="collapse" href="#div-filtro" aria-expanded="false" aria-controls="div-filtro">
        <span class='glyphicon glyphicon-search'></span>
    </a>    
</h1>
<div class="clearfix"></div>
<div class='collapse' id='div-filtro'>
    <div class='well well-sm' style="padding:9px 0">
{!! Form::model(
    Request::session()->get('imagem.index'), 
    [
        'route' => 'imagem.index', 
        'method' => 'GET', 
        'class' => 'form-horizontal', 
        'id' => 'imagem-search', 
        'role' => 'search'
    ])
!!}
        <div class="col-md-2">      
            <div class="form-group">
                {!! Form::label('ativo', 'Ativo', ['class' => 'col-sm-4 control-label']) !!}
                <div class="col-md-8">{!! Form::select2Ativo('ativo', null, ['class'=> 'form-control', 'id' => 'ativo']) !!}</div>
            </div>      
        </div>
        <div class="col-md-2">      
            <div class="form-group">
                <div class=" col-md-10">
                    <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i> Buscar</button>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
    {!! Form::close() !!}
</div>
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
        <h3>Nenhuma imagem encontrada!</h3>
    @endif    
  </div>
  {!! $model->appends(Request::session()->get('imagem.index'))->render() !!}
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
function atualizaFiltro()
{
    scroll();
    var frmValues = $('#imagem-search').serialize();
    $.ajax({
        type: 'GET',
        url: baseUrl + '/imagem',
        data: frmValues,
        dataType: 'html'
    })
    .done(function (data) {
        $('#items').html(jQuery(data).find('#items').html());
    })
    .fail(function () {
        console.log('Erro no filtro');
    });

    $('#items').infinitescroll('update', {
        state: {
            currPage: 1,
            isDestroyed: false,
            isDone: false             
        },
        path: ['?page=', '&'+frmValues]
    });
}

function scroll()
{
    var loading_options = {
        finishedMsg: "<div class='end-msg'>Fim dos registros</div>",
        msgText: "<div class='center'>Carregando mais itens...</div>",
        img: baseUrl + '/public/img/ajax-loader.gif'
    };

    $('#items').infinitescroll({
        loading : loading_options,
        navSelector : "#registros .pagination",
        nextSelector : "#registros .pagination li.active + li a",
        itemSelector : "#items div.imagem-grid-item",
    });    
}    
$(document).ready(function() {
    scroll();

});  
</script>
@endsection
@stop