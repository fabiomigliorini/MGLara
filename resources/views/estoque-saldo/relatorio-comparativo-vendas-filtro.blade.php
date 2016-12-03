@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
    {!! titulo(null, 'Relatório Vendas Filial X Saldo Depósito', null) !!}
    <li class='active'>
        <small>
            <a data-toggle="collapse" href="#div-filtro" aria-expanded="false" aria-controls="div-filtro"><span class='glyphicon glyphicon-search'></span></a>
        </small>
    </li>
</ol>
<div class="clearfix"></div>
<div class='collapse in' id='div-filtro'>
    <div class='well well-sm' style="padding:9px 0">
        {!! Form::model($filtro, ['url' => 'estoque-saldo/relatorio-comparativo-vendas', 'method' => 'GET', 'class' => 'form-horizontal', 'id' => 'estoque-saldo-search', 'role' => 'search', 'autocomplete' => 'off' ]) !!}
        <div class='col-md-4'>
            <div class="form-group">
                {!! Form::label('codestoquelocaldeposito', 'Depósito', ['class' => 'col-sm-4 control-label']) !!}
                <div class="col-sm-4">{!! Form::select2EstoqueLocal('codestoquelocaldeposito', null, ['class' => 'form-control', 'required' => true]) !!}</div>
            </div>
            <div class="form-group">
                {!! Form::label('minimo', 'Saldo Depósito', ['class' => 'col-sm-4 control-label']) !!}
                <div class="col-md-8">{!! Form::select2('saldo_deposito', $arr_saldo_deposito, null, ['class' => 'form-control', 'placeholder' => 'Saldo Depósito...']) !!}</div>
            </div>
            <div class="form-group">
                {!! Form::label('codmarca', 'Marca', ['class' => 'col-sm-4 control-label']) !!}
                <div class="col-sm-5">{!! Form::select2Marca('codmarca', null, ['class' => 'form-control']) !!}</div>
            </div>
        </div>
        
        <div class='col-md-4'>
            <div class="form-group">
                {!! Form::label('codestoquelocalfilial', 'Loja', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-4">{!! Form::select2EstoqueLocal('codestoquelocalfilial', null, ['class' => 'form-control', 'required' => true]) !!}</div>
            </div>
            <div class="form-group">
                {!! Form::label('datainicial', 'De', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-6">{!! Form::datetimeLocal('datainicial', null, ['class'=> 'form-control text-center', 'placeholder' => 'De', 'required' => true]) !!}</div>
            </div>
          
            <div class="form-group">
                {!! Form::label('datafinal', 'Até', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-6">{!! Form::datetimeLocal('datafinal', null, ['class'=> 'form-control text-center', 'placeholder' => 'Até', 'required' => true]) !!}</div>
            </div>
        </div>
        
        <div class='col-md-4'>
            <div class="form-group">
                {!! Form::label('minimo', 'Mínimo', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-9">{!! Form::select2('minimo', $arr_minimo, null, ['class' => 'form-control', 'placeholder' => 'Estoque Mínimo...']) !!}</div>
            </div>
            <div class="form-group">
                {!! Form::label('maximo', 'Máximo', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-9">{!! Form::select2('maximo', $arr_maximo, null, ['class' => 'form-control', 'placeholder' => 'Estoque Máximo...']) !!}</div>
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
