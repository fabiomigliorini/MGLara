@extends('layouts.default')
@section('content')
<?php
    use MGLara\Models\Filial;
    use MGLara\Models\NaturezaOperacao;
    
    $filiais    = [''=>''] + Filial::lists('filial', 'codfilial')->all();
    $naturezaop = [''=>''] + NaturezaOperacao::lists('naturezaoperacao', 'codnaturezaoperacao')->all();
    //dd($model->ProdutoVariacaoS);
?>
<ol class="breadcrumb header">
    {!! 
    titulo(
        $model->codproduto,
        [
            url("produto") => 'Produtos',
            $model->produto,
        ],
        $model->inativo,
        6
    ) 
    !!}
    <li class='active'>
        <small>
            <a href="<?php echo url("produto/$model->codproduto/edit");?>" alt="Editar"><span class="glyphicon glyphicon-pencil"></span></a>
            &nbsp;
            <a href="<?php echo url("produto/create/?duplicar={$model->codproduto}");?>"><span class="glyphicon glyphicon-duplicate"></span></a>
            &nbsp;
            @if(empty($model->inativo))
            <a href="{{ url('produto/inativo') }}" data-inativar data-codigo="{{ $model->codproduto }}" data-acao="inativar" data-pergunta="Tem certeza que deseja inativar o produto {{ $model->produto }}? " data-after-inativar="location.reload()"><span class="glyphicon glyphicon-ban-circle"></span></a>
            @else
            <a href="{{ url('produto/inativo') }}" data-inativar data-codigo="{{ $model->codproduto }}" data-acao="ativar" data-pergunta="Tem certeza que deseja ativar o produto {{ $model->produto }}? " data-after-inativar="location.reload()"><span class="glyphicon glyphicon-ok-sign"></span></a>
            @endif
            &nbsp;
            <a href="{{ url("produto/$model->codproduto") }}" data-excluir data-pergunta="Tem certeza que deseja excluir o produto '{{ $model->produto }}'?" data-after-delete="location.replace(baseUrl + '/produto');"><i class="glyphicon glyphicon-trash"></i></a>
            &nbsp;
            <a href="" id="btnVaiPara"><span class="glyphicon glyphicon-new-window"></span></a>
        </small>
    </li>
</ol>

<br>
<div class="col-md-6">
    @include('produto.show-imagens')
</div>
    
