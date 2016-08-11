@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
  <div class="container-fluid"> 
    <ul class="nav navbar-nav">
      <li>
        <a href="<?php echo url('usuario/create');?>"><span class="glyphicon glyphicon-plus"></span> Novo</a>
      </li> 
    </ul>
  </div>
</nav>
<h1 class="header">
    {!! titulo(null, 'Usuários', null) !!}
    <a class="btn btn-primary pull-right" role="button" data-toggle="collapse" href="#div-filtro" aria-expanded="false" aria-controls="div-filtro">
        <span class='glyphicon glyphicon-search'></span>
    </a> 
</h1>
<div class="clearfix"></div>
<div class='collapse' id='div-filtro'>
    <div class='well well-sm' style="padding:9px 0">
          {!! Form::model(Request::session()->get('usuario.index'), [
            'route' => 'usuario.index', 
            'method' => 'GET', 
            'class' => 'form-horizontal',
            'id' => 'usuario-search',
            'role' => 'search'
          ])!!}
        <div class="col-md-3">
            <div class="form-group">
                {!! Form::label('codusuario', '#', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-5">{!! Form::text('codusuario', null, ['class' => 'form-control', 'placeholder' => '#']) !!}</div>
            </div>
            <div class="form-group">
                {!! Form::label('usuario', 'Usuário', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-9">{!! Form::text('usuario', null, ['class' => 'form-control', 'placeholder' => 'Usuário']) !!}</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('codpessoa', 'Pessoa', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-md-9">{!! Form::select2Pessoa('codpessoa', null, ['class' => 'form-control', 'id'=>'codpessoa', 'placeholder' => 'Pessoa', 'ativo' => 9]) !!}</div>
            </div>
            <div class="form-group">
                {!! Form::label('codfilial', 'Filial', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-md-4">{!! Form::select2Filial('codfilial', null, ['class' => 'form-control', 'id'=>'codfilial']) !!}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                {!! Form::label('ativo', 'Ativo', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-9">{!! Form::select2Ativo('ativo', null, ['class'=> 'form-control', 'id' => 'ativo']) !!}</div>
            </div>              
            <div class="form-group">
                <div class="col-md-offset-3 col-md-10">              
                    <button type="submit" class="btn btn-default">Buscar</button>
                </div>
            </div>
        </div>  
        <div class="clearfix"></div>
    {!! Form::close() !!}
</div>
</div>
<div id="registros">
  <div class="list-group list-group-striped list-group-hover" id="items">
    @foreach($model as $row)
      <div class="list-group-item @if(!empty($row->inativo)) bg-danger @endif">
        <div class="row item">
            <div class="col-md-1">
              <a class="small text-muted" href="<?php echo url("usuario/$row->codusuario");?>">{{formataCodigo($row->codusuario)}}</a>
            </div>                            
            <div class="col-md-2">
              <a href="<?php echo url("usuario/$row->codusuario");?>">{!! listagemTitulo($row->usuario, $row->inativo) !!}</a>
            </div>
            <div class="col-md-4">
                <a href="<?php echo url("pessoa/$row->codpessoa");?>">{{ $row->Pessoa['pessoa'] }}</a>
            </div>
            <div class="col-md-3">
                <a href="<?php echo url("filial/$row->codfilial");?>">{{ $row->Filial['filial'] }}</a>
            </div>
            <div class="col-md-2">
                {!! inativo($row->inativo) !!}
            </div>            
        </div>
      </div>    
    @endforeach
    @if (count($model) === 0)
        <h3>Nenhum registro encontrado!</h3>
    @endif    
  </div>
  <?php echo $model->appends(Request::session()->get('usuario.index'))->render();?>
</div>
@section('inscript')
<script type="text/javascript">
function atualizaFiltro()
{
    scroll();
    var frmValues = $("#usuario-search").serialize();
    $.ajax({
        type: 'GET',
        url: baseUrl + '/usuario',
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
    $("#usuario-search").on("change", function (event) {
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

