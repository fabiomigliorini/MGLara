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
<h1 class="header">{!! titulo(null, 'Seções de Produto', null) !!}  </h1>
<hr>
<div class="search-bar">
{!! Form::model(
    Request::session()->get('secao-produto.index'), 
    [
        'route' => 'secao-produto.index', 
        'method' => 'GET', 
        'class' => 'form-inline', 
        'id' => 'secao-produto-search', 
        'role' => 'search', 
        'autocomplete' => 'off'
    ]
)!!}
    <div class="form-group">
        {!! Form::text('codsecaoproduto', null, ['class' => 'form-control search-cod', 'placeholder' => '#']) !!}
    </div>
    <div class="form-group">
        {!! Form::text('secaoproduto', null, ['class' => 'form-control', 'placeholder' => 'Seção']) !!}
    </div>
    <div class="form-group">
        {!! Form::select2Ativo('ativo', null, ['class'=> 'form-control', 'id' => 'ativo', 'style'=>'width:120px']) !!}
    </div>      
    <button type="submit" class="btn btn-default">Buscar</button>
{!! Form::close() !!}
</div>

<br>
<div id="registros">
  <div class="list-group list-group-striped list-group-hover" id="items">
    @foreach($model as $row)
      <div class="list-group-item @if(!empty($row->inativo)) bg-danger @endif">
        <div class="row item">
            <div class="col-md-1">
                <a class="small text-muted" href="{{ url("secao-produto/$row->codsecaoproduto") }}">
                {{ formataCodigo($row->codsecaoproduto)}}
                </a>          
            </div>                            
            <div class="col-md-7">
            <a href="{{ url("secao-produto/$row->codsecaoproduto") }}">
                {!! listagemTitulo($row->secaoproduto, $row->inativo) !!}
            </a>
            </div>                            
            <div class="col-md-2">
                {!! inativo($row->inativo) !!}
            </div>
            <div class="col-md-2">
            @if(!empty($row->codimagem))
                <div class="pull-right foto-item-listagem">
                    <img class="img-responsive pull-right" alt="{{$row->secaoproduto}}" title="{{$row->secaoproduto}}" src='<?php echo URL::asset('public/imagens/'.$row->Imagem->observacoes);?>'>
                </div>
            @endif             
            </div>                            
        </div>
      </div>    
    @endforeach
    @if (count($model) === 0)
        <h3>Nenhum registro encontrado!</h3>
    @endif    
  </div>
  <?php echo $model->appends(Request::session()->get('secao-produto.index'))->render();?>
</div>
@section('inscript')
<script type="text/javascript">
function atualizaFiltro()
{
    scroll();
    var frmValues = $("#secao-produto-search").serialize();
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
        itemSelector : "#items div.list-group-item",
    });    
}
$(document).ready(function() {
    scroll();
    $("#secao-produto-search").on("change", function (event) {
        $('#items').infinitescroll('destroy');
        atualizaFiltro();
    }).on('submit', function (event){
        event.preventDefault();
        $('#items').infinitescroll('destroy');
        atualizaFiltro();
    });        

});
</script>
@endsection
@stop