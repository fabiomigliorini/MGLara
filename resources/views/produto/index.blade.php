@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li>
                <a href="<?php echo url('produto/create');?>"><span class="glyphicon glyphicon-plus"></span> Novo</a>
            </li> 
        </ul>
    </div>
</nav>
<h1 class="header">Produtos</h1>
<hr>
<div class="search-bar">
{!! Form::model(Request::all(), ['route' => 'produto.index', 'method' => 'GET', 'class' => 'form-inline', 'id' => 'produto-search', 'role' => 'search', 'autocomplete' => 'off'])!!}
    <div class="form-group">
        {!! Form::text('codproduto', null, ['class' => 'form-control search-cod', 'placeholder' => '#']) !!}
    </div>

    <div class="form-group">
        {!! Form::text('barras', null, ['class' => 'form-control', 'placeholder' => 'Barras']) !!}
    </div>

    <div class="form-group">
        {!! Form::text('produto', null, ['class' => 'form-control', 'placeholder' => 'Descrição']) !!}
    </div>

    <div class="form-group">
        {!! Form::text('codmarca', null, ['class' => 'form-control','id'=>'codmarca', 'style'=>'width:140px', 'placeholder' => 'Marca']) !!}
    </div>

    <div class="form-group">
        {!! Form::text('referencia', null, ['class' => 'form-control', 'style'=>'width:165px', 'placeholder' => 'Referencia']) !!}
    </div>

    <div class="form-group">
        <select placeholder="Inativo" class="form-control" name="inativo" id="inativo" style="width: 120px;">
            <option value=""></option>
            <option value="0">Todos</option>
            <option value="1">Ativos</option>
            <option value="2">Inativos</option>
        </select>
    </div>

    <div class="form-group">
        <select placeholder="Tributação" class="form-control" name="codtributacao" id="codtributacao" style="width: 120px;">
            <option value=""></option>
            <option value="1">Tributação</option>
            <option value="2">Isento</option>
            <option value="3">Substituição</option>
        </select>
    </div>

    <div class="form-group">
        <select placeholder="Site" class="form-control" name="site" id="site" style="width: 100px;">
            <option value=""></option>
            <option value="1">No Site</option>
            <option value="2">Fora do Site</option>
        </select>
    </div>
    
    <button type="submit" class="btn btn-default pull-right">Buscar</button>
    
    <div class="form-group">
        {!! Form::text('codncm', null, ['class' => 'form-control', 'id'=> 'codncm', 'placeholder' => 'NCM', 'style'=> 'width: 450px;']) !!}
    </div>

    <strong>Preço</strong>
    <div class="form-group">
        {!! Form::text('preco_de', null, ['class' => 'form-control text-right between', 'id' => 'preco_de', 'placeholder' => 'De']) !!}
        {!! Form::text('preco_ate', null, ['class' => 'form-control text-right between', 'id' => 'preco_ate', 'placeholder' => 'Até']) !!}
    </div>

    <strong>Criação</strong>
    <div class="form-group">
        {!! Form::text('criacao_de', null, ['class' => 'form-control between', 'id' => 'criacao_de', 'placeholder' => 'De']) !!}
        {!! Form::text('criacao_ate', null, ['class' => 'form-control between', 'id' => 'criacao_ate', 'placeholder' => 'Até']) !!}
    </div>

    <strong>Alteração</strong>
    <div class="form-group">
        {!! Form::text('alteracao_de', null, ['class' => 'form-control between', 'id' => 'alteracao_de', 'placeholder' => 'De']) !!}
        {!! Form::text('alteracao_ate', null, ['class' => 'form-control between', 'id' => 'alteracao_ate', 'placeholder' => 'Até']) !!}
    </div>

{!! Form::close() !!}
</div>

<br>
<div id="registros">
  <div class="list-group" id="items">
    @foreach($model as $row)
      <div class="list-group-item">
        <div class="row item">
            <div class="col-md-1">
                <a href="{{ url("produto/$row->codproduto") }}">
                    <strong>{{ formataCodigo($row->codproduto) }}</strong>
                </a>
                @if($row->codncm)
                <div class="text-muted">
                    <a href="{{ url("ncm/$row->codncm") }}">
                        {{ formataNcm($row->Ncm->ncm) }}
                    </a>
                </div>    
                @endif
                @if($row->codtributacao)
                <div class="text-muted">
                    {{ $row->Tributacao->tributacao }}
                </div>
                @endif
            </div>                            
            <div class="col-md-4">
                <a href="{{ url("produto/$row->codproduto") }}">
                    <strong>{{ $row->produto }}</strong>
                </a>
                @if(!empty($row->inativo))
                <div>
                    <span class="label label-danger produtos-grid-inativo">Inativado em {{ formataData($row->inativo, 'L')}} </span>
                </div>
                @endif
                @if(!empty($row->codsubgrupoproduto))
                <div>
                    <strong>{{ $row->SubGrupoProduto->GrupoProduto->grupoproduto }} › {{ $row->SubGrupoProduto->subgrupoproduto }}</strong>
                </div>    
                @endif
                <a href="{{ url("marca/$row->codmarca") }}">
                    {{ $row->Marca->marca }}
                </a>
                <span class="text-muted">{{ $row->referencia }}</span>
            </div>
            <div class="col-md-7">
                <div class="row subregistro">
                    <strong class="col-md-2 text-right">
                        {{ formataNumero($row->preco) }}
                    </strong>
                    <small class="col-md-2">
                        {{ $row->UnidadeMedida->sigla }}
                    </small>
                    @foreach ($row->ProdutoBarraS()->whereNull('codprodutoembalagem')->get() as $pb)
                        <small class="col-md-8 pull-right text-muted"> 
                            <div class="col-md-3">
                                {{ $pb->barras}}
                            </div>
                            <div class="col-md-5">
                                {{ $pb->variacao}}
                            </div>
                            <div class="col-md-4">
                                <strong>{{ $pb->Marca->marca or ''}}</strong>
                                {{ $pb->referencia}}
                            </div>
                        </small>
                    @endforeach
                </div>
                @foreach ($row->ProdutoEmbalagemS as $pe)
                    @include('produto.produtoembalagem',  ['pe' => $pe])
                @endforeach
            </div>
        </div>
      </div>    
    @endforeach
    @if (count($model) === 0)
        <h3>Nenhum registro encontrado!</h3>
    @endif    
  </div>
  <?php echo $model->appends(Request::all())->render();?>
