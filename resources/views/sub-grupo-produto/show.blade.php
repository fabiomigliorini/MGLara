@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="{{ url("grupo-produto/$model->codgrupoproduto") }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>             
            <li><a href="{{ url("sub-grupo-produto/create?codgrupoproduto=$model->codgrupoproduto") }}"><span class="glyphicon glyphicon-plus"></span> Novo</a></li>             
            <li><a href="{{ url("sub-grupo-produto/$model->codsubgrupoproduto/edit") }}"><span class="glyphicon glyphicon-pencil"></span> Alterar</a></li> 
            <li>
                @if(empty($model->inativo))
                <a href="" id="inativo-sub-grupo-produto">
                    <span class="glyphicon glyphicon-ban-circle"></span> Inativar
                </a>
                @else
                <a href="" id="inativo-sub-grupo-produto">
                    <span class="glyphicon glyphicon-ok-sign"></span> Ativar
                </a>
                @endif
            </li>
            <li>
                <a href="{{ url("sub-grupo-produto/$model->codsubgrupoproduto") }}" data-excluir data-pergunta="Tem certeza que deseja excluir o Sub Grupo '{{ $model->subgrupoproduto }}'?" data-after-delete="location.replace(baseUrl + '/grupo-produto/{{$model->codgrupoproduto}}');"><i class="glyphicon glyphicon-trash"></i> Excluir</a>
            </li>            
        </ul>
    </div>
</nav>
<h1 class="header">
    {!! 
        titulo(
            $model->codgrupoproduto,
            [
                url("secao-produto/{$model->GrupoProduto->FamiliaProduto->SecaoProduto->codsecaoproduto}") => $model->GrupoProduto->FamiliaProduto->SecaoProduto->secaoproduto,
                url("familia-produto/{$model->GrupoProduto->FamiliaProduto->codfamiliaproduto}") => $model->GrupoProduto->FamiliaProduto->familiaproduto,
                url("grupo-produto/$model->codgrupoproduto") => $model->GrupoProduto->grupoproduto,
                $model->subgrupoproduto,
            ],
            $model->inativo
        ) 
    !!} 
    <a class="btn btn-primary pull-right" role="button" data-toggle="collapse" href="#div-filtro" aria-expanded="false" aria-controls="div-filtro">
        <span class='glyphicon glyphicon-search'></span>
    </a> 
    <div class="pull-right foto-item-unico">
        @if(empty($model->codimagem))
            <a class="btn btn-default carregar" href="{{ url("/imagem/edit?id=$model->codsubgrupoproduto&model=SubGrupoProduto") }}">
                <i class="glyphicon glyphicon-picture"></i>
                Carregar imagem
            </a>
        @else
        <a href="{{ url("imagem/{$model->Imagem->codimagem}") }}">
            <img class="img-responsive pull-right" src='<?php echo URL::asset('public/imagens/'.$model->Imagem->observacoes);?>'>
        </a>
        <span class="caption simple-caption">
            <a href="{{ url("/imagem/edit?id=$model->codsubgrupoproduto&model=SubGrupoProduto") }}" class="btn btn-default btn-sm"><i class="glyphicon glyphicon-pencil"></i> Alterar</a>
        </span>        
        @endif
    </div>
</h1>
@include('includes.autor')
<div class="clearfix"></div>
<div class='collapse' id='div-filtro'>
    <div class='well well-sm' style="padding:9px 0">
        {!! Form::model(
            Request::session()->get('sub-grupo-produto.show'),
            [
                'route' => 'grupo-produto.show', 
                'method' => 'GET', 
                'class' => 'form-horizontal', 
                'id' => 'produto-search', 
                'role' => 'search', 
                'autocomplete' => 'off'
            ]
        )!!}
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('produto', 'Produto', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-9">{!! Form::text('produto', null, ['class' => 'form-control', 'placeholder' => 'Produto']) !!}</div>
            </div>
        </div>
        
        <div class="col-md-3">    
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
    @foreach($produtos as $row)
      <div class="list-group-item @if(!empty($row->inativo)) bg-danger @endif">
        <div class="row item">
            <div class="col-md-1">
                <a class="small text-muted" href="{{ url("produto/$row->codproduto") }}">{{ formataCodigo($row->codproduto) }}</a>
            </div>                            
            <div class="col-md-9">
                <a href="{{ url("produto/$row->codproduto") }}">
                    {!! listagemTitulo($row->produto, $row->inativo) !!}
                </a>
            </div>
            <div class="col-md-2">
                {!! inativo($row->inativo) !!}
            </div>
        </div>
      </div>    
    @endforeach
    @if (count($produtos) === 0)
        <h3>Nenhum Produto encontrado!</h3>
    @endif    
  </div>
  {!! $produtos->appends(Request::session()->get('sub-grupo-produto.show'))->render() !!}
</div>
@section('inscript')
<script type="text/javascript">
function atualizaFiltro()
{
    scroll();
    var frmValues = $("#produto-search").serialize();
    $.ajax({
        type: 'GET',
        url: baseUrl + '/sub-grupo-produto/'+ {{$model->codsubgrupoproduto}},
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
    $("#produto-search").on("change", function (event) {
        $('#items').infinitescroll('destroy');
        atualizaFiltro();
    }).on('submit', function (event){
        event.preventDefault();
        $('#items').infinitescroll('destroy');
        atualizaFiltro();
    });        
    
    $('#inativo-sub-grupo-produto').on("click", function(e) {
        e.preventDefault();
        var codsubgrupoproduto = {{ $model->codsubgrupoproduto }};
        var token = '{{ csrf_token() }}';
        var inativo = '{{ $model->inativo }}';
        if(inativo.length === 0) {
            acao = 'inativar';
        } else {
            acao = 'ativar';
        }        
        bootbox.confirm("Tem certeza que deseja "+acao+"?", function(result) {
            if(result) {
                $.post(baseUrl + '/sub-grupo-produto/inativo', {
                    codsubgrupoproduto: codsubgrupoproduto,
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