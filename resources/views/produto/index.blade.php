@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
    {!! titulo(null, 'Produtos', null) !!}
    <li class='active'>
        <small>
            <a href="<?php echo url('produto/create'); ?>"><span class="glyphicon glyphicon-plus"></span></a>
            <a class="" data-toggle="collapse" href="#div-filtro" aria-expanded="false" aria-controls="div-filtro"><span class='glyphicon glyphicon-search'></span></a>
        </small>
    </li>
</ol>
<div class="clearfix"></div>
<div class='collapse' id='div-filtro'>
    <div class='well well-sm' style="padding:9px 0">
    {!! Form::model(Request::session()->get('produto.index'),
    [
        'route' => 'produto.index',
        'method' => 'GET',
        'class' => 'form-horizontal',
        'id' => 'produto-search',
        'role' => 'search',
        'autocomplete' => 'off'
    ])!!}
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('codproduto', '#', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-md-4">{!! Form::number('codproduto', null, ['class' => 'form-control', 'placeholder' => '#']) !!}</div>
        </div>

        <div class="form-group">
            {!! Form::label('barras', 'Barras', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-md-6">{!! Form::text('barras', null, ['class' => 'form-control', 'placeholder' => 'Barras']) !!}</div>
        </div>

        <div class="form-group">
            {!! Form::label('produto', 'Descrição', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-md-9">{!! Form::text('produto', null, ['class' => 'form-control', 'placeholder' => 'Descrição']) !!}</div>
        </div>
        
        <div class="form-group">
            {!! Form::label('referencia', 'Referência', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-md-6">{!! Form::text('referencia', null, ['class' => 'form-control', 'placeholder' => 'Referência']) !!}</div>
        </div>

        <div class="form-group">
            {!! Form::label('preco_de', 'Preço', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-md-9">
                {!! Form::number('preco_de', null, ['class' => 'form-control text-right pull-left', 'id' => 'preco_de', 'placeholder' => 'De', 'style'=>'width:100px; margin-right:10px', 'step'=>'0.01']) !!}
                {!! Form::number('preco_ate', null, ['class' => 'form-control text-right pull-left', 'id' => 'preco_ate', 'placeholder' => 'Até', 'style'=>'width:100px;', 'step'=>'0.01']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('ativo', 'Ativo', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-md-4">{!! Form::select2Ativo('ativo', null, ['class'=> 'form-control', 'id' => 'ativo']) !!}</div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('codsecaoproduto', 'Seção', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-md-9">{!! Form::select2SecaoProduto('codsecaoproduto', null, ['class'=> 'form-control', 'id' => 'codsecaoproduto', 'placeholder' => 'Seção']) !!}</div>
        </div>

        <div class="form-group">
            {!! Form::label('codfamiliaproduto', 'Família', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-md-9">{!! Form::select2FamiliaProduto('codfamiliaproduto', null, ['class' => 'form-control','id'=>'codfamiliaproduto', 'placeholder' => 'Família', 'codsecaoproduto'=>'codsecaoproduto',  'ativo'=>'9']) !!}</div>
        </div>

        <div class="form-group">
            {!! Form::label('codgrupoproduto', 'Grupo', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-md-9">{!! Form::select2GrupoProduto('codgrupoproduto', null, ['class' => 'form-control','id'=>'codgrupoproduto', 'placeholder' => 'Grupo', 'codfamiliaproduto'=>'codfamiliaproduto', 'ativo'=>'9']) !!}</div>
        </div>

        <div class="form-group">
            {!! Form::label('codsubgrupoproduto', 'SubGrupo', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-md-9">{!! Form::select2SubGrupoProduto('codsubgrupoproduto', null, ['class' => 'form-control','id'=>'codsubgrupoproduto', 'placeholder' => 'Sub Grupo', 'codgrupoproduto'=>'codgrupoproduto', 'ativo'=>'9']) !!}</div>
        </div>

        <div class="form-group">
            {!! Form::label('codmarca', 'Marca', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-md-9">{!! Form::select2Marca('codmarca', null, ['class' => 'form-control','id'=>'codmarca', 'style'=>'width:160px']) !!}</div>
        </div>
        
    </div>
    
    <div class="col-md-5">
        <div class="form-group">
            {!! Form::label('codtributacao', 'Tributação', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-md-4">{!! Form::select2Tributacao('codtributacao', null, ['placeholder'=>'Tributação',  'class'=> 'form-control', 'id' => 'codtributacao']) !!}</div>
        </div>

        <div class="form-group">
            {!! Form::label('codncm', 'NCM', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-md-10">{!! Form::select2Ncm('codncm', null, ['class' => 'form-control','id'=>'codncm', 'placeholder' => 'NCM']) !!}</div>
        </div>

        <div class="form-group">
            {!! Form::label('site', 'Site', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-md-3">{!! Form::select('site', ['' => '', 'true' => 'No Site', 'false' => 'Fora do Site'], null, ['id'=>'site', 'style'=>'width:100%;']) !!}</div>
        </div>

        <div class="form-group">
            {!! Form::label('criacao_de', 'Criação', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-md-10">
                {!! Form::date('criacao_de', null, ['class' => 'form-control pull-left', 'id' => 'criacao_de', 'placeholder' => 'De', 'style'=>'width:160px; margin-right:10px']) !!}
                {!! Form::date('criacao_ate', null, ['class' => 'form-control pull-left', 'id' => 'criacao_ate', 'placeholder' => 'Até', 'style'=>'width:160px;']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('alteracao_de', 'Alteração', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-md-10">
                {!! Form::date('alteracao_de', null, ['class' => 'form-control pull-left', 'id' => 'alteracao_de', 'placeholder' => 'De', 'style'=>'width:160px; margin-right:10px']) !!}
                {!! Form::date('alteracao_ate', null, ['class' => 'form-control pull-left', 'id' => 'alteracao_ate', 'placeholder' => 'Até', 'style'=>'width:160px;']) !!}
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
    scroll();
    $('#site').select2({
        placeholder: 'Site',
        allowClear:true,
        closeOnSelect:true
    });

    $('#produto-search input').blur(function(e) {
        var controlgroup = $(e.target.parentNode);
        if (!e.target.checkValidity()) {
            controlgroup.addClass('has-error');
            e.target.reportValidity();
        } else {
            controlgroup.removeClass('has-error');
        }
    }); 

    $("#produto-search").on("change", function (e) {
        if($('#produto-search')[0].checkValidity()){
            $("#produto-search").submit();
        }
        return false;
        
    }).on('submit', function (e){
        e.preventDefault();
        $('#items').infinitescroll('destroy');
        atualizaFiltro();
    });

    var alteracao_de = $('#alteracao_de').val();
    if(alteracao_de.length > 0 ){
        $('#alteracao_ate').attr('min', alteracao_de);
    }
    $('#alteracao_de').on('change', function(e) {
        e.preventDefault();
        var valor = $(this).val();
        if(valor.length === 0 ) {
            $('#alteracao_ate').empty();
            $('#alteracao_ate').attr('min', '');
        } else {
            $('#alteracao_ate').attr('min', valor);
        }
        
    });
    
    var alteracao_ate = $('#alteracao_ate').val();
    if(alteracao_ate.length > 0){
        $('#alteracao_de').attr('max', alteracao_ate);
    }
    $('#alteracao_ate').on('change', function(e) {        
        e.preventDefault();
        var valor = $(this).val();
        if(valor.length === 0 ) {
            $('#alteracao_de').empty();
            $('#alteracao_de').attr('max', '');
        } else {
            $('#alteracao_de').attr('max', valor);
        }
    });
    
    var criacao_de = $('#criacao_de').val();
    if(criacao_de.length > 0 ){
        $('#criacao_ate').attr('min', criacao_de);
    }
    $('#criacao_de').on('change', function(e) {
        e.preventDefault();
        var valor = $(this).val();
        if(valor.length === 0 ) {
            $('#criacao_ate').empty();
            $('#criacao_ate').attr('min', '');
        } else {
            $('#criacao_ate').attr('min', valor);
        }
        
    });
    
    var criacao_ate = $('#criacao_ate').val();
    if(criacao_ate.length > 0){
        $('#criacao_de').attr('max', criacao_ate);
    }
    $('#criacao_ate').on('change', function(e) {        
        e.preventDefault();
        var valor = $(this).val();
        if(valor.length === 0 ) {
            $('#criacao_de').empty();
            $('#criacao_de').attr('max', '');
        } else {
            $('#criacao_de').attr('max', valor);
        }
    });

    function setPrecoMin() {
        var valor = $('#preco_de').val();
        if(valor.length === 0 ) {
            $('#preco_ate').empty();
            $('#preco_ate').attr('min', '');
        } else {
            $('#preco_ate').attr('min', valor);
        }
    };

    function setPrecoMax() {
        var preco_de = $('#preco_de').val();
        var preco_ate = $('#preco_ate').val();
        if(preco_de.length === 0 ) {
            $('#preco_de').attr('max', preco_ate);
        }
    };
    
    var preco_de = $('#preco_de').val();
    if(preco_de.length > 0 ){
        $('#preco_ate').attr('min', preco_de);
    }
    
    var preco_ate = $('#preco_ate').val();
    if(preco_de.length > 0 ){
        $('#preco_de').attr('min', preco_ate);
    }

    $('#preco_de').on('change', function(e) {
        e.preventDefault();
        setPrecoMin();
    }).blur(function () {
        setPrecoMin();
    });

    $('#preco_ate').on('change', function(e) {
        e.preventDefault();
        setPrecoMax();
    }).blur(function () {
        setPrecoMax();
    });

});
</script>
@endsection
@stop
