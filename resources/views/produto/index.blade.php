@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid">
        <ul class="nav navbar-nav">
            <li>
                <a href="<?php echo url('produto/create'); ?>"><span class="glyphicon glyphicon-plus"></span> Novo</a>
            </li>
        </ul>
    </div>
</nav>
<h1 class="header">
    {!! titulo(null, 'Produtos', null) !!}
    <a class="btn btn-primary pull-right" role="button" data-toggle="collapse" href="#div-filtro" aria-expanded="false" aria-controls="div-filtro">
        <span class='glyphicon glyphicon-search'></span>
    </a>
</h1>
<hr>
<?php
use MGLara\Models\SecaoProduto;
$filtro = Request::session()->get('produto.index');
?>
<div class="clearfix"></div>
<div class='collapse' id='div-filtro'>
    <div class='well well-sm' style="padding:9px 0">
    {!! Form::model($filtro,
    [
        'route' => 'produto.index',
        'method' => 'GET',
        'class' => 'form-horizontal',
        'id' => 'produto-search',
        'role' => 'search',
        'autocomplete' => 'off'
    ])!!}
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('codproduto', '#', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-md-4">{!! Form::text('codproduto', null, ['class' => 'form-control', 'placeholder' => '#']) !!}</div>
        </div>

        <div class="form-group">
            {!! Form::label('barras', 'Barras', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-md-4">{!! Form::text('barras', null, ['class' => 'form-control', 'placeholder' => 'Barras']) !!}</div>
        </div>

        <div class="form-group">
            {!! Form::label('produto', 'Descrição', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-md-6">{!! Form::text('produto', null, ['class' => 'form-control', 'placeholder' => 'Descrição']) !!}</div>
        </div>

        <div class="form-group">
            {!! Form::label('codmarca', 'Marca', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-md-6">{!! Form::select2Marca('codmarca', null, ['class' => 'form-control','id'=>'codmarca', 'style'=>'width:160px']) !!}</div>
        </div>

        <div class="form-group">
            {!! Form::label('barras', 'Barras', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-md-6">{!! Form::select2SecaoProduto('codsecaoproduto', null, ['class'=> 'form-control', 'id' => 'codsecaoproduto', 'placeholder' => 'Seção']) !!}</div>
        </div>

        <div class="form-group">
            {!! Form::label('codfamiliaproduto', 'Família', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-md-6">{!! Form::select2FamiliaProduto('codfamiliaproduto', null, ['class' => 'form-control','id'=>'codfamiliaproduto', 'placeholder' => 'Família', 'ativo'=>'9']) !!}</div>
        </div>

        <div class="form-group">
            {!! Form::label('codgrupoproduto', 'Grupo', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-md-6">{!! Form::select2GrupoProduto('codgrupoproduto', null, ['class' => 'form-control','id'=>'codgrupoproduto', 'placeholder' => 'Grupo', 'ativo'=>'9']) !!}</div>
        </div>

        <div class="form-group">
            {!! Form::label('codsubgrupoproduto', 'Sub Grupo', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-md-6">{!! Form::select2SubGrupoProduto('codsubgrupoproduto', null, ['class' => 'form-control','id'=>'codsubgrupoproduto', 'placeholder' => 'Sub Grupo', 'ativo'=>'9']) !!}</div>
        </div>

        <div class="form-group">
            {!! Form::label('referencia', 'Referência', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-md-3">{!! Form::text('referencia', null, ['class' => 'form-control', 'placeholder' => 'Referência']) !!}</div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('ativo', 'Ativo', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-md-3">{!! Form::select2Ativo('ativo', null, ['class'=> 'form-control', 'id' => 'ativo']) !!}</div>
        </div>

        <div class="form-group">
            {!! Form::label('codtributacao', 'Tributação', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-md-4">{!! Form::select2Tributacao('codtributacao', null, ['placeholder'=>'Tributação',  'class'=> 'form-control', 'id' => 'codtributacao']) !!}</div>
        </div>

        <div class="form-group">
            {!! Form::label('site', 'Site', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-md-3">{!! Form::select('site', ['' => '', 'true' => 'No Site', 'false' => 'Fora do Site'], null, ['id'=>'site', 'style'=>'width:100%;']) !!}</div>
        </div>

        <div class="form-group">
            {!! Form::label('codncm', 'NCM', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-md-10">{!! Form::select2Ncm('codncm', null, ['class' => 'form-control','id'=>'codncm', 'placeholder' => 'NCM']) !!}</div>
        </div>

        <div class="form-group">
            {!! Form::label('preco_de', 'Preço', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-md-10">
                {!! Form::text('preco_de', null, ['class' => 'form-control text-right pull-left', 'id' => 'preco_de', 'placeholder' => 'De', 'style'=>'width:100px; margin-right:10px']) !!}
                {!! Form::text('preco_ate', null, ['class' => 'form-control text-right pull-left', 'id' => 'preco_ate', 'placeholder' => 'Até', 'style'=>'width:100px;']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('criacao_de', 'Criação', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-md-10">
                {!! Form::date('criacao_de', null, ['class' => 'form-control pull-left', 'id' => 'criacao_de', 'placeholder' => 'De', 'style'=>'width:48%; margin-right:10px']) !!}
                {!! Form::date('criacao_ate', null, ['class' => 'form-control pull-left', 'id' => 'criacao_ate', 'placeholder' => 'Até', 'style'=>'width:48%;']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('alteracao_de', 'Alteração', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-md-10">
                {!! Form::date('alteracao_de', null, ['class' => 'form-control pull-left', 'id' => 'alteracao_de', 'placeholder' => 'De', 'style'=>'width:48%; margin-right:10px']) !!}
                {!! Form::date('alteracao_ate', null, ['class' => 'form-control pull-left', 'id' => 'alteracao_ate', 'placeholder' => 'Até', 'style'=>'width:48%;']) !!}
            </div>
        </div>
        
        <div class="form-group">
            <div class="col-md-offset-2 col-md-10">
                <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i> Buscar</button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
    <div class="clearfix"></div>
    </div>
</div>
<br>
<div id="registros">
  <div class="list-group list-group-striped list-group-hover" id="items">
    @foreach($model as $row)
      <div class="list-group-item @if(!empty($row->inativo)) bg-danger @endif"">
        <div class="row item">
            <div class="col-md-1">
                <a href="{{ url("produto/$row->codproduto") }}">
                    <strong>{{ formataCodigo($row->codproduto, 6) }}</strong>
                </a>
                @if($row->codncm)
                <div class="text-muted">
                    <a href="{{ url("ncm/$row->codncm") }}">
                        {{ formataNcm($row->Ncm->ncm) }}
                    </a>
                </div>
                @endif
            </div>
            <div class="col-md-4">
                <a href="{{ url("produto/$row->codproduto") }}">
                    <strong>{!! listagemTitulo($row->produto, $row->inativo) !!}</strong>
                </a>
                @if(!empty($row->codsubgrupoproduto))
                <div>
                    <a href="{{ url("secao-produto/{$row->SubGrupoProduto->GrupoProduto->FamiliaProduto->codsecaoproduto}") }}">
                        {{ $row->SubGrupoProduto->GrupoProduto->FamiliaProduto->SecaoProduto->secaoproduto }}
                    </a>
                    »
                    <a href="{{ url("familia-produto/{$row->SubGrupoProduto->GrupoProduto->codfamiliaproduto}") }}">
                        {{ $row->SubGrupoProduto->GrupoProduto->FamiliaProduto->familiaproduto }}
                    </a>
                    »
                    <a href="{{ url("grupo-produto/{$row->SubGrupoProduto->codgrupoproduto}") }}">
                        {{ $row->SubGrupoProduto->GrupoProduto->grupoproduto }}
                    </a>
                    »
                    <a href="{{ url("sub-grupo-produto/$row->codsubgrupoproduto") }}">
                        {{ $row->SubGrupoProduto->subgrupoproduto }}
                    </a>
                    »
                    <a href="{{ url("marca/$row->codmarca") }}">
                        {{ $row->Marca->marca }}
                    </a>
                    »
                    <span class="text-muted">{{ $row->referencia }}</span>
                </div>
                @endif
            </div>
            <div class="col-md-2">
                <div class="row">
                    <strong class="col-md-6 text-right">
                        {{ formataNumero($row->preco) }}
                    </strong>
                    <div class="col-md-6">
                        {{ $row->UnidadeMedida->sigla }}
                    </div>
                </div>

                @foreach($row->ProdutoEmbalagemS()->orderBy(DB::raw('coalesce(quantidade, 0)'))->get() as $pe)
                    <div class="row">
                        @if (empty($pe->preco))
                            <i class="col-md-6 text-right text-muted">
                                ({{ formataNumero($row->preco * $pe->quantidade) }})
                            </i>
                        @else
                            <strong class="col-md-6 text-right">
                                {{ formataNumero($pe->preco) }}
                            </strong>
                        @endif
                        <div class="col-md-6 text-left">
                            {{ $pe->descricao }}
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="col-md-5 small text-muted" >
                {!! inativo($row->inativo) !!}
                <?php
$pvs = $row->ProdutoVariacaoS()->orderBy(DB::raw("coalesce(variacao, '')"), 'ASC')->get();
?>
                <table class="table table-striped table-condensed table-hover" style="margin-bottom: 1px">
                @foreach ($pvs as $pv)
                    <tr>
                        <td class="col-md-6">
                            @if (!empty($pv->variacao))
                                {{ $pv->variacao }}
                            @else
                                <i>{ Sem Variação }</i>
                            @endif
                            @if (!empty($pv->codmarca))
                                <a href="{{ url("marca/$pv->codmarca") }}">
                                    {{ $pv->Marca->marca }}
                                </a>
                            @endif
                            <div class="pull-right">
                                {{ $pv->referencia }}
                            </div>
                        </td>
                        <td class="col-md-6">
                            <?php
$pbs = $pv->ProdutoBarraS()->leftJoin('tblprodutoembalagem as pe', 'pe.codprodutoembalagem', '=', 'tblprodutobarra.codprodutoembalagem')
    ->orderBy(DB::raw('coalesce(pe.quantidade, 0)'), 'ASC')
    ->with('ProdutoEmbalagem')->get();
?>
                            @foreach ($pbs as $pb)
                                <div class="row">
                                    <div class="col-md-7 text-right">
                                        {{ $pb->barras }}
                                    </div>
                                    <small class="col-md-5">
                                        @if (!empty($pb->codprodutoembalagem))
                                            {{ $pb->ProdutoEmbalagem->descricao }}
                                        @else
                                            {{ $row->UnidadeMedida->sigla }}
                                        @endif
                                    </small>
                                </div>
                            @endforeach
                        </td>
                    </tr>
                @endforeach
                </table>
            </div>
        </div>
      </div>
    @endforeach
    @if (count($model) === 0)
        <h3>Nenhum registro encontrado!</h3>
    @endif
  </div>
  <?php echo $model->appends(Request::session()->get('produto.index'))->render(); ?>
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
function atualizaFiltro()
{
    scroll();
    var frmValues = $('#produto-search').serialize();
    $.ajax({
        type: 'GET',
        url: baseUrl + '/produto',
        data: frmValues,
        dataType: 'html'
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
    var loading_options = {
        finishedMsg: "<div class='end-msg'>Fim dos registros</div>",
        msgText: "<div class='center'>Carregando mais itens...</div>",
        img: baseUrl + '/public/img/ajax-loader.gif'
    };

    $('#items').infinitescroll({
        loading : loading_options,
        navSelector : "#registros .pagination",
        nextSelector : "#registros .pagination li.active + li a",
        itemSelector : "#items div.list-group-item"
    });    

    $("#produto-search").on("change", function (event) {
        $('#items').infinitescroll('destroy');
        atualizaFiltro();
    }).on('submit', function (event){
        event.preventDefault();
        $('#items').infinitescroll('destroy');
        atualizaFiltro();
    });

    $('#site').select2({
        placeholder: 'Site',
        allowClear:true,
        closeOnSelect:true
    });

    $('#preco_de, #preco_ate').autoNumeric('init', {aSep:'.', aDec:',', altDec:'.' });

});
</script>
@endsection
@stop
