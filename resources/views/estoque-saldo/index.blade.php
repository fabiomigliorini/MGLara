@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
  <div class="container-fluid"> 
    <ul class="nav navbar-nav">
    </ul>
  </div>
</nav>
<h1 class="header">
    {!! titulo(null, $titulo, null) !!}  
    <a class="btn btn-primary pull-right" role="button" data-toggle="collapse" href="#div-filtro" aria-expanded="false" aria-controls="div-filtro">
        <span class='glyphicon glyphicon-search'></span>
    </a>
    
</h1>
<br>

<div class='clearfix'>
</div>


<div class='collapse in' id='div-filtro'>
    <div class='well well-sm'>
        {!! Form::open(['route' => 'produto.index', 'method' => 'GET', 'class' => 'form-horizontal', 'id' => 'saldo-estoque-search', 'role' => 'search', 'autocomplete' => 'off' ]) !!}
        <div class='col-md-6'>
            
            <div class="form-group">
                <label for="codestoquelocal" class="col-sm-2 control-label">
                    Local
                </label>
                <div class="col-sm-4">
                    {!! Form::select2EstoqueLocal('codestoquelocal', null, ['class' => 'form-control']) !!}
                </div>
            </div>
            
            <div class="form-group">
                <label for="codmarca" class="col-sm-2 control-label">
                    Marca
                </label>
                <div class="col-sm-4">
                    {!! Form::select2Marca('codmarca', null, ['class' => 'form-control']) !!}
                </div>
            </div>
            
            <div class="form-group">
                <label for="codproduto" class="col-sm-2 control-label">
                    Produto
                </label>
                <div class="col-sm-10">
                    {!! Form::select2Produto('codproduto', null, ['class' => 'form-control']) !!}
                </div>
            </div>

            <div class="form-group">
                <label for="corredor" class="col-sm-2 control-label">
                    Localização
                </label>
                <div class="input-group">
                    {!! Form::number('corredor', null, ['class' => 'form-control col-md-2', 'min' => '0', 'step' => 1]) !!}
                    {!! Form::number('prateleira', null, ['class' => 'form-control', 'min' => '0', 'step' => 1]) !!}
                    {!! Form::number('coluna', null, ['class' => 'form-control', 'min' => '0', 'step' => 1]) !!}
                    {!! Form::number('bloco', null, ['class' => 'form-control', 'min' => '0', 'step' => 1]) !!}
                </div>
            </div>


            
        </div>
        
        
            <div class="input-group col-md-2">
                {!! Form::select2SecaoProduto('codsecaoproduto', null, ['class' => 'form-control']) !!}
            </div>
            <div class="input-group col-md-2">
                {!! Form::select2FamiliaProduto('codfamiliaproduto', null, ['class' => 'form-control']) !!}
            </div>
            <div class="input-group col-md-2">
                {!! Form::select2GrupoProduto('codgrupoproduto', null, ['class' => 'form-control']) !!}
            </div>
            <div class="input-group col-md-2">
                {!! Form::select2SubGrupoProduto('codsubgrupoproduto', null, ['class' => 'form-control']) !!}
            </div>
            <div class="input-group col-md-2">
                {!! Form::select2('saldo', ['' => '', -1=>'Negativo', 1=>'Positivo'], null, ['class' => 'form-control', 'placeholder' => 'Saldo...']) !!}
            </div>
            <div class="input-group col-md-2">
                {!! Form::select2('minimo', ['' => '', -1=>'Abaixo Mínimo', 1=>'Acima Mínimo'], null, ['class' => 'form-control', 'placeholder' => 'Estoque Mínimo...']) !!}
            </div>
            <div class="input-group col-md-2">
                {!! Form::select2('maximo', ['' => '', -1=>'Abaixo Máximo', 1=>'Acima Máximo'], null, ['class' => 'form-control', 'placeholder' => 'Estoque Máximo...']) !!}
            </div>
        {!! Form::close() !!}
    </div>
</div>

