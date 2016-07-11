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
<h1 class="header">{!! titulo(null, 'Produtos', null) !!}  </h1>
<hr>
<?php
use MGLara\Models\SecaoProduto;
use MGLara\Models\ProdutoVariacao;
$filtro = Request::session()->get('produto.index');

?>
<div class="search-bar">
{!! Form::model($filtro, 
[
    'route' => 'produto.index', 
    'method' => 'GET', 
    'class' => 'form-inline', 
    'id' => 'produto-search', 
    'role' => 'search', 
    'autocomplete' => 'off'
])!!}
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
        {!! Form::select2Marca('codmarca', null, ['class' => 'form-control','id'=>'codmarca', 'style'=>'width:160px']) !!}
    </div>

    <div class="form-group">
        {!! Form::select2SecaoProduto('codsecaoproduto', null, ['required' => true, 'class'=> 'form-control', 'id' => 'codsecaoproduto', 'style'=>'width:160px', 'placeholder' => 'Seção']) !!}
    </div>

    <div class="form-group">
        {!! Form::select2FamiliaProduto('codfamiliaproduto', null, ['class' => 'form-control','id'=>'codfamiliaproduto', 'style'=>'width:160px', 'placeholder' => 'Família']) !!}
    </div>

    <div class="form-group">
        {!! Form::select2GrupoProduto('codgrupoproduto', null, ['class' => 'form-control','id'=>'codgrupoproduto', 'style'=>'width:160px', 'placeholder' => 'Grupo']) !!}
    </div>

    <div class="form-group">
        {!! Form::select2SubGrupoProduto('codsubgrupoproduto', null, ['class' => 'form-control','id'=>'codsubgrupoproduto', 'style'=>'width:160px', 'placeholder' => 'Sub Grupo']) !!}
    </div>

    <div class="form-group">
        {!! Form::text('referencia', null, ['class' => 'form-control', 'style'=>'width:165px', 'placeholder' => 'Referencia']) !!}
    </div>

    <div class="form-group">
        {!! Form::select('inativo', ['' => '', 1 => 'Ativos', 2 => 'Inativos'], null, ['style' => 'width: 120px', 'id'=>'inativo']) !!}
    </div>

    <div class="form-group">
        {!! Form::select2Tributacao('codtributacao', null, ['placeholder'=>'Tributação',  'class'=> 'form-control', 'id' => 'codtributacao', 'style'=>'width:120px']) !!}
    </div>

    <div class="form-group">
        {!! Form::select('site', ['' => '', 'true' => 'No Site', 'false' => 'Fora do Site'], null, ['style' => 'width: 120px', 'id'=>'site']) !!}
    </div>      

    <div class="form-group">
        {!! Form::select2Ncm('codncm', null, ['class' => 'form-control','id'=>'codncm', 'style'=>'width:450px', 'placeholder' => 'NCM']) !!}
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
    <div class="form-group pull-right">
        <button type="submit" class="btn btn-default pull-right">
            <i class='glyphicon glyphicon-search'></i>
            Buscar
        </button>
    </div>

{!! Form::close() !!}
</div>

<br>
<div id="registros">
  <div class="list-group group-list-striped group-list-hover" id="items">
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
function atualizaFiltro()
{
    var frmValues = $('#produto-search').serialize();
    $.ajax({
        type: 'GET',
        url: baseUrl + '/produto',
        data: frmValues
    })
    .done(function (data) {
        $('#items').html(jQuery(data).find('#items').html()); 
    })
    .fail(function () {
        console.log('Erro no filtro');
    });
    event.preventDefault(); 
    
}
    
$(document).ready(function() {
    //$('ul.pagination').removeClass('hide');
    
    $("#produto-search").on("change", function (event) {
        atualizaFiltro();
    });
    
    $(document).on('dp.change', '#criacao_de, #criacao_ate, #alteracao_de, #alteracao_ate', function() {
        atualizaFiltro();
    });
    
    $('#inativo').select2({
        placeholder: 'Inativo',
        allowClear: true,
        closeOnSelect: true
    });
    
    $('#site').select2({
        placeholder: 'Site',
        allowClear:true,
        closeOnSelect:true
    });
    
    $('#preco_de, #preco_ate').autoNumeric('init', {aSep:'.', aDec:',', altDec:'.' });
    $('#criacao_de, #criacao_ate, #alteracao_de, #alteracao_ate').datetimepicker({
        useCurrent: false,
        showClear: true,
        locale: 'pt-br',
        format: 'DD/MM/YY'
    });
});
</script>
@endsection
@stop