@extends('layouts.default')
@section('content')
<?php
    use MGLara\Models\Filial;
    use MGLara\Models\NaturezaOperacao;
    
    $filiais    = [''=>''] + Filial::lists('filial', 'codfilial')->all();
    $naturezaop = [''=>''] + NaturezaOperacao::lists('naturezaoperacao', 'codnaturezaoperacao')->all();

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
                <a href="{{ url("produto/$model->codproduto") }}" data-excluir data-pergunta="Tem certeza que deseja excluir o produto '{{ $model->produto }}'?" data-after-delete="redirecionar();"><i class="glyphicon glyphicon-trash"></i> Excluir</a>
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
            <ul class="nav nav-pills" role="tablist">
                <li role="presentation" class="active"><a href="#tab-variacoes" aria-controls="home" role="tab" data-toggle="tab">Variações</a></li>
                <li role="presentation"><a href="#tab-estoque" aria-controls="home" role="tab" data-toggle="tab">Estoque</a></li>
                <li role="presentation"><a href="#tab-site" aria-controls="profile" role="tab" data-toggle="tab">Site</a></li>
                <li role="presentation"><a href="#tab-fiscal" aria-controls="profile" role="tab" data-toggle="tab">Fiscal</a></li>
                <li role="presentation"><a href="#tab-npb" aria-controls="messages" role="tab" data-toggle="tab">Negócios</a></li>
                <li role="presentation"><a href="#tab-nfpb" aria-controls="messages" role="tab" data-toggle="tab">Notas Fiscais</a></li>
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
                    <?php
                    //@include('produto.estoque')
                    ?>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="tab-fiscal">
                    <?php
                    //@include('produto.fiscal')
                    ?>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="tab-npb">
                    <?php
                    @include('negocio-produto-barra.index')
                    ?>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="tab-nfpb">
                    <?php
                    //@include('nota-fiscal-produto-barra.index')
                    ?>
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

#produto-search .form-group, 
#produto-npb-search .form-group {
    margin-bottom: 5px;
    position: relative;
}
    
</style>