<div class="col-md-6">
    <div>
        <ul class="nav nav-tabs" role="tablist" id='tab-produto'>
            <li role="presentation" class='active'><a href="#tab-variacoes" aria-controls="home" role="tab" data-toggle="tab">Detalhes</a></li>
            <li role="presentation"><a href="#tab-estoque" aria-controls="home" role="tab" data-toggle="tab">Estoque</a></li>
            <li role="presentation"><a href="#tab-site" aria-controls="profile" role="tab" data-toggle="tab">Site</a></li>
            <li role="presentation"><a href="#tab-fiscal" aria-controls="profile" role="tab" data-toggle="tab">NCM</a></li>
            <li role="presentation"><a href="#tab-negocio" aria-controls="messages" role="tab" data-toggle="tab">Negócios</a></li>
            <li role="presentation"><a href="#tab-notasfiscais" aria-controls="messages" role="tab" data-toggle="tab">Notas Fiscais</a></li>
            <li role="presentation"><a href="#tab-observacoes" aria-controls="observacoes" role="tab" data-toggle="tab">Observações</a></li>
        </ul>
        <br>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="tab-variacoes">
                <div class='clearfix'>
                    <div class='col-md-7'>
                        <ol class="breadcrumb">
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
                        </ol>
                        <ol class="breadcrumb">
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
                        </ol>
                    </div>
                    @include('produto.show-embalagens')
                </div>


                <a href="<?php echo url("produto-variacao/create?codproduto={$model->codproduto}");?>">Nova Variação <span class="glyphicon glyphicon-plus"></span></a>
                |
                <a href="<?php echo url("produto/$model->codproduto/transferir-variacao");?>">Transferir Variação <span class="glyphicon glyphicon-transfer"></span></a>
                |
                <a href="<?php echo url("produto-barra/create?codproduto={$model->codproduto}");?>">Novo Código de Barras <span class="glyphicon glyphicon-plus"></span></a>

                <br>
                <br>

                @include('produto.show-variacoes')

            </div>
            <div role="tabpanel" class="tab-pane fade" id="tab-estoque">
                <div id="div-estoque">
                    <b>Aguarde...</b>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="tab-site">
                <p>
                    <botton class="btn btn-default" id="integracao-open-cart"><i class="glyphicon glyphicon-shopping-cart"></i> 
                        Sincronizar &nbsp;&nbsp;
                        <img width="20px" id="sincronizar" src="{{ URL::asset('public/img/carregando.gif') }}">
                    </botton>
                    
                </p>
                <br>
                <strong>Divulgado no Site: {{ ($model->site)?'Sim':'Não' }}</strong>
                <hr>
                {!! nl2br($model->descricaosite) !!}
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
                                <div class="col-sm-7">{!! Form::select2ProdutoVariacao('negocio_codprodutovariacao', $parametros['negocio_codprodutovariacao'], ['style'=>'width:100%', 'id' => 'negocio_codprodutovariacao', 'codproduto'=>'negocio_codproduto']) !!}</div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-4 control-label">{!! Form::label('negocio_codproduto', 'Pessoa') !!}</div>
                                <div class="col-sm-7">{!! Form::select2Pessoa('negocio_codpessoa', null, ['class' => 'form-control', 'id'=>'negocio_codpessoa', 'style'=>'width:100%', 'placeholder' => 'Pessoa', 'ativo' => 9]) !!}</div>
                            </div>                            
                            {!! Form::hidden('negocio_codproduto', $model->codproduto, ['id'=>'negocio_codproduto']) !!}

                        {!! Form::hidden('_div', 'div-negocios', ['id'=>'negocio_page']) !!}

                        {!! Form::close() !!}
                    </div>
                </div>
                <br>
                <div id="div-negocios">
                    <b>Aguarde...</b>
                </div>
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
                                <div class="col-sm-7">{!! Form::select2Pessoa('notasfiscais_codpessoa', null, ['class' => 'form-control','id'=>'notasfiscais_codpessoa', 'style'=>'width:100%', 'placeholder' => 'Pessoa', 'ativo' => 9]) !!}</div>
                            </div>
                            {!! Form::hidden('notasfiscais_codproduto', $model->codproduto, ['id'=>'notasfiscais_codproduto']) !!}
                            {!! Form::hidden('_div', 'div-notasfiscais', ['id'=>'notasfiscais_page']) !!}

                        {!! Form::close() !!}
                    </div>
                </div>
                <br>
                <div id="div-notasfiscais">
                    <b>Aguarde...</b>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="tab-observacoes">
                {!! $model->observacoes !!}
            </div>
        </div>
    </div>
    <br>    
    @include('includes.autor')
</div>    

@section('inscript')
<script type="text/javascript">

function mostraListagemNegocios()
{

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

    var listagemEstoqueAberta = false;
    $('a[href="#tab-estoque"]').on('shown.bs.tab', function (e) {
        recarregaDiv('div-estoque');
        listagemEstoqueAberta = true;
    });
    
    
    $('#codproduto').change(function (){
        window.location.href = '{{ url("produto/") }}' + $('#codproduto').val();
    });
    
    $('#sincronizar').hide();
    $('#integracao-open-cart').click(function (e) {
        e.preventDefault();
        bootbox.confirm("Tem certeza que deseja sincronizar esse produto", function(result) {
            if (result) {
                $.ajax({
                    type: 'GET',
                    url: baseUrl + '/produto/sincroniza-produto-open-cart',
                    data: {
                        id:{{ $model->codproduto }}
                    },
                    beforeSend: function( xhr ) {
                        $('#sincronizar').show(function() {
                            $('#integracao-open-cart').attr('disabled','disabled');
                        });
                    }
                })
                .done(function (data) {
                    $('#sincronizar').hide(function() {
                        $('#integracao-open-cart').removeAttr('disabled');
                    });
                    if(data.resultado === true) {
                        var mensagem = '<strong class="text-success">'+data.mensagem+'</strong>';
                        bootbox.alert(mensagem);
                        console.log(data.resultado);
                    } else {
                        var mensagem = '<strong class="text-danger">'+data.mensagem+'</strong>';
                        mensagem += '<hr><pre>';
                        mensagem += JSON.stringify(data.exception, undefined, 2);
                        mensagem += '</pre>';                        
                        bootbox.alert(mensagem);
                        console.log(data.resultado);
                    }
                })
                .fail(function (data) {
                    console.log('erro no POST');
                });                 
            }
        }); 
    });
    
    $('#btnVaiPara').click(function (e) {
        e.preventDefault();
        bootbox.prompt({
            title: "Digite o código do produto",
            inputType: 'number',
            callback: function (result) {
                if(result) {
                    location.replace(baseUrl + '/produto/' + result)
                }
            }
        });
    });
    
    
});
</script>
@endsection
@stop