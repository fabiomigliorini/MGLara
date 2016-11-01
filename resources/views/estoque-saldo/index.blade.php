@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
    {!! titulo(null, 'Saldos de Estoque', null) !!}
    <li class='active'>
        <small>
            <a data-toggle="collapse" href="#div-filtro" aria-expanded="false" aria-controls="div-filtro"><span class='glyphicon glyphicon-search'></span></a>
        </small>
    </li>
</ol>
<div class="clearfix"></div>
<div class='collapse' id='div-filtro'>
    <div class='well well-sm' style="padding:9px 0">
        {!! Form::model($filtro, ['route' => 'estoque-saldo.index', 'method' => 'GET', 'class' => 'form-horizontal', 'id' => 'estoque-saldo-search', 'role' => 'search', 'autocomplete' => 'off' ]) !!}
        <div class='col-md-5'>
            <div class="form-group">
                {!! Form::label('codestoquelocal', 'Local', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-4">{!! Form::select2EstoqueLocal('codestoquelocal', null, ['class' => 'form-control']) !!}</div>
            </div>
            
            <div class="form-group">
                {!! Form::label('codmarca', 'Marca', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-5">{!! Form::select2Marca('codmarca', null, ['class' => 'form-control']) !!}</div>
            </div>
            
            <div class="form-group">
                {!! Form::label('codproduto', 'Produto', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">{!! Form::select2Produto('codproduto', null, ['class' => 'form-control']) !!}</div>
            </div>
            <div class="form-group">
                {!! Form::label('corredor', 'Corredor', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-md-9">
                    {!! Form::number('corredor', null, ['class' => 'form-control pull-left', 'min' => '0', 'step' => 1, 'style'=>'width:60px']) !!}
                    {!! Form::number('prateleira', null, ['class' => 'form-control pull-left', 'min' => '0', 'step' => 1, 'style'=>'width:60px; margin-left:10px']) !!}
                    {!! Form::number('coluna', null, ['class' => 'form-control pull-left', 'min' => '0', 'step' => 1, 'style'=>'width:60px; margin-left:10px']) !!}
                    {!! Form::number('bloco', null, ['class' => 'form-control pull-left', 'min' => '0', 'step' => 1, 'style'=>'width:60px; margin-left:10px']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('agrupamento', 'Por', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-md-4">{!! Form::select2('agrupamento', $arr_agrupamentos, $agrupamento_atual, ['class' => 'form-control', 'placeholder' => 'Agrupado por...']) !!}</div>
            </div>
            
        </div>
        
        <div class='col-md-3'>
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
        </div>
        
        <div class='col-md-3'>
            <div class="form-group">
                {!! Form::label('saldo', 'Saldo', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-9">{!! Form::select2('saldo', $arr_saldos, null, ['class' => 'form-control', 'placeholder' => 'Saldo...']) !!}</div>
            </div>
            <div class="form-group">
                {!! Form::label('minimo', 'Mínimo', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-9">{!! Form::select2('minimo', $arr_minimo, null, ['class' => 'form-control', 'placeholder' => 'Estoque Mínimo...']) !!}</div>
            </div>
            <div class="form-group">
                {!! Form::label('maximo', 'Máximo', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-9">{!! Form::select2('maximo', $arr_maximo, null, ['class' => 'form-control', 'placeholder' => 'Estoque Máximo...']) !!}</div>
            </div>
            <div class="form-group">
                {!! Form::label('valor', 'Valor', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-9">{!! Form::select2('valor', $arr_valor, $valor, ['class' => 'form-control', 'placeholder' => 'Valorização...']) !!}</div>
            </div>
            <div class="form-group">
                <div class="col-md-9 col-md-offset-3">
                    <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i> Buscar</button>
                </div>
            </div>
        </div>
        
        {!! Form::close() !!}
        <div class="clearfix"></div>
    </div>
</div>

<div id='div-estoque'>
    <div class="panel-group">

        <div class="panel panel-default panel-condensed">

            <!-- Titulo -->
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-5">
                        <b>Item</b>
                    </div>
                    <div class="col-md-1 text-right">
                        Min <span class='glyphicon glyphicon-arrow-down'></span> 
                        Max <span class='glyphicon glyphicon-arrow-up'></span> 
                    </div>
                    <div class="col-md-3 text-center">
                        <b>Físico</b>
                    </div>
                    <div class="col-md-3 text-center">
                        <b>Fiscal</b>
                    </div>
                </div>
            </div>

        </div>

        @foreach($itens as $coditem => $item)
            <?php
            $filtro[$codigo] = ($coditem=='total')?null:$coditem;
            $filtro['agrupamento'] = ($coditem=='total')?$agrupamento_atual:$agrupamento_proximo;
            ?>
            <div class="panel panel-default panel-condensed">

                <!-- Total Local -->
                <div class="{{ ($coditem == 'total')?'panel-footer':'panel-body' }}">
                        <div class="row">
                            <small class='col-md-1 text-muted'>
                                @if (!empty($item['coditem']))
                                    <a href="{{ url("{$url_detalhes}{$coditem}") }}">
                                        {{ formataCodigo($item['coditem'], ($codigo=='codproduto')?6:8) }}
                                    </a>
                                    @if ($agrupamento_atual != 'variacao')
                                        <span class='pull-right'>
                                            <a href="{{ urlArrGet($filtro, 'estoque-saldo') }}">
                                                    <span class='glyphicon glyphicon-zoom-in'></span>
                                            </a>
                                        </span>
                                    @endif
                                @endif
                            </small>
                            <a data-toggle="collapse" href="#collapseItem{{ $coditem }}">
                                <div class='col-md-3'>
                                    <b>
                                        {{ ($coditem == 'total')?'Total':$item['item'] }}
                                    </b>
                                </div>
                                <div class='col-md-2 text-right'>
                                    {!! formataEstoqueMinimoMaximo($item['estoquelocal']['total']['estoqueminimo'], $item['estoquelocal']['total']['estoquemaximo'], $item['estoquelocal']['total']['fisico']['saldoquantidade']) !!}
                                </div>
                                <div class='col-md-2 text-right {{ ($item['estoquelocal']['total']['fisico']['saldoquantidade'] < 0)?'text-danger':'' }}'>
                                    {{ formataNumero($item['estoquelocal']['total']['fisico']['saldoquantidade'], 0) }}
                                </div>
                                <div class='col-md-1 text-right text-muted'>
                                    <small>
                                        {{ formataNumero($item['estoquelocal']['total']['fisico']['saldovalor'], 2) }}
                                    </small>
                                </div>
                                <div class='col-md-2 text-right {{ ($item['estoquelocal']['total']['fiscal']['saldoquantidade'] < 0)?'text-danger':'' }}'>
                                    {{ formataNumero($item['estoquelocal']['total']['fiscal']['saldoquantidade'], 0) }}
                                </div>
                                <div class='col-md-1 text-right text-muted'>
                                    <small>
                                        {{ formataNumero($item['estoquelocal']['total']['fiscal']['saldovalor'], 2) }}
                                    </small>
                                </div>
                            </a>
                        </div>
                </div>

                <!-- Variacoes do Produto -->
                <div id="collapseItem{{ $coditem }}" class="panel-collapse collapse">
                    <ul class="list-group list-group-condensed list-group-striped list-group-hover list-group-condensed">
                        @foreach ($item['estoquelocal'] as $codestoquelocal => $local)
                            <?php
                            if ($codestoquelocal == 'total')
                                continue;
                            ?>
                            <li class="list-group-item">
                                
                                <div class="row">
                                    <div class='col-md-2 text-muted'>
                                    </div>
                                    <div class='col-md-2 text-muted'>
                                        <small>
                                            <a href="{{ urlArrGet($filtro + ['codestoquelocal' => $codestoquelocal], 'estoque-saldo') }}" class="">
                                                <span class='glyphicon glyphicon-zoom-in'></span>
                                            </a>
                                        </small>
                                        &nbsp;
                                        {{ $local['estoquelocal'] }}
                                    </div>
                                    <div class='col-md-2 text-right'>
                                        {!! formataEstoqueMinimoMaximo($local['estoqueminimo'], $local['estoquemaximo'], $local['fisico']['saldoquantidade']) !!}
                                    </div>
                                    <div class='col-md-2 text-right {{ ($local['fisico']['saldoquantidade'] < 0)?'text-danger':'' }}'>
                                        @if (!empty($local['fisico']['codestoquesaldo']))
                                            <small>
                                                <a href="{{ url("estoque-saldo/{$local['fisico']['codestoquesaldo']}") }}" class="">
                                                    <span class='glyphicon glyphicon-zoom-in'></span>                                                
                                                </a>
                                            </small>
                                            &nbsp;
                                        @endif
                                        {{ formataNumero($local['fisico']['saldoquantidade'], 0) }}
                                    </div>
                                    <div class='col-md-1 text-right text-muted'>
                                        <small>
                                            {{ formataNumero($local['fisico']['saldovalor'], 2) }}
                                        </small>
                                    </div>
                                    <div class='col-md-2 text-right {{ ($local['fiscal']['saldoquantidade'] < 0)?'text-danger':'' }}'>
                                        @if (!empty($local['fiscal']['codestoquesaldo']))
                                            <small>
                                                <a href="{{ url("estoque-saldo/{$local['fiscal']['codestoquesaldo']}") }}" class="">
                                                    <span class='glyphicon glyphicon-zoom-in'></span>                                                
                                                </a>
                                            </small>
                                            &nbsp;
                                        @endif
                                        {{ formataNumero($local['fiscal']['saldoquantidade'], 0) }}
                                    </div>
                                    <div class='col-md-1 text-right text-muted'>
                                        <small>
                                            {{ formataNumero($local['fiscal']['saldovalor'], 2) }}
                                        </small>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

            </div>
        @endforeach

    </div>
</div>

<?php //echo $model->appends(Request::all())->render();?>

@section('inscript')
<script type="text/javascript">
    
function atualizaFiltro()
{
    var frmValues = $('#estoque-saldo-search').serialize();
    $.ajax({
        type: 'GET',
        url: baseUrl + '/estoque-saldo',
        data: frmValues,
        dataType: 'html'
    })
    .done(function (data) {
        $('#div-estoque').html(jQuery(data).find('#div-estoque').html());
    })
    .fail(function () {
        console.log('Erro no filtro');
    });
}
    
$(document).ready(function() {

    $("#estoque-saldo-search").on("change", function (e) {
        if($('#estoque-saldo-search')[0].checkValidity()){
            $("#estoque-saldo-search").submit();
        }
        return false;
        
    }).on('submit', function (e){
        e.preventDefault();
        atualizaFiltro();
    });

});
</script>
@endsection
@stop
