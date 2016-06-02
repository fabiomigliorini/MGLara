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
            <li><a href="<?php echo url('produto/create');?>"><span class="glyphicon glyphicon-plus"></span> Novo</a></li>             
            <li><a href="<?php echo url("produto/$model->codproduto/edit");?>"><span class="glyphicon glyphicon-pencil"></span> Alterar</a></li> 
            <li><a href="<?php echo url("produto/$model->codproduto/juntar-barras");?>"><span class="glyphicon glyphicon-resize-small"></span> Juntar códigosde barra</a></li> 
            <li><a href="<?php echo url("produto/$model->codproduto/transferir-barras");?>"><span class="glyphicon glyphicon-transfer"></span> Transferir códigos de barra</a></li> 
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
                {!! Form::open(['method' => 'DELETE', 'route' => ['produto.destroy', $model->codproduto]]) !!}
                <span class="glyphicon glyphicon-trash"></span>
                {!! Form::submit('Excluir') !!}
                {!! Form::close() !!}
            </li>
        </ul>
    </div>
</nav>
@if(!empty($model->inativo))
    <br>
    <div class="alert alert-danger" role="alert">Inativado em {{formataData($model->inativo, 'L')}}</div>
@endif
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
                        <ul class="list-group">
                            <li class="list-group-item">
                                <strong>Códigos de barra</strong>
                                <span class="pull-right"><a href="{{ url("produto-barra/create?codproduto={$model->codproduto}") }}"><i class="glyphicon glyphicon-plus"></i> Novo</a></span>
                            </li>
                            @foreach($model->ProdutoBarraS as $pb)
                            <li class="list-group-item">
                                <div class="row item">
                                    <div class="col-md-2">
                                        {{ $pb->ProdutoEmbalagem->UnidadeMedida->sigla or $pb->Produto->UnidadeMedida->sigla}}
                                    </div>
                                    <div class="col-md-4">
                                        {{ $pb->barras }}
                                    </div>
                                    <div class="col-md-3">
                                        {{ $pb->variacao }}
                                    </div>
                                    <div class="col-md-3">
                                        <span class="pull-right">
                                            <a href="{{ url("produto-barra/$pb->codprodutobarra/edit") }}"><i class="glyphicon glyphicon-pencil"></i></a>
                                            &nbsp;&nbsp;
                                            <a href=""><i class="glyphicon glyphicon-trash"></i></a>
                                        </span>                                          
                                    </div>
                                </div>
                            </li>
                            @endforeach

                            <li class="list-group-item">
                                <strong>Embalagens</strong>
                                <span class="pull-right"><a href="{{ url("produto-embalagem/create?codproduto={$model->codproduto}") }}"><i class="glyphicon glyphicon-plus"></i> Novo</a></span>
                            </li>
                            @foreach($model->ProdutoEmbalagemS as $pe)
                            <li class="list-group-item">
                                <div class="row item">            
                                    <div class="col-md-4">
                                        {{ $pe->descricao }}
                                    </div>                            
                                    <div class="col-md-4">
                                    @if (empty($pe->preco))
                                        <div class="text-right text-muted">
                                            {{ formataNumero($pe->preco_calculado) }}
                                        </div>
                                    @else
                                        <div class="text-right text-success">
                                            {{ formataNumero($pe->preco_calculado) }}
                                        </div>	
                                    @endif
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row-fluid">
                                            <span class="pull-right">
                                                <a href="{{ url("produto-embalagem/$pe->codprodutoembalagem/edit") }}"><i class="glyphicon glyphicon-pencil"></i></a>
                                                &nbsp;&nbsp;
                                                <a href=""><i class="glyphicon glyphicon-trash"></i></a>
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
                    <h4>Notas fiscais</h4>
                    <div class="pull-right">{!! $nfpbs->appends(Request::all())->render() !!}</div>
                    <div class="search-bar">
                    {!! Form::model(Request::all(), ['route' => ['produto.show', 'produto'=> $model->codproduto], 'method' => 'GET', 'class' => 'form-inline', 'id' => 'produto-nfpb-search', 'role' => 'search', 'autocomplete' => 'off'])!!}
                        <strong>Lançamento</strong>
                        <div class="form-group">
                            {!! Form::text('nfpb_saida_de', null, ['class' => 'form-control between', 'id' => 'nfpb_saida_de', 'placeholder' => 'De']) !!}
                            {!! Form::text('nfpb_saida_ate', null, ['class' => 'form-control between', 'id' => 'nfpb_saida_ate', 'placeholder' => 'Até']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::select('nfpb_codfilial', $filiais, ['style'=>'width:100px'], ['id'=>'nfpb_codfilial']) !!}
                        </div>  
                        <div class="form-group">
                            {!! Form::select('nfpb_codnaturezaoperacao', $naturezaop, ['style'=>'width:100px'], ['id' => 'nfpb_codnaturezaoperacao']) !!}
                        </div>  
                    {!! Form::close() !!}
                    </div>
                    <br>                    
                    
                    
                    
                    
                    
                    
<div class="list-group" id="nfpbs">
  @foreach($nfpbs as $nfpb)
    <div class="list-group-item">
      <div class="row item">
          <div class="col-md-4">
              {{ formataData($nfpb->NotaFiscal->saida) }}
              {{ $nfpb->NotaFiscal->Filial->filial }} <br>
              {{ $nfpb->NotaFiscal->NaturezaOperacao->naturezaoperacao }} <br>
              <a href="{{ url("pessoa/{$nfpb->NotaFiscal->Pessoa->codpessoa}") }}">{{ $nfpb->NotaFiscal->Pessoa->fantasia }}</a>
          </div>                            
          <div class="col-md-4">
              {{ formataNumero($nfpb->quantidade) }}
              <?php
              $precounitario = ($nfpb->valortotal + $nfpb->icmsstvalor + $nfpb->ipivalor);
              if ($nfpb->quantidade > 0)
                  $precounitario = $precounitario/$nfpb->quantidade;
              $ipi = '';
              $icmsst = '';
              if ($nfpb->valortotal > 0)
              {
                  $ipi = $nfpb->ipivalor/$nfpb->valortotal;
                  $icmsst = $nfpb->icmsstvalor/$nfpb->valortotal;
              }
              echo $nfpb->ProdutoBarra->Produto->UnidadeMedida->sigla;
              if (isset($nfpb->ProdutoBarra->ProdutoEmbalagem))
              {
                  echo " C/" . formatNumero($nfpb->ProdutoBarra->ProdutoEmbalagem->quantidade, 0);
                  $precounitario /=$nfpb->ProdutoBarra->ProdutoEmbalagem->quantidade;
              }
              ?> <br>
              {{ formataNumero($nfpb->valorunitario) }} <br>

              @if($ipi > 0)
                  {{ formataNumero($ipi * 100, 0) }}  % IPI
              @endif
              <br>
              @if($icmsst > 0)
                  {{ formataNumero($icmsst * 100, 0) }}  % ST
              @endif
          </div>
          <div class="col-md-4">
              {{ formataNumero($precounitario) }} <br>
              <a href="{{ url("nota-fiscal/{$nfpb->NotaFiscal->codnotafiscal}") }}">{{ formataNumeroNota($nfpb->NotaFiscal->emitida, $nfpb->NotaFiscal->serie, $nfpb->NotaFiscal->numero, $nfpb->NotaFiscal->modelo) }}</a> <br>
              {{ $nfpb->ProdutoBarra->barras }}
          </div>
      </div>
    </div>    
  @endforeach
  @if (count($nfpbs) === 0)
      <h4>Nenhum registro encontrado!</h4>
  @endif    
</div>                    
                    
                    
                    
                    
                    
                    
                    
                    
                </div>
                <div role="tabpanel" class="tab-pane fade" id="tab-produto-negocios">
                    <h4>Negócios</h4>
                    <div class="pull-right">{!! $npbs->appends(Request::all())->render() !!}</div>
                    <div class="search-bar">
                    {!! Form::model(Request::all(), ['route' => ['produto.show', 'produto'=> $model->codproduto], 'method' => 'GET', 'class' => 'form-inline', 'id' => 'produto-npb-search', 'role' => 'search', 'autocomplete' => 'off'])!!}
                        <strong>Lançamento</strong>
                        <div class="form-group">
                            {!! Form::text('npb_saida_de', null, ['class' => 'form-control between', 'id' => 'npb_saida_de', 'placeholder' => 'De']) !!}
                            {!! Form::text('npb_saida_ate', null, ['class' => 'form-control between', 'id' => 'npb_saida_ate', 'placeholder' => 'Até']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::select('npb_codfilial', $filiais, ['style'=>'width:100px'], ['id'=>'npb_codfilial']) !!}
                        </div>  
                        <div class="form-group">
                            {!! Form::select('npb_codnaturezaoperacao', $naturezaop, ['style'=>'width:100px'], ['id' => 'npb_codnaturezaoperacao']) !!}
                        </div>  


                    {!! Form::close() !!}
                    </div>
                    <br>
                    
                    
                    
                    
<div class="list-group" id="npbs">
  @foreach($npbs as $npb)
    <div class="list-group-item">
        <div class="row item">
            <div class="col-md-4">
                {{ formataData($npb->Negocio->lancamento, 'L') }}
                {{ $npb->Negocio->Filial->filial }} <br>
                {{ $npb->Negocio->NaturezaOperacao->naturezaoperacao }} <br>
                <a href="{{ url("pessoa/{$npb->Negocio->Pessoa->codpessoa}") }}">{{ $npb->Negocio->Pessoa->fantasia }}</a>
            </div>                            
            <div class="col-md-4">
                {{ formataNumero($npb->quantidade) }} <br>
                <?php $precounitario = ($npb->valortotal)/$npb->quantidade; ?>
                {{ $npb->ProdutoBarra->Produto->UnidadeMedida->sigla }}
                @if(!empty($npb->ProdutoBarra->ProdutoEmbalagem))
                    C/ {{ formataNumero($npb->ProdutoBarra->ProdutoEmbalagem->quantidade, 0) }}
                    <?php $precounitario /=$npb->ProdutoBarra->ProdutoEmbalagem->quantidade;?>
                @endif
                <br>
                {{ $npb->valorunitario }}
            </div>
            <div class="col-md-4">
                {{ formataNumero($precounitario) }} <br>
                {{ $npb->codprodutobarra }} <br>
                {{ $npb->ProdutoBarra->barras }} <br>
                <a href="{{ url("negocio/{$npb->Negocio->codnegocio}") }}">{{ formataCodigo($npb->Negocio->codnegocio) }}</a>
            </div>
        </div>
    </div>    
  @endforeach
  @if (count($npbs) === 0)
      <h3>Nenhum registro encontrado!</h3>
  @endif    
</div>                    
                    
                    
                    
                    
                </div>
            </div>
        </div>        
	<?php
            $arr_saldos = [];
            $arr_totais = [false => 0, true => 0];
            foreach ($model->EstoqueLocalProdutoS as $es)
            {
                $arr_totais[$es->EstoqueSaldoS->first()->fiscal] += $es->EstoqueSaldoS->first()->saldoquantidade;
                $arr_saldos[] = $es;
            }
	?>
        <div class='panel panel-info'>
            <div class="panel-heading">
                <div class="row item">
                    <div class="col-md-6">Estoque</div>
                    <div class="col-md-2 text-right">Local</div>
                    <div class="col-md-2 text-right">Físico</div>
                    <div class="col-md-2 text-right">Fiscal</div>
                </div>
            </div>            
            <ul class="list-group bg-infoo">
                @foreach($arr_saldos as $saldo)
                <li class="list-group-item">
                    <div class="row item">            
                        <div class="col-md-6">
                            {{ $saldo->EstoqueLocal->estoquelocal }}
                        </div>
                        <div class="col-md-2 text-right">
                            {{ formataLocalEstoque($saldo->corredor, $saldo->prateleira, $saldo->coluna, $saldo->bloco) }}
                        </div>
                        <div class="col-md-2 text-right">
                            <a href='{{ url("estoque-saldo/{$saldo->EstoqueSaldoS->first()->codestoquesaldo}") }}'>
                                {{ ($saldo->EstoqueSaldoS->first()->fiscal) ? '' : formataNumero($saldo->EstoqueSaldoS->first()->saldoquantidade, 0) }}
                            </a>
                        </div>
                        <div class="col-md-2 text-right">
                            <a href='{{ url("estoque-saldo/{$saldo->EstoqueSaldoS->first()->codestoquesaldo}") }}'>
                                {{ ($saldo->EstoqueSaldoS->first()->fiscal) ? formataNumero($saldo->EstoqueSaldoS->first()->saldoquantidade, 0) : '' }}
                            </a>
                        </div>
                    </div>            
                </li>
                @endforeach    
                <li class="list-group-item">
                    <div class="row item">            
                        <div class="col-md-6">
                            <strong>Total</strong>
                        </div>
                        <div class="col-md-2 text-right"></div>
                        <div class="col-md-2 text-right">
                            <strong>{{ formataNumero($arr_totais[false], 0) }}</strong>
                        </div>
                        <div class="col-md-2 text-right">
                            <strong>{{ formataNumero($arr_totais[true], 0) }}</strong>
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
    

    $(document).on('click','#tab-produto-notasfiscais .pagination a', function(e){
        e.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        getNfpbs(page);
    });
    
    $(document).on('click','#tab-produto-negocios .pagination a', function(e){
        e.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        getNpbs(page);
    });
    
    function getNpbs(page){
        $.ajax({
            url: baseUrl + '/produto/<?php echo $model->codproduto;?>?page=' + page
        }).done(function(data){
            $('#npbs').html(data);
        });
    }    
    
    function getNfpbs(page){
        $.ajax({
            url: baseUrl + '/produto/<?php echo $model->codproduto;?>?page=' + page
        }).done(function(data){
            $('#nfpbs').html(data);
        });
    }    
    
    /***
    $(window).on('hashchange', function() {
        if (window.location.hash) {
            var page = window.location.hash.replace('#', '');
            if (page == Number.NaN || page <= 0) {
                return false;
            } else {
                getPosts(page);
            }
        }
    });
    $(document).ready(function() {
        $(document).on('click', '.pagination a', function (e) {
            getPosts($(this).attr('href').split('page=')[1]);
            e.preventDefault();
        });
    });
    function getPosts(page) {
        $.ajax({
            url : '?page=' + page,
            dataType: 'json',
        }).done(function (data) {
            $('#nfpbs').html(data);
            location.hash = page;
        }).fail(function () {
            alert('Posts could not be loaded.');
        });
    }
    ***/
    
    
    $('#nfpb_saida_de, #nfpb_saida_ate, #npb_saida_de, #npb_saida_ate').datetimepicker({
        useCurrent: false,
        showClear: true,
        locale: 'pt-br',
        format: 'DD/MM/YY'
    });
    $(document).on('dp.change', '#saida_de, #saida_ate', function() {
        $('#produto-npb-search').submit();
    });
    
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
    
});
</script>
@endsection
@stop
