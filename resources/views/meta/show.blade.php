@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
    {!! 
        titulo(
            $model->codmeta,
            [
                url("meta") => 'Metas',
                formataData($model->periodofinal, 'EC'),
            ],
            null
        ) 
    !!} 
    <li class='active'>
        <small>
            <a title="Novo" href="{{ url('meta/create') }}"><i class="glyphicon glyphicon-plus"></i></a>
            &nbsp;
            <a title="Editar" href="{{ url("meta/$model->codmeta/edit") }}"><i class="glyphicon glyphicon-pencil"></i></a>
            &nbsp;
            <a title="Excluir" href="{{ url("meta/$model->codmeta") }}" data-excluir data-pergunta="Tem certeza que deseja excluir a meta '{{ $model->observacoes }}'?" data-after-delete="location.replace(baseUrl + '/meta');"><i class="glyphicon glyphicon-trash"></i></a>
        </small>
    </li>   
</ol>
<?php
    $proximos = $model->buscaProximos(8);
    $anteriores = $model->buscaAnteriores(16 - sizeof($proximos));
    if (sizeof($anteriores) < 8) {
        $proximos = $model->buscaProximos(16 - sizeof($anteriores));
    }
    $filiais = collect($dados['filiais']);
    $vendedores = collect($dados['vendedores']);
    $metasfiliais = $model->MetaFilialS()->get();
?>
<ul class="nav nav-pills">
    @foreach($anteriores as $meta)
    <li role="presentation"><a href="{{ url("meta/$meta->codmeta") }}">{{ formataData($meta->periodofinal, 'EC') }}</a></li>
    @endforeach
    <li role="presentation" class="active"><a href="#">{{ formataData($model->periodofinal, 'EC') }}</a></li>
    @foreach($proximos as $meta)
    <li role="presentation"><a href="{{ url("meta/$meta->codmeta") }}">{{ formataData($meta->periodofinal, 'EC') }}</a></li>
    @endforeach
</ul>        
<div>
    <br>
    @if(count($metasfiliais) > 0)
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#geral" aria-controls="geral" role="tab" data-target="#geral" data-toggle="tab">Geral</a></li>
        @foreach($metasfiliais as $metafilial)
        <li role="presentation"><a href="{{ url("meta/{$model->codmeta}?codfilial=$metafilial->codfilial") }}" aria-controls="{{ $metafilial->codfilial }}" data-target="#{{ $metafilial->codfilial }}" role="tab" data-toggle="tab" class="tab-filial">{{ $metafilial->Filial->filial }}</a></li>
        @endforeach
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="geral">
            <h3>Total de vendas</h3>
            <div class="panel panel-default">            
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Filial</th>
                            <th class="text-right">Meta</th>
                            <th class="text-right">Vendas</th>
                            <th class="text-right">Meta Vendedor</th>
                            <th>Sub-Gerente</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($filiais as $filial)
                        <tr>
                            <th scope="row">{{ $filial->filial }}</th>
                            <td class="text-right">{{ formataNumero($filial->valormetafilial) }}</td>
                            <td class="text-right">{{ formataNumero($filial->valorvendas) }}</td>
                            <td class="text-right">{{ formataNumero($filial->valormetavendedor) }}</td>
                            <td><a href="{{ url("pessoa/$filial->codpessoa") }}">{{ $filial->pessoa }}</a></td>
                        </tr>
                        @endforeach
                    </tbody> 
                </table>
            </div>
            <h3>Vendedores</h3>
            <div class="panel panel-default">            
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Filial</th>
                            <th>Vendedor</th>
                            <th class="text-right">Meta</th>
                            <th class="text-right">Vendas</th>
                            <th class="text-right">Falta</th>
                            <th class="text-center">Status</th>
                            <th class="text-right">Comissão</th>
                            <th class="text-right">R$ Meta</th>
                            <th class="text-right">1º Vendedor</th>
                            <th class="text-right">R$ Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1;?>
                        @foreach($vendedores as $vendedor)
                        <tr>
                            <th scope="row">{{ $vendedor['filial'] }}</th>
                            <td>
                                <a href="{{ url('pessoa/'.$vendedor['codpessoa']) }}">{{ $vendedor['pessoa'] }}</a>
                                <span class="label label-success pull-right">{{$i++}}º</span>
                            </td>
                            <td class="text-right">{{ formataNumero($vendedor['valormetavendedor']) }}</td>
                            <td class="text-right">{{ formataNumero($vendedor['valorvendas']) }}</td>
                            <td class="text-right">{{ formataNumero($vendedor['falta']) }}</td>
                            <td class="text-center">
                                @if($vendedor['metaatingida'])
                                    <span class="label label-success">Atingida</span>
                                @endif
                            </td>
                            <td class="text-right">{{ formataNumero($vendedor['valorcomissaovendedor']) }}</td>
                            <td class="text-right">{{ formataNumero($vendedor['valorcomissaometavendedor']) }}</td>
                            <td class="text-right">{{ formataNumero($vendedor['primeirovendedor']) }}</td>
                            <td class="text-right">{{ formataNumero($vendedor['valortotalcomissao']) }}</td>
                        </tr>
                        @endforeach
                    </tbody> 
                </table>
            </div>
            <br>
            <div class="col-sm-3">
                <div class="row">
                    <h3>Xerox</h3>
                    <div class="panel panel-default">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Filial</th>
                                    <th class="text-right">Vendas Xerox</th>
                                    <th class="text-right">Comissão</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($filiais as $filial)
                                <tr>
                                    <th scope="row">{{ $filial->filial }}</th>
                                    <td class="text-right">{{ formataNumero($filial->valorvendasxerox) }}</td>
                                    <td class="text-right"></td>
                                </tr>
                                @endforeach
                            </tbody> 
                        </table> 
                    </div>
                </div>
            </div>
            <div class="col-sm-9">
                <div id="piechartGeral"></div>
            </div>
            <script type="text/javascript">
                google.charts.load('current', {
                    'packages':['corechart'],
                    'language': 'pt_BR'
                });
                google.charts.setOnLoadCallback(drawChart);
                var DataTable = [
                    ['Lojas', 'Vendas'],
                    @foreach($filiais as $filial)
                    ["{{ $filial->filial }}", {{ $filial->valorvendas }}],
                    @endforeach
                ];
                function drawChart() {
                    var data = google.visualization.arrayToDataTable(DataTable);
                    var options = {
                        title: 'Divisão',
                        //'width':100%,
                        'height':500,
                    };
                    var chartGeral = new google.visualization.PieChart(document.getElementById('piechartGeral'));
                    chartGeral.draw(data, options);
                }
                var piechartFilial = [];
                var optionsFilial = [];
                var DataTableFilial = [];
            </script>            
        </div>
        @foreach($metasfiliais as $filial)
        <div role="tabpanel" class="tab-pane" id="{{ $filial->codfilial }}">
            @include('meta.filial', [
                'vendedores' => $vendedores->where('codfilial', $filial->codfilial),
                'filiais' => $filiais->where('codfilial', $filial->codfilial)
            ])
        </div>
        @endforeach
    </div>
@else
<h3>Nenhuma filial cadastrada para esse meta!</h3>
@endif
</div>
@section('inscript')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
@endsection
@stop