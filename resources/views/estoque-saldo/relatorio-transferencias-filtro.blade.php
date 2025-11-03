@extends('layouts.default')
@section('content')
    <ol class="breadcrumb header">
        {!! titulo(null, 'Relatório Transferência de Estoque', null) !!}
        <li class='active'>
            <small>
                <a data-toggle="collapse" href="#div-filtro" aria-expanded="false" aria-controls="div-filtro"><span
                        class='glyphicon glyphicon-search'></span></a>
            </small>
        </li>
    </ol>
    <div class="clearfix"></div>
    <div class='collapse in' id='div-filtro'>
        <div class='well well-sm' style="padding:9px 0">
            {!! Form::model($filtro, [
                'url' => 'estoque-saldo/relatorio-transferencias',
                'method' => 'GET',
                'class' => 'form-horizontal',
                'id' => 'estoque-saldo-search',
                'role' => 'search',
                'autocomplete' => 'off',
            ]) !!}
            <div class='col-md-4'>

                <div class="form-group">
                    {!! Form::label('abc', 'ABC', ['class' => 'col-sm-3 control-label']) !!}
                    <div class="col-sm-6">
                        {!! Form::select2(
                            'abc',
                            [
                                '' => '',
                                'AB' => 'Urgentes (A/B)',
                                'A' => 'Somente A - Contínuo',
                                'B' => 'Somente B - Alto Giro',
                                'C' => 'Somente C - Comum',
                                'D' => 'Somente D - Sazonal',
                            ],
                            null,
                            [
                                'class' => 'form-control',
                                'id' => 'abc',
                                'allowClear' => true,
                                'placeholder' => 'Produtos A/B/C/D',
                                // 'style' => 'width:100%; height:200px',
                                // 'required' => true,
                                // 'multiple' => 'multiple',
                            ],
                        ) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('codmarca', 'Marca', ['class' => 'col-md-3 control-label']) !!}
                    <div class="col-md-6">{!! Form::select2Marca('codmarca', null, ['class' => 'form-control']) !!}</div>
                </div>
                <div class="form-group">
                    {!! Form::label('codestoquelocalorigem', 'Local Origem', ['class' => 'col-md-3 control-label']) !!}
                    <div class="col-md-6">{!! Form::select2EstoqueLocal('codestoquelocalorigem', null, ['class' => 'form-control']) !!}</div>
                </div>
                <div class="form-group">
                    {!! Form::label('codestoquelocaldestino', 'Local Destino', ['class' => 'col-md-3 control-label']) !!}
                    <div class="col-md-6">{!! Form::select2EstoqueLocal('codestoquelocaldestino', null, ['class' => 'form-control']) !!}</div>
                </div>

                <div class="form-group">
                    <div class="col-md-9 col-md-offset-4">
                        <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i>
                            Buscar</button>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
            <div class="clearfix"></div>
        </div>
    </div>
@stop
