@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
    {!! 
        titulo(
            $model->codmarca,
            [
                url("marca") => 'Marcas',
                $model->marca,
            ],
            $model->inativo
        ) 
    !!} 
    <li class='active'>
        <small>
            <a title="Novo" href="{{ url('marca/create') }}"><i class="glyphicon glyphicon-plus"></i></a>
            &nbsp;
            <a title="Alterar" href="{{ url("marca/$model->codmarca/edit") }}"><i class="glyphicon glyphicon-pencil"></i></a>
            &nbsp;
            @if(empty($model->inativo))
            <a title="Inativar" href="" id="inativo-marca"><i class="glyphicon glyphicon-ban-circle"></i></a>
            &nbsp;
            @else
            <a title="Ativar" href="" id="inativo-marca"><i class="glyphicon glyphicon-ok-sign"></i></a>
            &nbsp;
            @endif
            <a title="Excluir" href="{{ url("marca/$model->codmarca") }}" data-excluir data-pergunta="Tem certeza que deseja excluir a Marca '{{ $model->marca }}'?" data-after-delete="location.replace(baseUrl + '/marca');"><i class="glyphicon glyphicon-trash"></i></a>
        </small>
    </li>   
</ol>
@include('includes.autor')
<div class="row">
    <div class="col-lg-9 col-sm-8">
        <table class="detail-view table table-striped table-condensed"> 
          <tbody>  
            <tr> 
              <th class="col-md-2">Descrição site</th> 
              <td class="col-md-10">{{ $model->observacoes }}</td> 
            </tr>
          </tbody> 
        </table>
    </div>
    <div class="col-lg-3 col-sm-4">
        <div class="panel panel-default">
            <div class="panel-body">
                @if($model->codimagem)
                <div class="text-right">
                    <a href="{{ url("/imagem/$model->codmarca/delete/?model=Marca&imagem={$model->Imagem->codimagem}") }}" class="btn btn-default btn-sm"><i class="glyphicon glyphicon-trash"></i> Excluir</a>
                    <a href="{{ url("/imagem/edit?id=$model->codmarca&model=Marca") }}" class="btn btn-default btn-sm"><i class="glyphicon glyphicon-pencil"></i> Alterar</a>
                </div>        
                <a href="{{ url("imagem/{$model->Imagem->codimagem}") }}">
                    <img class="img-responsive pull-right" src='<?php echo URL::asset('public/imagens/'.$model->Imagem->observacoes);?>'>
                </a>
                @else
                <a title="Carregar imagem" href="{{ url("/imagem/edit?id=$model->codmarca&model=Marca") }}">
                    <i class="glyphicon glyphicon-picture"></i>
                    Cadastrar imagem
                </a>
                @endif
            </div>
        </div>
    </div>    
</div>
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
                $.post(baseUrl + '/marca/inativar', {
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