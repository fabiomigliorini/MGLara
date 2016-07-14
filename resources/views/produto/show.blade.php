@extends('layouts.default')
@section('content')
<?php
    use MGLara\Models\Filial;
    use MGLara\Models\NaturezaOperacao;
    
    $filiais    = [''=>''] + Filial::lists('filial', 'codfilial')->all();
    $naturezaop = [''=>''] + NaturezaOperacao::lists('naturezaoperacao', 'codnaturezaoperacao')->all();
    //dd($model->ProdutoVariacaoS);
?>

<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="<?php echo url('produto');?>"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>             
            
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-plus"></span> Novo <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo url('produto/create');?>">Produto</a></li>             
                    <li><a href="<?php echo url("produto-embalagem/create?codproduto={$model->codproduto}");?>">Embalagem</a></li>             
                    <li><a href="<?php echo url("produto-variacao/create?codproduto={$model->codproduto}");?>">Variação</a></li>             
                    <li><a href="<?php echo url("produto-barra/create?codproduto={$model->codproduto}");?>">Código de Barras</a></li>             
                </ul>
            </li>
        
            <li><a href="<?php echo url("produto/create/?duplicar={$model->codproduto}");?>"><span class="glyphicon glyphicon-duplicate"></span> Duplicar</a></li> 
            <li><a href="<?php echo url("produto/$model->codproduto/edit");?>"><span class="glyphicon glyphicon-pencil"></span> Alterar</a></li> 
            <!--
            <li><a href="<?php echo url("produto/$model->codproduto/juntar-barras");?>"><span class="glyphicon glyphicon-resize-small"></span> Juntar códigosde barra</a></li> 
            <li><a href="<?php echo url("produto/$model->codproduto/transferir-barras");?>"><span class="glyphicon glyphicon-transfer"></span> Transferir códigos de barra</a></li> 
            -->
            <li>
                @if(empty($model->inativo))
                <a href="" id="inativar-produto">
                    <span class="glyphicon glyphicon-ban-circle"></span> Inativar
                </a>
                @else
                <a href="" id="inativar-produto">
                    <span class="glyphicon glyphicon-ok-sign"></span> Ativar
                </a>
                @endif
            </li> 
            <li>
                <a href="{{ url("produto/$model->codproduto") }}" data-excluir data-pergunta="Tem certeza que deseja excluir o produto '{{ $model->produto }}'?" data-after-delete="location.replace(baseUrl + '/produto');"><i class="glyphicon glyphicon-trash"></i> Excluir</a>
            </li>
        </ul>
    </div>
</nav>
<!--
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
-->
<div class="panel panel-default">
    <table class="table table-bordered table-responsive">
        <tr>
            <td class="col-md-8 bg-warning" style="vertical-align: middle">
                <h2 class="text-danger produtos-detalhes-produto">
                    {!! titulo($model->codproduto, $model->produto, $model->inativo, 6) !!}
                </h2>
                <div class="pull-left">
                    {!! 
                        titulo(
                            NULL, 
                            [
                                url("secao-produto/{$model->SubGrupoProduto->GrupoProduto->FamiliaProduto->codsecaoproduto}") => $model->SubGrupoProduto->GrupoProduto->FamiliaProduto->SecaoProduto->secaoproduto,
                                url("familia-produto/{$model->SubGrupoProduto->GrupoProduto->codfamiliaproduto}") => $model->SubGrupoProduto->GrupoProduto->FamiliaProduto->familiaproduto,
                                url("grupo-produto/{$model->SubGrupoProduto->codgrupoproduto}") => $model->SubGrupoProduto->GrupoProduto->grupoproduto,
                                url("sub-grupo-produto/{$model->codsubgrupoproduto}") => $model->SubGrupoProduto->subgrupoproduto,
                                url("marca/{$model->codmarca}") => $model->Marca->marca,
                                $model->referencia,
                            ], 
                            NULL) 
                    !!}
                </div>
                <div class="pull-right">
                    <?php 
                    $arr = [            
                        url("tipo-produto/{$model->codtipoproduto}") => $model->TipoProduto->tipoproduto,
                        url("ncm/{$model->codncm}") => formataNcm($model->Ncm->ncm),
                        url("tributacao/{$model->codtributacao}") => $model->Tributacao->tributacao,
                    ];

                    if (!empty($model->codcest))
                        $arr[url("cest/{$model->codcest}")] = formataCest($model->Cest->cest);

                    $arr[] = ($model->importado)?'Importado':'Nacional';

                    ?>
                    {!! 
                        titulo(NULL, $arr, NULL) 
                    !!}
                </div>


            </td>
            <td class="col-md-4 bg-success" style="vertical-align: middle">
                <div class="col-md-12">
                    <h2 class="produtos-detalhe-preco text-right text-success col-md-7">
                        <span class="pull-left text-muted produtos-detalhe-cifrao">R$ &nbsp; </span>
                        {{ formataNumero($model->preco) }}
                    </h2>
                    <span class="text-muted col-md-5">
                        {{ $model->UnidadeMedida->unidademedida }}
                    </span>
                </div>
                @include('produto.show-embalagens')
            </td>
        </tr>
    </table>
