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
<div class="clearfix"></div>
<div class='collapse' id='div-filtro'>
    <div class='well well-sm' style="padding:9px 0">
        {!! Form::open(['route' => 'produto.index', 'method' => 'GET', 'class' => 'form-horizontal', 'id' => 'saldo-estoque-search', 'role' => 'search', 'autocomplete' => 'off' ]) !!}
        <div class='col-md-6'>
            <div class="form-group">
                {!! Form::label('codestoquelocal', 'Local', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-6">{!! Form::select2EstoqueLocal('codestoquelocal', null, ['class' => 'form-control']) !!}</div>
            </div>
            
            <div class="form-group">
                {!! Form::label('codmarca', 'Marca', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-6">{!! Form::select2Marca('codmarca', null, ['class' => 'form-control']) !!}</div>
            </div>
            
            <div class="form-group">
                {!! Form::label('codproduto', 'Produto', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">{!! Form::select2Produto('codproduto', null, ['class' => 'form-control']) !!}</div>
            </div>
            <div class="form-group">
                {!! Form::label('corredor', 'Localização', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-md-9">
                    {!! Form::number('corredor', null, ['class' => 'form-control pull-left', 'min' => '0', 'step' => 1, 'style'=>'width:60px']) !!}
                    {!! Form::number('prateleira', null, ['class' => 'form-control pull-left', 'min' => '0', 'step' => 1, 'style'=>'width:60px; margin-left:10px']) !!}
                    {!! Form::number('coluna', null, ['class' => 'form-control pull-left', 'min' => '0', 'step' => 1, 'style'=>'width:60px; margin-left:10px']) !!}
                    {!! Form::number('bloco', null, ['class' => 'form-control pull-left', 'min' => '0', 'step' => 1, 'style'=>'width:60px; margin-left:10px']) !!}
                </div>
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
                <div class="col-md-9">{!! Form::select2('saldo', ['' => '', -1=>'Negativo', 1=>'Positivo'], null, ['class' => 'form-control', 'placeholder' => 'Saldo...']) !!}</div>
            </div>
            <div class="form-group">
                {!! Form::label('minimo', 'Mínimo', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-9">{!! Form::select2('minimo', ['' => '', -1=>'Abaixo Mínimo', 1=>'Acima Mínimo'], null, ['class' => 'form-control', 'placeholder' => 'Estoque Mínimo...']) !!}</div>
            </div>
            <div class="form-group">
                {!! Form::label('maximo', 'Máximo', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-9">{!! Form::select2('maximo', ['' => '', -1=>'Abaixo Máximo', 1=>'Acima Máximo'], null, ['class' => 'form-control', 'placeholder' => 'Estoque Máximo...']) !!}</div>
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