<script type="text/javascript">var codproduto = {{ $model->codproduto }}</script>
<script type="text/javascript">
function redirecionar()
{
    location.replace(baseUrl + '/produto');
}

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
            url: baseUrl+'/produto/listagem-json',
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
                url: baseUrl+'/produto/listagem-json',
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
    
    
    // Notas fiscais e Negócios
    $('.pagination').removeClass('hide');
    
    
    $('#nfpb_codfilial').select2({
        allowClear:true,
        closeOnSelect:true
    })<?php echo (app('request')->input('nfpb_codfilial') ? ".select2('val'," .app('request')->input('nfpb_codfilial').");" : ';'); ?>
    
    $('#npb_codfilial').select2({
        allowClear:true,
        closeOnSelect:true
    })<?php echo (app('request')->input('npb_codfilial') ? ".select2('val'," .app('request')->input('npb_codfilial').");" : ';'); ?>
    
    $('#nfpb_codnaturezaoperacao').select2({
        allowClear:true,
        closeOnSelect:true
    })<?php echo (app('request')->input('nfpb_codnaturezaoperacao') ? ".select2('val'," .app('request')->input('nfpb_codnaturezaoperacao').");" : ';'); ?>
    
    $('#npb_codnaturezaoperacao').select2({
        allowClear:true,
        closeOnSelect:true
    })<?php echo (app('request')->input('npb_codnaturezaoperacao') ? ".select2('val'," .app('request')->input('npb_codnaturezaoperacao').");" : ';'); ?>
    
    
    
    // botão delete da embalagem
	$('delete-barra').click(function(e) {
            console.log('Ola, clicaram aqui!!');
            e.preventDefault();
            var codprodutoembalagem = this.dataset.pe;
		// pega url para delete
            var url = $(this).attr('href');
		//pede confirmacao
		bootbox.confirm("Excluir este Código de Barras?", function(result) {
			if (result) {
				$.ajax({
					type: 'POST',
					//url: baseUrl + '/produto-embalagem/' + codprodutoembalagem + '/destroy',
					url: url,
					success: function() {
                        $('#'+codprodutoembalagem).remove();
					},
					error: function (XHR, textStatus) {
						var err;
						if (XHR.readyState === 0 || XHR.status === 0) {
							return;
						}
						//tipos de erro
						switch (textStatus) {
							case 'timeout':
								err = 'O servidor não responde (timed out)!';
								break;
							case 'parsererror':
								err = 'Erro de parâmetros (Parser error)!';
								break;
							case 'error':
								if (XHR.status && !/^\s*$/.test(XHR.status)) {
									err = 'Erro ' + XHR.status;
								} else {
									err = 'Erro';
								}
								if (XHR.responseText && !/^\s*$/.test(XHR.responseText)) {
									err = err + ': ' + XHR.responseText;
								}
								break;
						}
						if (err) {
							bootbox.alert(err);
						}
					}
				});
			}	
		});
	});	
        
        
        // PAGINAÇÃO NEGÓCIOS PRODUTO BARRA
        $("#produto-npb-search").on("change", function (event) {
            var $this = $(this);
            var frmValues = $this.serialize();
            $.ajax({
                type: 'GET',
                url: baseUrl + '/produto/' + {{ $model->codproduto }},
                data: frmValues
            })
            .done(function (data) {
                $('#npbs').html(jQuery(data).find('#npbs').html()); 
            })
            .fail(function () {
                console.log('Erro no filtro');
            });
            event.preventDefault(); 
        });
    
        $('#npb_paginacao .pagination a').on('click', function (e) {
            var page = $(this).attr('href').split('page=')[1];
            $("#npb_page").val(page);
            $('#produto-npb-search').change();
            $('#npb_paginacao .pagination .active').removeClass('active');
            $(this).parent().addClass('active');
            e.preventDefault();
        });   	    
        
        $('#nfpb_saida_de, #nfpb_saida_ate, #npb_lancamento_de, #npb_lancamento_ate').datetimepicker({
            useCurrent: false,
            showClear: true,
            locale: 'pt-br',
            format: 'DD/MM/YY'
        });
        
        $(document).on('dp.change', '#npb_lancamento_de, #npb_lancamento_de, #npb_codpessoa', function() {
            $('#produto-npb-search').change();
        });
        
        $('#npb_codpessoa').select2({
            'minimumInputLength':3,
            'allowClear':true,
            'closeOnSelect':true,
            'placeholder':'Pessoa',
            'formatResult':function(item) {
                var css = "div-combo-pessoa";
                if (item.inativo)
                    var css = "text-error";

                var css_titulo = "";
                var css_detalhes = "text-muted";
                if (item.inativo){
                    css_titulo = "text-error";
                    css_detalhes = "text-error";
                }

                var nome = item.fantasia;
                var markup = "";
                markup    += "<strong class='" + css_titulo + "'>" + nome + "</strong>";
                markup    += "<small class='pull-right " + css_detalhes + "'>#" + formataCodigo(item.id) + "</small>";
                markup    += "<br>";
                markup    += "<small class='" + css_detalhes + "'>" + item.pessoa + "</small>";
                markup    += "<small class='pull-right " + css_detalhes + "'>" + formataCnpjCpf(item.cnpj) + "</small>";
                return markup;
            },
            'formatSelection':function(item) { 
                return item.fantasia; 
            },
            'ajax':{
                'url':baseUrl+'/pessoa/listagem-json',
                'dataType':'json',
                'quietMillis':500,
                'data':function(term, current_page) { 
                    return {
                        q: term, 
                        per_page: 10, 
                        current_page: current_page
                    }; 
                },
                'results':function(data,page) {
                    //var more = (current_page * 20) < data.total;
                    return {
                        results: data.data, 
                        //more: data.mais
                    };
                }
            },
            'initSelection':function (element, callback) {
                $.ajax({
                    type: "GET",
                    url: baseUrl+'/pessoa/listagem-json',
                    data: "id=<?php if(isset($_GET['codpessoa'])){echo $_GET['codpessoa'];}?>",
                    dataType: "json",
                    success: function(result) { 
                        callback(result); 
                    }
                });
            },'width':'resolve'
        });

        // PAGINAÇÃO NOTAS FISCAIS PRODUTO BARRA
        $("#produto-nfpb-search").on("change", function (event) {
            var $this = $(this);
            var frmValues = $this.serialize();
            $.ajax({
                type: 'GET',
                url: baseUrl + '/produto/' + {{ $model->codproduto }},
                data: frmValues
            })
            .done(function (data) {
                $('#nfpbs').html(jQuery(data).find('#nfpbs').html()); 
            })
            .fail(function () {
                console.log('Erro no filtro');
            });
            event.preventDefault(); 
        });
    
        $('#nfpb_paginacao .pagination a').on('click', function (e) {
            var page = $(this).attr('href').split('page=')[1];
            $("#nfpb_page").val(page);
            $('#produto-nfpb-search').change();
            $('#nfpb_paginacao .pagination .active').removeClass('active');
            $(this).parent().addClass('active');
            e.preventDefault();
        });   	    
        
        $(document).on('dp.change', '#nfpb_saida_de, #nfpb_saida_ate', function() {
            $('#produto-nfpb-search').change();
        });
    
        $('#nfpb_codpessoa').select2({
            'minimumInputLength':3,
            'allowClear':true,
            'closeOnSelect':true,
            'placeholder':'Pessoa',
            'formatResult':function(item) {
                var css = "div-combo-pessoa";
                if (item.inativo)
                    var css = "text-error";

                var css_titulo = "";
                var css_detalhes = "text-muted";
                if (item.inativo){
                    css_titulo = "text-error";
                    css_detalhes = "text-error";
                }

                var nome = item.fantasia;
                var markup = "";
                markup    += "<strong class='" + css_titulo + "'>" + nome + "</strong>";
                markup    += "<small class='pull-right " + css_detalhes + "'>#" + formataCodigo(item.id) + "</small>";
                markup    += "<br>";
                markup    += "<small class='" + css_detalhes + "'>" + item.pessoa + "</small>";
                markup    += "<small class='pull-right " + css_detalhes + "'>" + formataCnpjCpf(item.cnpj) + "</small>";
                return markup;
            },
            'formatSelection':function(item) { 
                return item.fantasia; 
            },
            'ajax':{
                'url':baseUrl+'/pessoa/listagem-json',
                'dataType':'json',
                'quietMillis':500,
                'data':function(term, current_page) { 
                    return {
                        q: term, 
                        per_page: 10, 
                        current_page: current_page
                    }; 
                },
                'results':function(data,page) {
                    //var more = (current_page * 20) < data.total;
                    return {
                        results: data.data, 
                        //more: data.mais
                    };
                }
            },
            'initSelection':function (element, callback) {
                $.ajax({
                    type: "GET",
                    url: baseUrl+'/pessoa/listagem-json',
                    data: "id=<?php if(isset($_GET['codpessoa'])){echo $_GET['codpessoa'];}?>",
                    dataType: "json",
                    success: function(result) { 
                        callback(result); 
                    }
                });
            },'width':'resolve'
        });
        
});
</script>
@endsection
@stop