</div>
<div class="row">
    <div class="col-md-6">
        <!--FOTOS -->
        <div class="panel panel-default produtos-detalhe-carousel">
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
        <!--/ FOTOS -->
    </div>
    <div class="col-md-6">
        <div>
            <ul class="nav nav-pills" role="tablist" id='tab-produto'>
                <li role="presentation" class='active'><a href="#tab-variacoes" aria-controls="home" role="tab" data-toggle="tab">Variações</a></li>
                <li role="presentation"><a href="#tab-estoque" aria-controls="home" role="tab" data-toggle="tab">Estoque</a></li>
                <li role="presentation"><a href="#tab-site" aria-controls="profile" role="tab" data-toggle="tab">Site</a></li>
                <li role="presentation"><a href="#tab-fiscal" aria-controls="profile" role="tab" data-toggle="tab">NCM</a></li>
                <li role="presentation"><a href="#tab-negocio" aria-controls="messages" role="tab" data-toggle="tab">Negócios</a></li>
                <li role="presentation"><a href="#tab-notasfiscais" aria-controls="messages" role="tab" data-toggle="tab">Notas Fiscais</a></li>
            </ul>
            <br>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="tab-variacoes">
                    @include('produto.show-variacoes')
                </div>
                <div role="tabpanel" class="tab-pane fade" id="tab-estoque">
                    @include('estoque-saldo.resumo-produto', ['codproduto' => $model->codproduto, 'somentequantidade' => true])
                </div>
                <div role="tabpanel" class="tab-pane fade" id="tab-site">
                    <br>
                    <strong>Divulgado no Site: {{ ($model->site)?'Sim':'Não' }}</strong>
                    <hr>
                    {{ $model->descricaosite }}
                </div>
                <div role="tabpanel" class="tab-pane fade" id="tab-fiscal">
                    @include('produto.show-ncm')
                </div>
                <div role="tabpanel" class="tab-pane fade" id="tab-negocio">
                    
                    <!-- BOTAO FILTRO -->
                    <div class='clearfix'>
                        <a class="btn btn-primary pull-right" role="button" data-toggle="collapse" href="#filtro-negocio" aria-expanded="false" aria-controls="filtro-negocio">
                            <span class='glyphicon glyphicon-search'></span>
                        </a>
                    </div>
                    
                    <!-- FILTRO NEGOCIO -->
                    <div class="collapse" id="filtro-negocio">
                        <br>
                        <div class='well well-sm'>
                            {!! Form::model(Request::all(), ['route' => ['produto.show', 'produto'=> $model->codproduto], 'class' => 'form-horizontal', 'method' => 'GET', 'id' => 'produto-negocio-search', 'role' => 'search', 'autocomplete' => 'off'])!!}

                                <div class="form-group">
                                    <div class="col-sm-4 control-label">{!! Form::label('negocio_lancamento_de', 'De') !!}</div>
                                    <div class="col-sm-4">{!! Form::date('negocio_lancamento_de', $parametros['negocio_lancamento_de'], ['class' => 'form-control', 'id' => 'negocio_lancamento_de', 'placeholder' => 'De']) !!}</div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-4 control-label">{!! Form::label('negocio_lancamento_ate', 'Até') !!}</div>
                                    <div class="col-sm-4">{!! Form::date('negocio_lancamento_ate', $parametros['negocio_lancamento_de'], ['class' => 'form-control', 'id' => 'negocio_lancamento_ate', 'placeholder' => 'Até']) !!}</div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-4 control-label">{!! Form::label('negocio_codfilial', 'Filial') !!}</div>
                                    <div class="col-sm-4">{!! Form::select2Filial('negocio_codfilial', $parametros['negocio_codfilial'], ['style'=>'width:100%', 'id'=>'negocio_codfilial']) !!}</div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-4 control-label">{!! Form::label('negocio_codnaturezaoperacao', 'Natureza de Operação') !!}</div>
                                    <div class="col-sm-7">{!! Form::select2NaturezaOperacao('negocio_codnaturezaoperacao', $parametros['negocio_codnaturezaoperacao'], ['style'=>'width:100%', 'id' => 'negocio_codnaturezaoperacao']) !!}</div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-4 control-label">{!! Form::label('negocio_codprodutovariacao', 'Variação') !!}</div>
                                    <div class="col-sm-7">{!! Form::select2('negocio_codprodutovariacao', [''=>''] + $model->ProdutoVariacaoS->lists('variacao', 'codprodutovariacao')->all(), $parametros['negocio_codprodutovariacao'], ['style'=>'width:100%', 'id' => 'negocio_codprodutovariacao', 'placeholder'=>'Variaçao']) !!}</div>
                                </div>
                            
                                <div class="form-group">
                                    <div class="col-sm-4 control-label">{!! Form::label('negocio_codproduto', 'Pessoa') !!}</div>
                                    <div class="col-sm-7">{!! Form::select2Pessoa('negocio_codpessoa', null, ['class' => 'form-control','id'=>'negocio_codpessoa', 'style'=>'width:100%', 'placeholder' => 'Pessoa']) !!}</div>
                                </div>                            
                                
                            {!! Form::hidden('_div', 'div-negocios', ['id'=>'negocio_page']) !!}
                                
                            {!! Form::close() !!}
                        </div>
                    </div>

                    <br>
                    @include('produto.show-negocios')
                </div>
                <!-- -->
                <div role="tabpanel" class="tab-pane fade" id="tab-notasfiscais">
                    
                    <!-- BOTAO FILTRO -->
                    <div class='clearfix'>
                        <a class="btn btn-primary pull-right" role="button" data-toggle="collapse" href="#filtro-notasfiscais" aria-expanded="false" aria-controls="filtro-notasfiscais">
                            <span class='glyphicon glyphicon-search'></span>
                        </a>
                    </div>
                    
                    <!-- FILTRO NOTAS FISCAIS -->
                    <div class="collapse" id="filtro-notasfiscais">
                        <br>
                        <div class='well well-sm'>
                            {!! Form::model(Request::all(), ['route' => ['produto.show', 'produto'=> $model->codproduto], 'class' => 'form-horizontal', 'method' => 'GET', 'id' => 'produto-notasfiscais-search', 'role' => 'search', 'autocomplete' => 'off'])!!}

                                <div class="form-group">
                                    <div class="col-sm-4 control-label">{!! Form::label('notasfiscais_lancamento_de', 'De') !!}</div>
                                    <div class="col-sm-4">{!! Form::date('notasfiscais_lancamento_de', $parametros['notasfiscais_lancamento_de'], ['class' => 'form-control', 'id' => 'notasfiscais_lancamento_de', 'placeholder' => 'De']) !!}</div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-4 control-label">{!! Form::label('notasfiscais_lancamento_ate', 'Até') !!}</div>
                                    <div class="col-sm-4">{!! Form::date('notasfiscais_lancamento_ate', $parametros['notasfiscais_lancamento_de'], ['class' => 'form-control', 'id' => 'notasfiscais_lancamento_ate', 'placeholder' => 'Até']) !!}</div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-4 control-label">{!! Form::label('notasfiscais_codfilial', 'Filial') !!}</div>
                                    <div class="col-sm-4">{!! Form::select2Filial('notasfiscais_codfilial', $parametros['notasfiscais_codfilial'], ['style'=>'width:100%', 'id'=>'notasfiscais_codfilial']) !!}</div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-4 control-label">{!! Form::label('notasfiscais_codnaturezaoperacao', 'Natureza de Operação') !!}</div>
                                    <div class="col-sm-7">{!! Form::select2NaturezaOperacao('notasfiscais_codnaturezaoperacao', $parametros['notasfiscais_codnaturezaoperacao'], ['style'=>'width:100%', 'id' => 'notasfiscais_codnaturezaoperacao']) !!}</div>
                                </div>
                            
                                <div class="form-group">
                                    <div class="col-sm-4 control-label">{!! Form::label('notasfiscais_codprodutovariacao', 'Variação') !!}</div>
                                    <div class="col-sm-7">{!! Form::select2('notasfiscais_codprodutovariacao', [''=>''] + $model->ProdutoVariacaoS->lists('variacao', 'codprodutovariacao')->all(), $parametros['negocio_codprodutovariacao'], ['style'=>'width:100%', 'id' => 'notasfiscais_codprodutovariacao', 'placeholder'=>'Variaçao']) !!}</div>
                                </div>
                            
                                <div class="form-group">
                                    <div class="col-sm-4 control-label">{!! Form::label('notasfiscais_codproduto', 'Pessoa') !!}</div>
                                    <div class="col-sm-7">{!! Form::select2Pessoa('notasfiscais_codpessoa', null, ['class' => 'form-control','id'=>'notasfiscais_codpessoa', 'style'=>'width:100%', 'placeholder' => 'Pessoa']) !!}</div>
                                </div>
                            
                                {!! Form::hidden('_div', 'div-notasfiscais', ['id'=>'notasfiscais_page']) !!}
                                
                            {!! Form::close() !!}
                        </div>
                    </div>

                    <br>
                    @include('produto.show-notasfiscais')
                </div>
            </div>
        </div>
    </div>    
