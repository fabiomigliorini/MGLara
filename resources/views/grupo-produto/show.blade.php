@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="{{ url("familia-produto/$model->codfamiliaproduto") }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>             
            <li><a href="{{ url("grupo-produto/create?codfamiliaproduto=$model->codfamiliaproduto") }}"><span class="glyphicon glyphicon-plus"></span> Novo</a></li>             
            <li><a href="{{ url("grupo-produto/$model->codgrupoproduto/edit") }}"><span class="glyphicon glyphicon-pencil"></span> Alterar</a></li> 
            <li>
                @if(empty($model->inativo))
                <a href="" id="inativo-grupo-produto">
                    <span class="glyphicon glyphicon-ban-circle"></span> Inativar
                </a>
                @else
                <a href="" id="inativo-grupo-produto">
                    <span class="glyphicon glyphicon-ok-sign"></span> Ativar
                </a>
                @endif
            </li>
            <li>
                <a href="{{ url("grupo-produto/$model->codgrupoproduto") }}" data-excluir data-pergunta="Tem certeza que deseja excluir o Grupo '{{ $model->grupoproduto }}'?" data-after-delete="location.replace(baseUrl + '/familia-produto/{{$model->codfamiliaproduto}}');"><i class="glyphicon glyphicon-trash"></i> Excluir</a>
            </li>            
        </ul>
    </div>
</nav>
<ol class="breadcrumb header">
{!! 
    titulo(
        $model->codgrupoproduto,
        [
            url("secao-produto/{$model->FamiliaProduto->SecaoProduto->codsecaoproduto}") => $model->FamiliaProduto->SecaoProduto->secaoproduto,
            url("familia-produto/$model->codfamiliaproduto") => $model->FamiliaProduto->familiaproduto,
            $model->grupoproduto,
        ],
        $model->inativo
    ) 
!!}
    <a class="btn btn-primary pull-right" role="button" data-toggle="collapse" href="#div-filtro" aria-expanded="false" aria-controls="div-filtro">
        <span class='glyphicon glyphicon-search'></span>
    </a>   
    <div class="pull-right foto-item-unico">
        @if(empty($model->codimagem))
            <a class="btn btn-default carregar" href="{{ url("/imagem/edit?id=$model->codgrupoproduto&model=GrupoProduto") }}">
                <i class="glyphicon glyphicon-picture"></i>
                Carregar imagem
            </a>
        @else
        <a href="{{ url("imagem/{$model->Imagem->codimagem}") }}">
            <img class="img-responsive pull-right" src='<?php echo URL::asset('public/imagens/'.$model->Imagem->observacoes);?>'>
        </a>
        <span class="caption simple-caption">
            <a href="{{ url("/imagem/$model->codgrupoproduto/delete/?model=GrupoProduto&imagem={$model->Imagem->codimagem}") }}" class="btn btn-default btn-sm"><i class="glyphicon glyphicon-trash"></i> Excluir</a>          
            <a href="{{ url("/imagem/edit?id=$model->codgrupoproduto&model=GrupoProduto") }}" class="btn btn-default btn-sm"><i class="glyphicon glyphicon-pencil"></i> Alterar</a>
        </span>        
        @endif
    </div>
</ol>
@include('includes.autor')
<div class="clearfix">
    <a class="btn btn-default" href="{{ url("sub-grupo-produto/create?codgrupoproduto=$model->codgrupoproduto") }}">
    <i class=" glyphicon glyphicon-plus"></i> Novo Sub Grupo</a>
</div>
<div class='collapse' id='div-filtro'>
    <div class='well well-sm' style="padding:9px 0">
        {!! Form::model(
            Request::session()->get('grupo-produto.show'),
            [
                'route' => 'grupo-produto.show', 
                'method' => 'GET', 
                'class' => 'form-horizontal', 
                'id' => 'sub-grupo-produto-search', 
                'role' => 'search', 
                'autocomplete' => 'off'
            ]
        )!!}
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('subgrupoproduto', 'Sub Grupo', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-9">{!! Form::text('subgrupoproduto', null, ['class' => 'form-control', 'placeholder' => 'Sub Grupo']) !!}</div>
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
    @foreach($subgrupos as $row)
      <div class="list-group-item @if(!empty($row->inativo)) bg-danger @endif">
        <div class="row item">
            <div class="col-md-1">
                <a class="small text-muted" href="{{ url("sub-grupo-produto/$row->codsubgrupoproduto") }}">{{ formataCodigo($row->codsubgrupoproduto) }}</a>
            </div>                            
            <div class="col-md-7">
                <a href="{{ url("sub-grupo-produto/$row->codsubgrupoproduto") }}">
                    {!! listagemTitulo($row->subgrupoproduto, $row->inativo) !!}
                </a>
            </div>
            <div class="col-md-2">
                {!! inativo($row->inativo) !!}
            </div>
            <div class="col-md-2">
            @if(!empty($row->codimagem))
                <div class="pull-right foto-item-listagem">
                    <img class="img-responsive pull-right" alt="{{$row->subgrupoproduto}}" title="{{$row->subgrupoproduto}}" src='<?php echo URL::asset('public/imagens/'.$row->Imagem->observacoes);?>'>
                </div>
            @endif                 
            </div>
        </div>
      </div>    
    @endforeach
    @if (count($subgrupos) === 0)
        <h3>Nenhum Sub Grupo encontrado!</h3>
    @endif    
  </div>
  {!! $subgrupos->appends(Request::session()->get('grupo-produto.show'))->render() !!}
</div>
@section('inscript')
<script type="text/javascript">
function atualizaFiltro()
{
    scroll();
    var frmValues = $("#sub-grupo-produto-search").serialize();
    $.ajax({
        type: 'GET',
        url: baseUrl + '/grupo-produto/'+ {{$model->codgrupoproduto}},
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
    $("#sub-grupo-produto-search").on("change", function (event) {
        $('#items').infinitescroll('destroy');
        atualizaFiltro();
    }).on('submit', function (event){
        event.preventDefault();
        $('#items').infinitescroll('destroy');
        atualizaFiltro();
    });        
    
    $('#inativo-grupo-produto').on("click", function(e) {
        e.preventDefault();
        var codgrupoproduto = {{ $model->codgrupoproduto }};
        var token = '{{ csrf_token() }}';
        var inativo = '{{ $model->inativo }}';
        if(inativo.length === 0) {
            acao = 'inativar';
        } else {
            acao = 'ativar';
        }        
        bootbox.confirm("Tem certeza que deseja "+acao+"?", function(result) {
            if(result) {
                $.post(baseUrl + '/grupo-produto/inativo', {
                    codgrupoproduto: codgrupoproduto,
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