<div class="search-bar">
    <?php
    /*
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
        {!! Form::select2SecaoProduto('codsecaoproduto', null, ['class'=> 'form-control', 'id' => 'codsecaoproduto', 'style'=>'width:160px', 'placeholder' => 'Seção']) !!}
    </div>

    <div class="form-group">
        {!! Form::select2FamiliaProduto('codfamiliaproduto', null, ['class' => 'form-control','id'=>'codfamiliaproduto', 'style'=>'width:160px', 'placeholder' => 'Família', 'ativo'=>'9']) !!}
    </div>

    <div class="form-group">
        {!! Form::select2GrupoProduto('codgrupoproduto', null, ['class' => 'form-control','id'=>'codgrupoproduto', 'style'=>'width:160px', 'placeholder' => 'Grupo', 'ativo'=>'9']) !!}
    </div>

    <div class="form-group">
        {!! Form::select2SubGrupoProduto('codsubgrupoproduto', null, ['class' => 'form-control','id'=>'codsubgrupoproduto', 'style'=>'width:160px', 'placeholder' => 'Sub Grupo', 'ativo'=>'9']) !!}
    </div>

    <div class="form-group">
        {!! Form::text('referencia', null, ['class' => 'form-control', 'style'=>'width:165px', 'placeholder' => 'Referencia']) !!}
    </div>

    <div class="form-group">
        {!! Form::select2Ativo('ativo', null, ['class'=> 'form-control', 'id' => 'ativo', 'style'=>'width:120px']) !!}
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
        {!! Form::date('criacao_de', null, ['class' => 'form-control', 'id' => 'criacao_de', 'placeholder' => 'De']) !!}
        {!! Form::date('criacao_ate', null, ['class' => 'form-control', 'id' => 'criacao_ate', 'placeholder' => 'Até']) !!}
    </div>

    <strong>Alteração</strong>
    <div class="form-group">
        {!! Form::date('alteracao_de', null, ['class' => 'form-control', 'id' => 'alteracao_de', 'placeholder' => 'De']) !!}
        {!! Form::date('alteracao_ate', null, ['class' => 'form-control', 'id' => 'alteracao_ate', 'placeholder' => 'Até']) !!}
    </div>
    <div class="form-group pull-right">
        <button type="submit" class="btn btn-default pull-right">
            <i class='glyphicon glyphicon-search'></i>
            Buscar
        </button>
    </div>
     * 
     */
    ?>
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
            $parametros[$codigo] = ($coditem=='total')?null:$coditem;
            ?>
            <div class="panel panel-default panel-condensed">

                <!-- Total Local -->
                <div class="{{ ($coditem == 'total')?'panel-footer':'panel-body' }}">
                        <div class="row">
                            <div class='col-md-1 text-muted'>
                                @if (!empty($item['coditem']))
                                    <small>
                                        {{ formataCodigo($item['coditem']) }}
                                        <a href="{{ urlArrGet($parametros, 'estoque-saldo') }}" class="pull-right">
                                                <span class='glyphicon glyphicon-zoom-in'></span>
                                        </a>
                                    </small>
                                @endif
                            </div>
                            <a data-toggle="collapse" href="#collapseItem{{ $coditem }}">
                                <div class='col-md-3'>
                                    <b>
                                        {{ ($coditem == 'total')?'Total':$item['item'] }}
                                    </b>
                                </div>
                                <div class='col-md-2 text-right'>
                                    {!! formataEstoqueMinimoMaximo($item['estoquelocal']['total']['estoqueminimo'], $item['estoquelocal']['total']['estoquemaximo'], $item['estoquelocal']['total']['fisico']['saldoquantidade']) !!}
                                </div>
                                <div class='col-md-2 text-right'>
                                        {{ formataNumero($item['estoquelocal']['total']['fisico']['saldoquantidade'], 0) }}
                                </div>
                                <div class='col-md-1 text-right text-muted'>
                                    <small>
                                        {{ formataNumero($item['estoquelocal']['total']['fisico']['saldovalor'], 2) }}
                                    </small>
                                </div>
                                <div class='col-md-2 text-right'>
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
                                            <a href="{{ urlArrGet($parametros + ['codestoquelocal' => $codestoquelocal], 'estoque-saldo') }}" class="">
                                                    <span class='glyphicon glyphicon-zoom-in'></span>
                                            </a>
                                        </small>
                                        &nbsp;
                                        {{ $local['estoquelocal'] }}
                                    </div>
                                    <div class='col-md-2 text-right'>
                                        {!! formataEstoqueMinimoMaximo($local['estoqueminimo'], $local['estoquemaximo'], $local['fisico']['saldoquantidade']) !!}
                                    </div>
                                    <div class='col-md-2 text-right'>
                                        {{ formataNumero($local['fisico']['saldoquantidade'], 0) }}
                                    </div>
                                    <div class='col-md-1 text-right text-muted'>
                                        <small>
                                            {{ formataNumero($local['fisico']['saldovalor'], 2) }}
                                        </small>
                                    </div>
                                    <div class='col-md-2 text-right'>
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
  $(document).ready(function() {
      
  });
</script>
@endsection
@stop
