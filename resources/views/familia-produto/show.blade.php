@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="{{ url("secao-produto/$model->codsecaoproduto") }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>             
            <li><a href="{{ url("familia-produto/create?codsecaoproduto=$model->codsecaoproduto") }}"><span class="glyphicon glyphicon-plus"></span> Novo</a></li>
            <li><a href="{{ url("familia-produto/$model->codfamiliaproduto/edit") }}"><span class="glyphicon glyphicon-pencil"></span> Alterar</a></li> 
            <li>
                @if(empty($model->inativo))
                <a href="" id="inativo-familia-produto">
                    <span class="glyphicon glyphicon-ban-circle"></span> Inativar
                </a>
                @else
                <a href="" id="inativo-familia-produto">
                    <span class="glyphicon glyphicon-ok-sign"></span> Ativar
                </a>
                @endif
            </li>
            <li>
                <a href="{{ url("familia-produto/$model->codfamiliaproduto") }}" data-excluir data-pergunta="Tem certeza que deseja excluir a Família '{{ $model->familiaproduto }}'?" data-after-delete="location.replace(baseUrl + '/secao-produto/{{$model->codsecaoproduto}}');"><i class="glyphicon glyphicon-trash"></i> Excluir</a>
            </li>
        </ul>
    </div>
</nav>
<h1 class="header">
    {!! 
        titulo(
            $model->codfamiliaproduto,
            [
                url("secao-produto/$model->codsecaoproduto") => $model->SecaoProduto->secaoproduto,
                $model->familiaproduto
            ],
            $model->inativo
        ) 
    !!}
    <a class="btn btn-primary pull-right" role="button" data-toggle="collapse" href="#div-filtro" aria-expanded="false" aria-controls="div-filtro">
        <span class='glyphicon glyphicon-search'></span>
    </a>  
    <div class="pull-right foto-item-unico">
        @if(empty($model->codimagem))
            <a class="btn btn-default carregar" href="{{ url("/imagem/edit?id=$model->codfamiliaproduto&model=FamiliaProduto") }}">
                <i class="glyphicon glyphicon-picture"></i>
                 Carregar imagem
            </a>
        @else
        <a href="{{ url("imagem/{$model->Imagem->codimagem}") }}">
            <img class="img-responsive pull-right" src='<?php echo URL::asset('public/imagens/'.$model->Imagem->observacoes);?>'>
        </a>
        <span class="caption simple-caption">
            <a href="{{ url("/imagem/$model->codfamiliaproduto/delete/?model=FamiliaProduto&imagem={$model->Imagem->codimagem}") }}" class="btn btn-default btn-sm"><i class="glyphicon glyphicon-trash"></i> Excluir</a>        
            <a href="{{ url("/imagem/edit?id=$model->codfamiliaproduto&model=FamiliaProduto") }}" class="btn btn-default btn-sm"><i class="glyphicon glyphicon-pencil"></i> Alterar</a>
        </span>        
        @endif
    </div>
</h1>
@include('includes.autor')
<div class="clearfix">
    <a class="btn btn-default" href="{{ url("grupo-produto/create?codfamiliaproduto=$model->codfamiliaproduto") }}">
    <i class=" glyphicon glyphicon-plus"></i> Novo Grupo</a>
</div>
<div class='collapse' id='div-filtro'>
    <div class='well well-sm' style="padding:9px 0">
        {!! Form::model(
            Request::session()->get('familia-produto.show'),
            [
                'route' => 'familia-produto.show', 
                'method' => 'GET', 
                'class' => 'form-horizontal', 
                'id' => 'grupo-produto-search', 
                'role' => 'search', 
                'autocomplete' => 'off'
            ]
        )!!}
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('grupoproduto', 'Grupo', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-9">{!! Form::text('grupoproduto', null, ['class' => 'form-control', 'placeholder' => 'Grupo']) !!}</div>
            </div>
        </div>

        <div class="col-md-2">    
            <div class="form-group">
                {!! Form::label('ativo', 'Ativo', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-9">{!! Form::select2Ativo('ativo', null, ['class'=> 'form-control', 'id' => 'ativo']) !!}</div>
            </div>
        </div>
        <div class="col-md-3">    
            <div class="form-group">
                <div class="col-md-12">               
                    <button type="submit" class="btn btn-default"><i class=" glyphicon glyphicon-search"></i> Buscar</button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
        <div class="clearfix"></div>
    </div>
    {!! Form::close() !!}
</div>
<div id="registros">
  <div class="list-group list-group-striped list-group-hover" id="items">
    @foreach($grupos as $row)
        <div class="list-group-item @if(!empty($row->inativo)) bg-danger @endif"">
            <div class="row item">
                <div class="col-md-1">
                    <a class="small text-muted" href="{{ url("grupo-produto/$row->codgrupoproduto") }}">{{ formataCodigo($row->codgrupoproduto) }}</a>
                </div>                            
                <div class="col-md-7">
                    <a href="{{ url("grupo-produto/$row->codgrupoproduto") }}">
                        {!! listagemTitulo($row->grupoproduto, $row->inativo) !!}
                    </a>
                </div>
                <div class="col-md-2">
                    {!! inativo($row->inativo) !!}
                </div>
                <div class="col-md-2">
                @if(!empty($row->codimagem))
                    <div class="pull-right foto-item-listagem">
                        <img class="img-responsive pull-right" alt="{{$row->grupoproduto}}" title="{{$row->grupoproduto}}" src='<?php echo URL::asset('public/imagens/'.$row->Imagem->observacoes);?>'>
                    </div>
                @endif                      
                </div>
            </div>
        </div>    
    @endforeach
    @if (count($grupos) === 0)
        <h3>Nenhum Grupo encontrado!</h3>
    @endif    
  </div>
  {!! $grupos->appends(Request::session()->get('familia-produto.show'))->render() !!}
</div>
@section('inscript')
<script type="text/javascript">
function atualizaFiltro()
{
    scroll();
    var frmValues = $("#grupo-produto-search").serialize();
    $.ajax({
        type: 'GET',
        url: baseUrl + '/familia-produto/'+ {{$model->codfamiliaproduto}},
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
    $("#grupo-produto-search").on("change", function (event) {
        $('#items').infinitescroll('destroy');
        atualizaFiltro();
    }).on('submit', function (event){
        event.preventDefault();
        $('#items').infinitescroll('destroy');
        atualizaFiltro();
    });        
    
    $('#inativo-familia-produto').on("click", function(e) {
        e.preventDefault();
        var codfamiliaproduto = {{ $model->codfamiliaproduto }};
        var token = '{{ csrf_token() }}';
        var inativo = '{{ $model->inativo }}';
        if(inativo.length === 0) {
            acao = 'inativar';
        } else {
            acao = 'ativar';
        }        
        bootbox.confirm("Tem certeza que deseja "+acao+"?", function(result) {
            if(result) {
                $.post(baseUrl + '/familia-produto/inativo', {
                    codfamiliaproduto: codfamiliaproduto,
                    acao: acao,
                    _token: token
                }).done(function (data) {
                    location.reload();
                }).fail(function (error){
                  location.reload();          
              });
            }  
        });
    });

});
</script>
@endsection
@stop