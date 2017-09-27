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
                {!! Form::label('codestoquelocaldeposito', 'Depósito', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">{!! Form::select2EstoqueLocal('codestoquelocaldeposito', null, ['class' => 'form-control', 'required' => true]) !!}</div>
            </div>
            <div class="form-group">
                {!! Form::label('saldo_deposito', 'Saldo', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-9">{!! Form::select2('saldo_deposito', $arr_saldo_deposito, null, ['class' => 'form-control', 'placeholder' => 'Saldo Depósito...']) !!}</div>
            </div>
            <div class="form-group">
                {!! Form::label('codmarca', 'Marca', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-9">{!! Form::select2Marca('codmarca', null, ['class' => 'form-control']) !!}</div>
            </div>
            <div class="form-group">
                {!! Form::label('marcacontrolada', 'Marcas Controladas', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-9">{!! Form::select2MarcaControlada('marcacontrolada', null, ['class' => 'form-control']) !!}</div>
            </div>            
        </div>
        
        <div class='col-md-5'>
            <div class="form-group">
                {!! Form::label('codestoquelocalfilial', 'Filial', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-5">{!! Form::select2EstoqueLocal('codestoquelocalfilial', null, ['class' => 'form-control', 'required' => true]) !!}</div>
            </div>
            <div class="form-group">
                {!! Form::label('saldo_filial', 'Saldo', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-md-10">{!! Form::select2('saldo_filial', $arr_saldo_filial, null, ['class' => 'form-control', 'placeholder' => 'Saldo Filial...']) !!}</div>
            </div>
            <div class="form-group">
                {!! Form::label('datainicial', 'De', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-md-6">{!! Form::datetimeLocal('datainicial', $filtro['datainicial']->format('Y-m-d\TH:i:s'), ['class'=> 'form-control text-center', 'placeholder' => 'De', 'required' => true]) !!}</div>
            </div>
          
            <div class="form-group">
                {!! Form::label('datafinal', 'Até', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-md-6">{!! Form::datetimeLocal('datafinal', $filtro['datafinal']->format('Y-m-d\TH:i:s'), ['class'=> 'form-control text-center', 'placeholder' => 'Até', 'required' => true]) !!}</div>
            </div>
          
            <div class="form-group">
                {!! Form::label('dias_previsao', 'Previsão', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-md-4">
                    <div class="input-group">
                        {!! Form::number('dias_previsao', null, ['class'=> 'form-control text-right', 'placeholder' => 'Dias Previsão', 'step' => 1, 'required' => true]) !!}
                        <span class="input-group-addon">Dias</span>
                    </div>
                </div>
            </div>
          
          
        </div>
        
        <div class='col-md-3'>
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
