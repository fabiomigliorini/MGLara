@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="<?php echo url('produto');?>"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>             
            <li><a href="<?php echo url('produto/create');?>"><span class="glyphicon glyphicon-plus"></span> Novo</a></li>             
            <li><a href="<?php echo url("produto/$model->codproduto/edit");?>"><span class="glyphicon glyphicon-pencil"></span> Alterar</a></li> 
            <li><a href="<?php echo url("produto/$model->codproduto/juntar-barras");?>"><span class="glyphicon glyphicon-resize-small"></span> Juntar códigosde barra</a></li> 
            <li><a href="<?php echo url("produto/$model->codproduto/transferir-barras");?>"><span class="glyphicon glyphicon-transfer"></span> Transferir códigos de barra</a></li> 
            <li>
                {!! Form::open(['method' => 'DELETE', 'route' => ['produto.destroy', $model->codproduto]]) !!}
                <span class="glyphicon glyphicon-trash"></span>
                {!! Form::submit('Excluir') !!}
                {!! Form::close() !!}
            </li>
        </ul>
    </div>
</nav>

<br>
<div class="row">
    <div class="col-md-5">
        {!! Form::model(Request::all(), [
          'method' => 'POST', 
          'class' => 'form-inline',
          'id' => 'produto-busca-barras',
          'role' => 'search'
        ])!!}        
        <div class="form-group" style="width: 100%">
            <div class="input-group" style="width: 100%">
                <input type="text" name="" class="form-control text-right" id="barras">
                <div class="input-group-addon"><i class="glyphicon glyphicon-search"></i></div>
            </div>
        </div>            
        {!! Form::close() !!}
    </div>
    <div class="col-md-7">
        {!! Form::model(Request::all(), ['route' => 'produto.show', 'method' => 'GET', 'class' => 'form-inline', 'id' => 'produto-show-search', 'role' => 'search', 'autocomplete' => 'off'])!!}
            {!! Form::text('codproduto', null, ['class' => 'form-control', 'id'=> 'codproduto', 'style'=> 'width: 100%;']) !!}
        {!! Form::close() !!}
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-7">
        <div class="panel panel-warning">
            <div class="panel-body bg-warning">
                <h1 class="text-danger produtos-detalhes-produto">
                    {{ $model->produto}} {{ app('request')->input('v') }}
                    <span class="pull-right text-muted">{{ $model->UnidadeMedida->unidademedida }}</span>
                </h1>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <p class="mz"><strong>Código</strong></p>
                        {{ formataCodigo($model->codproduto, 6) }}
                    </div>
                    <div class="col-md-4">
                        <p class="mz"><strong>Marca</strong></p>
                        {{ $model->Marca->marca or '' }}
                    </div>
                    <div class="col-md-4">
                        <p class="mz"><strong>Referência</strong></p>
                        {{ $model->referencia }}
                    </div>
                </div>
            </div>
        </div>        
        <div class="panel panel-info produtos-detalhe-carousel">
            <div class="pull-right carousel-menu">
                <a class="btn btn-default" href="{{ url("/imagem/produto/$model->codproduto") }}">
                    <i class="glyphicon glyphicon-picture"></i> 
                    Nova
                </a>
                @if(count ($model->ImagemS) > 0)
                <a class="btn btn-default btn-detalhe" href="{{ url("imagem/produto/$model->codproduto?imagem={$model->ImagemS->first()->codimagem}") }}">
                    <i class="glyphicon glyphicon-pencil"></i> 
                    Alterar
                </a>
                <a class="btn btn-default btn-delete" href="{{ url("imagem/produto/$model->codproduto/delete?imagem={$model->ImagemS->first()->codimagem}") }}">
                    <i class="glyphicon glyphicon-trash"></i> 
                    Excluir 
                </a>
                @endif
            </div>
            <div class="panel-body">
                @if(count ($model->ImagemS) > 0)
                    @include('produto.carousel')
                @endif
            </div>
        </div>        
    </div>
    <div class="col-md-5">
        <div class="panel panel-success">
            <div class="panel-body bg-success">
                <h2 class="produtos-detalhe-preco text-right pull-right text-success">{{ formataNumero($model->preco) }}</h2>
                <span class="text-muted text-left pull-left produtos-detalhe-cifrao">R$</span>
            </div>
        </div> 
        <div id="produto-detalhes">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#tab-produto-combinacoes" aria-controls="home" role="tab" data-toggle="tab">Combinações</a></li>
                <li role="presentation"><a href="#tab-produto-fiscal" aria-controls="profile" role="tab" data-toggle="tab">Fiscal</a></li>
                <li role="presentation"><a href="#tab-produto-notasfiscais" aria-controls="messages" role="tab" data-toggle="tab">Notas fiscais</a></li>
                <li role="presentation"><a href="#tab-produto-negocios" aria-controls="messages" role="tab" data-toggle="tab">Negócios</a></li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="tab-produto-combinacoes">
                    <div class="panel panel-info combinacoes">
                        <ul class="list-group bg-info">
                            <li class="list-group-item">
                                <strong>Códigos de barra</strong>
                                <span class="pull-right"><a href="{{ url("produto-barra/create?codproduto={$model->codproduto}") }}">Novo</a></span>
                            </li>
                            
                            @foreach($model->ProdutoBarraS as $pb)
                            <li class="list-group-item">
                                <div class="row item">
                                    <div class="col-md-3">
                                        
                                    </div>
                                    <div class="col-md-3">
                                        {{ $pb->barras }}
                                    </div>
                                    <div class="col-md-3">
                                        {{ $pb->variacao }}
                                    </div>
                                    <div class="col-md-3">
                                        
                                    </div>
                                </div>
                            </li>
                            @endforeach

                            <li class="list-group-item">
                                <strong>Embalagens</strong>
                                <span class="pull-right"><a href="{{ url("produto-embalagem/create?codproduto={$model->codproduto}") }}">Novo</a></span>
                            </li>
                            @foreach($model->ProdutoEmbalagemS as $pe)
                            <li class="list-group-item">
                                <div class="row item">            
                                    <div class="col-md-4">
                                        {{ $pe->UnidadeMedida->unidademedida }}
                                    </div>                            
                                    <div class="col-md-4">
                                        R$ {{ $pe->preco}}
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row-fluid">
                                            <span class="pull-right">
                                                <a href="{{ url("produto-embalagem/$pe->codprodutoembalagem/edit") }}"><i class="glyphicon glyphicon-pencil text-danger"></i></a>
                                                &nbsp;&nbsp;
                                                <a href=""><i class="glyphicon glyphicon-trash text-danger"></i></a>
                                            </span>                                                                                
                                        </div>
                                    </div>      
                                </div>    
                            </li>            
                            @endforeach        
                        </ul>                
                    </div>            
                </div>
                <div role="tabpanel" class="tab-pane fade" id="tab-produto-fiscal">
                    @include('produto.fiscal')
                </div>
                <div role="tabpanel" class="tab-pane fade" id="tab-produto-notasfiscais">
                    @include('produto.notasfiscais')
                </div>
                <div role="tabpanel" class="tab-pane fade" id="tab-produto-negocios">
                    @include('produto.negocios')
                </div>
            </div>
        </div>        
 
	<?php

	$arr_saldos = [];
        $arr_totais = [true => 0, false => 0];
	foreach ($model->EstoqueSaldoS as $es)
	{
            $arr_saldos[$es->EstoqueLocal->estoquelocal][$es->fiscal] = array(
                "saldoquantidade" => $es->saldoquantidade,
                "codestoquesaldo" => $es->codestoquesaldo,
            );
            $arr_totais[$es->fiscal] += $es->saldoquantidade;
            }
	?>
        
        <div class='panel panel-info'>
            <div class="panel-heading">
                    <div class="row item">
                        <div class="col-md-6">Estoque</div>
                        <div class="col-md-3 text-right">Físico</div>
                        <div class="col-md-3 text-right">Fiscal</div>
                    </div>
            </div>            
            <ul class="list-group bg-infoo">
            @foreach($arr_saldos as $estoquelocal => $saldo)
                <li class="list-group-item">
                    <div class="row item">            
                        <div class="col-md-6">
                            {{ $estoquelocal }}
                        </div>

                        <div class="col-md-3 text-right">
                            @if(isset($saldo[false]))
                            <a href='{{ url("estoque-saldo/{$saldo[false]['codestoquesaldo']}") }}'>
                                {{ formataNumero($saldo[false]['saldoquantidade'], 0) }}
                            </a>
                            @endif
                        </div>
                
                        <div class="col-md-3 text-right">
                            @if(isset($saldo[true]))
                            <a href='{{ url("estoque-saldo/{$saldo[true]['codestoquesaldo']}") }}'>
                                {{ formataNumero($saldo[true]['saldoquantidade'], 0) }}
                            </a>
                            @endif
                        </div>
                    </div>            
                </li>
            @endforeach    
                <li class="list-group-item">
                    <div class="row item">            
                        <div class="col-md-6">
                            Total
                        </div>

                        <div class="col-md-3 text-right">
                            {{ formataNumero($arr_totais[false], 0) }}
                        </div>
                
                        <div class="col-md-3 text-right">
                            {{ formataNumero($arr_totais[true], 0) }}
                        </div>
                    </div>            
                </li>
            </ul>
        </div>
        
    </div>    