</div>
@include('includes.autor')
<br>
<br>
@section('inscript')
<style type="text/css">
.produtos-grid-inativo {
    margin: 0 0 5px 0;
}

.subregistro .col-md-8 {
    margin-bottom: 5px;
}
.produtos-detalhes-produto {
    font-family: sans-serif;
    letter-spacing: -1px;
    margin: 0 0 20px;
    font-size: 28px;
}

.produtos-detalhe-preco {
    font-size: 4em;
    font-weight: bold;
    margin: 0;
}
.produtos-detalhe-preco-menor {
    font-size: 1.5em;
    font-weight: bold;
    margin: 0;
}
.produtos-detalhe-cifrao {
    font-size: 0.5em;
    font-weight: bold;
    margin: 8px 0 0;
}

.produtos-combinacoes-titulo {
    font-size: 22px;
}
.produto-detalhes-unidade {
    font-size: 20px;
}
.bg-info .list-group-item {
    background: none;
}
.produtos-detalhe-carousel {
    min-height: 500px;
}
.carousel-control.left, 
.carousel-control.right {
    background: none;
}
.carousel-menu {
    margin-right: -1px;
    margin-top: -1px;
    position: relative;
    z-index: 100;
}
.produto-historico-preco > .list-group-item {
    padding: 10px 0;
}

