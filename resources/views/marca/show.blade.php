@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="{{ url('marca') }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>             
            <li><a href="{{ url('marca/create') }}"><span class="glyphicon glyphicon-plus"></span> Novo</a></li>             
            <li><a href="{{ url("marca/$model->codmarca/edit") }}"><span class="glyphicon glyphicon-pencil"></span> Alterar</a></li> 
            <li>
                @if(empty($model->inativo))
                <a href="" id="inativo-marca">
                    <span class="glyphicon glyphicon-ban-circle"></span> Inativar
                </a>
                @else
                <a href="" id="inativo-marca">
                    <span class="glyphicon glyphicon-ok-sign"></span> Ativar
                </a>
                @endif
            </li>
            <li>
                <a href="{{ url("marca/$model->codmarca") }}" data-excluir data-pergunta="Tem certeza que deseja excluir a Marca '{{ $model->marca }}'?" data-after-delete="location.replace(baseUrl + '/marca');"><i class="glyphicon glyphicon-trash"></i> Excluir</a>
            </li>
        </ul>
    </div>
</nav>
<h1 class="header">
    {!! 
        titulo(
            $model->codmarca,
            $model->marca,
            $model->inativo
        ) 
    !!} 
    <div class="pull-right foto-item-unico">
        @if(empty($model->codimagem))
            <a class="btn btn-default carregar" href="{{ url("/imagem/edit?id=$model->codmarca&model=Marca") }}">
                <i class="glyphicon glyphicon-picture"></i>
                Carregar imagem
            </a>
        @else
        <a href="{{ url("imagem/{$model->Imagem->codimagem}") }}">
            <img class="img-responsive pull-right" src='<?php echo URL::asset('public/imagens/'.$model->Imagem->observacoes);?>'>
        </a>
        <span class="caption simple-caption">
            <a href="{{ url("/imagem/edit?id=$model->codmarca&model=Marca") }}" class="btn btn-default btn-sm"><i class="glyphicon glyphicon-pencil"></i> Alterar</a>
        </span>        
        @endif
    </div>
</h1>
@include('includes.autor')

@section('inscript')
<script type="text/javascript">
function atualizaFiltro()
{
    scroll();
    var frmValues = $("#familia-produto-search").serialize();
    $.ajax({
        type: 'GET',
        url: baseUrl + '/marca/'+ {{$model->codmarca}},
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
    $("#familia-produto-search").on("change", function (event) {
        $('#items').infinitescroll('destroy');
        atualizaFiltro();
    }).on('submit', function (event){
        event.preventDefault();
        $('#items').infinitescroll('destroy');
        atualizaFiltro();
    });        
    
    $('#inativo-marca').on("click", function(e) {
        e.preventDefault();
        var codmarca = {{ $model->codmarca }};
        var token = '{{ csrf_token() }}';
        var inativo = '{{ $model->inativo }}';
        if(inativo.length === 0) {
            acao = 'inativar';
        } else {
            acao = 'ativar';
        }        
        bootbox.confirm("Tem certeza que deseja "+acao+"?", function(result) {
            if(result) {
                $.post(baseUrl + '/marca/inativo', {
                    codmarca: codmarca,
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