</div>
<hr>
@include('includes.autor')
<hr>
<br>
@section('inscript')
<script type="text/javascript">
$(document).ready(function() {
    $('#codproduto').change(function (){
        window.location.href = '{{ url("produto/") }}' + $('#codproduto').val();
    });
    $('#codproduto').select2({
        minimumInputLength: 3,
        allowClear: true,
        closeOnSelect: true,
        placeholder: 'Pesquisa de produtos',
        formatResult:function(item) {
            var markup = "<div class='row'>";
            markup    += "<small class='text-muted col-md-2'> <small>#" /*+ item.barras + "<br>"*/ + item.id + "</small></small>";
            markup    += "<div class='col-md-8'>" + item.produto + "<small class='muted text-right pull-right'></small></div>";
            markup    += "<div><div class='col-md-8 text-right pull-right'><small class='span1 text-muted'></small>" + item.preco + "";
            markup    += "</div></div>";
            markup    += "</div>";
            return markup;
        },
        formatSelection:function(item) { 
            return item.produto + " - " + item.preco; 
        },
        ajax: {
            url: baseUrl+'/produto/ajax',
            dataType: 'json',
            quietMillis: 500,
            data: function(term, current_page) { 
                return {
                    q: term, 
                    per_page: 10, 
                    current_page: current_page
                }; 
            },
            results:function(data,page) {
                //var more = (current_page * 20) < data.total;
                return {
                    results: data, 
                    //more: data.mais
                };
            }
        },
        initSelection: function (element, callback) {
            $.ajax({
                type: "GET",
                url: baseUrl+'/produto/ajax',
                data: "id=",
                dataType: "json",
                success: function(result) { 
                    callback(result[0]); 
                }
            });
        },
        width:'resolve'
    });
    
    
    $('.carousel-inner .item').first().addClass('active');
    $('.carousel').carousel({
        interval:5000
    });
    $('.carousel').on('slid.bs.carousel', function (e) {
        var imagem = $(e.target).find('.active > img').attr('id');
        var produto = {{ $model->codproduto }};
        //$('.btn-detalhe').attr('href', baseUrl+'/imagem/'+imagem);
        $('.btn-detalhe').attr('href', baseUrl+'/imagem/produto/' +produto+ '?imagem=' + imagem);
        $('.btn-delete').attr('href', baseUrl+'/imagem/produto/' +produto+ '/delete?imagem=' + imagem);
    })    
    $('.btn-detalhe, .btn-delete').on('mouseenter', function() {
       $(".carousel").carousel('pause');
    });
    $('.btn-detalhe, .btn-delete').on('mouseleave', function() {
       $(".carousel").carousel('cycle');
    });
    
    $('.btn-delete').click(function (e) {
        e.preventDefault();
        var url = $('.btn-delete').attr('href');
        bootbox.confirm("Tem certeza que deseja deletar essa imagem", function(result) {
            if (result) {
                window.location.href = url;
            }
        }); 
    });
    
    $('#produto-busca-barras').on('submit', function(e) {
        e.preventDefault();
        $.post(baseUrl + '/produto/busca-barras', {
            barras: $('#barras').val(),
            _token: '{{ csrf_token() }}'
        }).done(function(data) {
            if(data.length > 0) {
                var codproduto = JSON.stringify(data[0].codproduto);
                var variacao = JSON.stringify(data[0].variacao).replace('"', '').replace('"', '');
                window.location.href = '{{ url('produto') }}/' + codproduto + '?v=' + variacao
            } else {
                alert( "Nenhum produto encontrado" );
            }
        }).fail(function() {
            alert( "Erro ao procurar produto" );
        });
    });
    
});
</script>
@endsection
@stop