</div>
@section('inscript')
<style type="text/css">
ul.pagination {
    margin: 0;
}
.between {
    width: 82px !important;
}
#produto-search .form-group {
    margin-bottom: 5px;
    position: relative;
}
#s2id_codncm {
    margin-right: 5px;
}
</style>
<script type="text/javascript">
$(document).ready(function() {
    $('ul.pagination').removeClass('hide');
    
        
    //$('#produto-search').change(function() {
    //    this.submit();
    //});
    
    $("#produto-search").on("change", function (event) {
        var $this = $(this);
        var frmValues = $this.serialize();
        $.ajax({
            type: 'GET',
            url: baseUrl+'/produto',
            data: frmValues
        })
        .done(function (data) {
            var dados = data.find('#items');
            $("#items").html(data);
            //console.log(data);
        })
        .fail(function () {
            console.log('Erro no filtro');
        });
        event.preventDefault(); 
    });    
    
    
    /*
    // aqui implementar mesmo recurso da paginação jquery
    $('#produto-search').change(function() {
        var ajaxRequest = $("#produto-search").serialize();
        
        //$.fn.yiiListView.update(
        //'registros',
        //    {data: ajaxRequest}
        //);
        $.ajax({
            url: baseUrl+'/produto', {param:ajaxRequest}
        }).done(function(data){
            $('#registros').html(data);
        });        

    });    
    */
    
    $('#inativo').select2({
        allowClear:true,
        closeOnSelect:true
    })<?php echo (app('request')->input('inativo') ? ".select2('val'," .app('request')->input('inativo').");" : ';'); ?>
    $('#codtributacao').select2({
        allowClear:true,
        closeOnSelect:true
    })<?php echo (app('request')->input('codtributacao') ? ".select2('val'," .app('request')->input('codtributacao').");" : ';'); ?>
    $('#site').select2({
        allowClear:true,
        closeOnSelect:true
    })<?php echo (app('request')->input('site') ? ".select2('val'," .app('request')->input('site').");" : ';'); ?>
    $('#preco_de, #preco_ate').autoNumeric('init', {aSep:'.', aDec:',', altDec:'.' });
    $('#criacao_de, #criacao_ate, #alteracao_de, #alteracao_ate').datetimepicker({
        useCurrent: false,
        showClear: true,
        locale: 'pt-br',
        format: 'DD/MM/YY'
    });
    $(document).on('dp.change', '#criacao_de, #criacao_ate, #alteracao_de, #alteracao_ate', function() {
        $('#produto-search').submit();
    });
    $('#codncm').select2({
        minimumInputLength:1,
        allowClear:true,
        closeOnSelect:true,
        placeholder:'Ncm',
        formatResult:function(item) {
            var markup = "";
            markup    += "<b>" + item.ncm + "</b>&nbsp;";
            markup    += "<span>" + item.descricao + "</span>";
            return markup;
        },
        formatSelection:function(item) { 
            return item.ncm + "&nbsp;" + item.descricao; 
        },
        ajax:{
            url:baseUrl+"/ncm/ajax",
            dataType:'json',
            quietMillis:500,
            data:function(term, page) { 
                return {q: term}; 
            },
            results:function(data, page) {
                var more = (page * 20) < data.total;
                return {results: data.data};
            }
        },
        initSelection:function (element, callback) {
            $.ajax({
                type: "GET",
                url: baseUrl+"/ncm/ajax",
                data: "id="+$('#codncm').val(),
                dataType: "json",
                success: function(result) { callback(result); }
            });
        },
        width:'resolve'
    });    
    $('#codmarca').select2({
        minimumInputLength:1,
        allowClear:true,
        closeOnSelect:true,
        placeholder:'Marca',
        formatResult:function(item) {
            var markup = "<div class='row-fluid'>";
            markup    += item.marca;
            markup    += "</div>";
            return markup;
        },
        formatSelection:function(item) { 
            return item.marca; 
        },
        ajax:{
            url:baseUrl+"/marca/ajax",
            dataType:'json',
            quietMillis:500,
            data:function(term,page) { 
                return {q: term}; 
            },
            results:function(data,page) {
                var more = (page * 20) < data.total;
                return {results: data.items};
            }
        },
        initSelection:function (element, callback) {
            $.ajax({
                type: "GET",
                url: baseUrl+"/marca/ajax",
                data: "id="+$('#codmarca').val(),
                dataType: "json",
                success: function(result) { callback(result); }
            });
        },
        width:'resolve'
    });    
});
</script>
@endsection
@stop