</style>

<script type="text/javascript">

function mostraListagemNegocios()
{
    console.log('mostraListagemNegocios');
    
    //Serializa FORM
    var frmValues = $("#produto-negocio-search").serialize();
    
    // Busca Listagem
    $.ajax({
        type: 'GET',
        url: baseUrl + '/produto/' + {{ $model->codproduto }},
        data: frmValues
    })
    .done(function (data) {
        
        //Substitui #div-negocios
        $('#div-negocios').html($(data).html()); 
        
        //Ativa InfiniteScroll
        $('#div-negocios-listagem').infinitescroll({
            loading : {
                finishedMsg: "<div class='end-msg'>Fim dos registros</div>",
                msgText: "<div class='center'>Carregando mais itens...</div>",
                img: baseUrl + '/public/img/ajax-loader.gif'
            },
            navSelector : "#div-negocios .pagination",
            nextSelector : "#div-negocios .pagination li.active + li a",
            itemSelector : "#div-negocios-listagem div.list-group-item"
        });
        
    })
    .fail(function (e) {
        console.log('Erro no filtro');
        console.log(e);
    });
}

function mostraListagemNotasFiscais()
{
    console.log('mostraListagemNotasFiscais');
    
    //Serializa FORM
    var frmValues = $("#produto-notasfiscais-search").serialize();
    
    // Busca Listagem
    $.ajax({
        type: 'GET',
        url: baseUrl + '/produto/' + {{ $model->codproduto }},
        data: frmValues
    })
    .done(function (data) {
        
        $('#div-notasfiscais').html($(data).html()); 
        
        $('#div-notasfiscais-listagem').infinitescroll({
            loading : {
                finishedMsg: "<div class='end-msg'>Fim dos registros</div>",
                msgText: "<div class='center'>Carregando mais itens...</div>",
                img: baseUrl + '/public/img/ajax-loader.gif'
            },
            navSelector : "#div-notasfiscais .pagination",
            nextSelector : "#div-notasfiscais .pagination li.active + li a",
            itemSelector : "#div-notasfiscais-listagem div.list-group-item"
        });
        
    })
    .fail(function (e) {
        console.log('Erro no filtro');
        console.log(e);
    });
}

