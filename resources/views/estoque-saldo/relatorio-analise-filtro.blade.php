@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
    {!! titulo(null, 'Relatório Análise Saldos de Estoque', null) !!}
    <li class='active'>
        <small>
            <a data-toggle="collapse" href="#div-filtro" aria-expanded="false" aria-controls="div-filtro"><span class='glyphicon glyphicon-search'></span></a>
        </small>
    </li>
</ol>
<div class="clearfix"></div>
<div class='collapse in' id='div-filtro'>
    <div class='well well-sm' style="padding:9px 0">
        {!! Form::model($filtro, ['url' => 'estoque-saldo/relatorio-analise', 'method' => 'GET', 'class' => 'form-horizontal', 'id' => 'estoque-saldo-search', 'role' => 'search', 'autocomplete' => 'off' ]) !!}
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
                {!! Form::label('ativo', 'Ativos', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-9">{!! Form::select2('ativo', $arr_ativo, null, ['class' => 'form-control', 'placeholder' => 'Ativos...']) !!}</div>
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


<?php //echo $model->appends(Request::all())->render();?>

@section('inscript')
<script type="text/javascript">
    
    
$(document).ready(function() {

});
</script>
@endsection
@stop
