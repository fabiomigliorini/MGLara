@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
    {!! titulo(null, 'Relatório Saldo Físico x Fiscal', null) !!}
    <li class='active'>
        <small>
            <a data-toggle="collapse" href="#div-filtro" aria-expanded="false" aria-controls="div-filtro"><span class='glyphicon glyphicon-search'></span></a>
        </small>
    </li>
</ol>
<div class="clearfix"></div>
<div class='collapse in' id='div-filtro'>
    <div class='well well-sm' style="padding:9px 0">
        {!! Form::model($filtro, ['url' => 'estoque-saldo/relatorio-fisico-fiscal', 'method' => 'GET', 'class' => 'form-horizontal', 'id' => 'estoque-saldo-search', 'role' => 'search', 'autocomplete' => 'off' ]) !!}
        <div class='col-md-4'>

            <div class="form-group">
                {!! Form::label('codempresa', 'Empresa', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-9">{!! Form::select2Empresa('codempresa', null, ['class' => 'form-control', 'required' => true]) !!}</div>
            </div>

            <div class="form-group">
                {!! Form::label('mes', 'Mês / Ano', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-7">
                    <div class="input-group">
                        {!! Form::number('mes', null, ['class'=> 'form-control text-center', 'placeholder' => 'Mês', 'step' => 1, 'min' => '1', 'max' => '12', 'required' => true]) !!}
                        <span class="input-group-addon">/</span>
                        {!! Form::number('ano', null, ['class'=> 'form-control text-center', 'placeholder' => 'Ano', 'step' => 1, 'min' => 2015, 'max' => 2030, 'required' => true]) !!}
                    </div>
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('codestoquelocal', 'Local', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-6">{!! Form::select2EstoqueLocal('codestoquelocal', null, ['class' => 'form-control']) !!}</div>
            </div>

            <div class="form-group">
                {!! Form::label('produto', 'Produto', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-9">{!! Form::text('produto', null, ['class' => 'form-control', 'placeholder' => 'Descrição do Produto...']) !!}</div>
            </div>
          
            <div class="form-group">
                {!! Form::label('preco_de', 'Preço', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-9">
                    {!! Form::number('preco_de', null, ['class' => 'form-control text-right pull-left', 'id' => 'preco_de', 'placeholder' => 'De', 'style'=>'width:100px; margin-right:10px', 'step'=>'0.01']) !!}
                    {!! Form::number('preco_ate', null, ['class' => 'form-control text-right pull-left', 'id' => 'preco_ate', 'placeholder' => 'Até', 'style'=>'width:100px;', 'step'=>'0.01']) !!}
                </div>
            </div>

        </div>

        <div class='col-md-4'>
            <div class="form-group">
                {!! Form::label('codsecaoproduto', 'Seção', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-9">{!! Form::select2SecaoProduto('codsecaoproduto', null, ['class'=> 'form-control', 'id' => 'codsecaoproduto', 'placeholder' => 'Seção']) !!}</div>
            </div>

            <div class="form-group">
                {!! Form::label('codfamiliaproduto', 'Família', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-9">{!! Form::select2FamiliaProduto('codfamiliaproduto', null, ['class' => 'form-control','id'=>'codfamiliaproduto', 'placeholder' => 'Família', 'codsecaoproduto'=>'codsecaoproduto',  'ativo'=>'9']) !!}</div>
            </div>

            <div class="form-group">
                {!! Form::label('codgrupoproduto', 'Grupo', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-9">{!! Form::select2GrupoProduto('codgrupoproduto', null, ['class' => 'form-control','id'=>'codgrupoproduto', 'placeholder' => 'Grupo', 'codfamiliaproduto'=>'codfamiliaproduto', 'ativo'=>'9']) !!}</div>
            </div>

            <div class="form-group">
                {!! Form::label('codsubgrupoproduto', 'SubGrupo', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-9">{!! Form::select2SubGrupoProduto('codsubgrupoproduto', null, ['class' => 'form-control','id'=>'codsubgrupoproduto', 'placeholder' => 'Sub Grupo', 'codgrupoproduto'=>'codgrupoproduto', 'ativo'=>'9']) !!}</div>
            </div>
          
            <div class="form-group">
                {!! Form::label('codmarca', 'Marca', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-6">{!! Form::select2Marca('codmarca', null, ['class' => 'form-control']) !!}</div>
            </div>
        </div>

        <div class='col-md-4'>
            <div class="form-group">
                {!! Form::label('codncm', 'NCM', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-9">{!! Form::select2Ncm('codncm', null, ['class' => 'form-control','id'=>'codncm', 'placeholder' => 'NCM']) !!}</div>
            </div>
        <div class="form-group">
            {!! Form::label('codtributacao', 'Tributação', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-md-9">{!! Form::select2Tributacao('codtributacao', null, ['placeholder'=>'Tributação',  'class'=> 'form-control', 'id' => 'codtributacao']) !!}</div>
        </div>


            <div class="form-group">
                {!! Form::label('saldo_fiscal', 'Fiscal', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-5">{!! Form::select2('saldo_fiscal', ['' => '', -1=>'Negativo', 1=>'Positivo', 9=>'Zerado'], null, ['class' => 'form-control', 'placeholder' => 'Fiscal...']) !!}</div>
            </div>
            <div class="form-group">
                {!! Form::label('saldo_fisico', 'Fisico', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-5">{!! Form::select2('saldo_fisico', ['' => '', -1=>'Negativo', 1=>'Positivo', 9=>'Zerado'], null, ['class' => 'form-control', 'placeholder' => 'Fisico...']) !!}</div>
            </div>
            <div class="form-group">
                {!! Form::label('saldo_fisico_fiscal', 'Comparação', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-7">{!! Form::select2('saldo_fisico_fiscal', ['' => '', -1=>'Faltando Fiscal', 1=>'Sobrando Fiscal'], null, ['class' => 'form-control', 'placeholder' => 'Fisico x Fiscal...']) !!}</div>
            </div>
            <div class="form-group">
                {!! Form::label('marcacontrolada', 'Marcas Controladas', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-7">{!! Form::select2MarcaControlada('marcacontrolada', null, ['class' => 'form-control']) !!}</div>
            </div>            
            <div class="form-group">
                <div class="col-md-9 col-md-offset-4">
                    <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i> Buscar</button>
                </div>
            </div>
        </div>

        {!! Form::close() !!}
        <div class="clearfix"></div>
    </div>
</div>


<?php //echo $model->appends(Request::all())->render();?>

@section('inscript')
<script type="text/javascript">

$(document).ready(function() {

    $('#preco_ate').attr('min', $('#preco_de').val());
    $('#preco_de').attr('max', $('#preco_ate').val());
    
    $('#preco_de').on('change', function(e) {
        $('#preco_ate').attr('min', $('#preco_de').val());
    });

    $('#preco_ate').on('change', function(e) {
        $('#preco_de').attr('max', $('#preco_ate').val());
    });

});
</script>
@endsection
@stop