$(document).ready(function() {

    ////////// LISTAGEM DE NEGOCIOS /////////
    //
    // Listagem de Negocios -- Troca ABA
    var listagemNegocioAberta = false;
    $('a[href="#tab-negocio"]').on('shown.bs.tab', function (e) {
        if (!listagemNegocioAberta)
            mostraListagemNegocios();
        listagemNegocioAberta = true;
    });
    
    // Listagem de Negocios -- Alteração Formulário
    $("#produto-negocio-search").on("change", function (event) {
        mostraListagemNegocios();
        event.preventDefault(); 
    });
    /////////////////////////////////////////
    

    ////////// LISTAGEM DE NOTAS FISCAIS /////////
    //
    // Listagem de Notas Fiscais -- Troca ABA
    var listagemNotasFiscaisAberta = false;
    $('a[href="#tab-notasfiscais"]').on('shown.bs.tab', function (e) {
        if (!listagemNotasFiscaisAberta)
            mostraListagemNotasFiscais();
        listagemNotasFiscaisAberta = true;
    });
    
    // Listagem de Negocios -- Alteração Formulário
    $("#produto-notasfiscais-search").on("change", function (event) {
        mostraListagemNotasFiscais();
        event.preventDefault(); 
    });
    /////////////////////////////////////////
    
    
    $('#codproduto').change(function (){
        window.location.href = '{{ url("produto/") }}' + $('#codproduto').val();
    });
    
    // IMAGENS
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
    
    $('#inativar-produto').on("click", function(e) {
        e.preventDefault();
        var codproduto = {{ $model->codproduto }};
        var token = '{{ csrf_token() }}';
        var inativo = '{{ $model->inativo }}';
        if(inativo.length === 0) {
            acao = 'inativar';
        } else {
            acao = 'ativar';
        }        
        bootbox.confirm("Tem certeza que deseja "+acao+"?", function(result) {
            if(result) {
                $.post(baseUrl + '/produto/inativo', {
                    codproduto: codproduto,
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