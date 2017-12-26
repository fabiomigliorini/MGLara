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
            &nbsp;
            <a title="Relatório" href="{{ url("meta/$model->codmeta/relatorio") }}"><i class="glyphicon glyphicon-print"></i></a>

        </small>
    </li>   
</ol>
<?php
    $proximos = $model->buscaProximos(8);
    $anteriores = $model->buscaAnteriores(16 - sizeof($proximos));
    if (sizeof($anteriores) < 8) {
        $proximos = $model->buscaProximos(16 - sizeof($anteriores));
    }
    $filiais    = collect($dados['filiais']);
    $vendedores = collect($dados['vendedores']);
    $xeroxs     = collect($dados['xerox']);
    $metasfiliais = $model->MetaFilialS()->get();
    $if = 1;
    $iv = 1;

    use MGLara\Models\Feriado;
    
    $dias_uteis = Feriado::diasUteis($model->periodoinicial, ($model->periodofinal <= Carbon\Carbon::today() ? $model->periodofinal : Carbon\Carbon::today()), true);
    $datas = [];
    
    foreach ($dias_uteis as $dia){
        $datas[] = substr($dia->toW3cString(), 0, -6);
    }

    $data = clone $model->periodoinicial;
    $dias = collect();
    
    while ($data->lte( ($model->periodofinal <= Carbon\Carbon::today() ? $model->periodofinal : Carbon\Carbon::today()) )) {
        $dias->push(substr($data->toW3cString(), 0, -6));
        $data->addDay();
    }

    //dd($datas);
    //dd($dias->contains('2017-02-28T00:00:00'));

    $colunas = [];
    foreach($filiais as $filial) {
        $colunas[$filial['filial']] = [$filial['filial']];
        $valorvendaspordata = collect($filial['valorvendaspordata']);
        foreach ($datas as $dia) {
            if(!$valorvendaspordata->contains('data', $dia)){
                //$valorvenda = 0;
                //dd('Não contem');
                array_push($colunas[$filial['filial']], 0);
            } else {
                $i = array_search($dia, array_column($valorvendaspordata->toArray(), 'data'));
                array_push($colunas[$filial['filial']], $valorvendaspordata[$i]['valorvendas']);
            }

            //array_push($colunas[$filial['filial']], $valorvendas);
        }
/*
        foreach($filial['valorvendaspordata'] as $vendas) {
            array_push($colunas[$filial['filial']], $vendas['valorvendas']);
        }
*/        
    }
    
    //dd($colunas);
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
                            <th>Sub-Gerente</th>
                            <th class="text-right">Meta</th>
                            <th class="text-right">Meta Vendedor</th>
                            <th class="text-right">Vendas</th>
                            <th class="text-right">Falta</th>
                            <th class="text-right">Comissão</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($filiais as $filial)
                        <tr>
                            <td scope="row">{{ $filial['filial'] }}</td>
                            <td>
                                <a href="{{ url('pessoa/'.$filial['codpessoa']) }}">{{ $filial['pessoa'] }}</a>
                                <span class="label label-success pull-right">{{ $if++ }}º</span>
                            </td>
                            <td class="text-right"><span class="text-muted">{{ formataNumero($filial['valormetafilial']) }}</span></td>
                            <td class="text-right"><span class="text-muted">{{ formataNumero($filial['valormetavendedor']) }}</span></td>
                            <td class="text-right"><strong>{{ formataNumero($filial['valorvendas']) }}</strong></td>
                            <td class="text-right">
                                <span class="text-danger">{{ formataNumero($filial['falta']) }}</span>
                                @if($filial['comissao'])
                                    <span class="label label-success">Atingida</span>
                                @endif                                
                            </td>
                            <td class="text-right">{{ formataNumero($filial['comissao']) }}</td>
                        </tr>
                        @endforeach
                    </tbody> 
                </table>
            </div>
            <div class="clearfix"></div>
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
                            <th class="text-right">Comissão</th>
                            <th class="text-right">Prêmio</th>
                            <th class="text-right">Primeiro</th>
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vendedores->sortByDesc('valorvendas') as $vendedor)
                        <tr>
                            <td scope="row">{{ $vendedor['filial'] }}</td>
                            <td>
                                <a href="{{ url('pessoa/'.$vendedor['codpessoa']) }}">{{ $vendedor['pessoa'] }}</a>
                                <span class="label label-success pull-right">{{ $iv++ }}º</span>
                            </td>
                            <td class="text-right"><span class="text-muted">{{ formataNumero($vendedor['valormetavendedor']) }}</span></td>
                            <td class="text-right"><strong>{{ formataNumero($vendedor['valorvendas']) }}</strong></td>
                            <td class="text-right">
                                <span class="text-danger">{{ formataNumero($vendedor['falta']) }}</span>
                                @if($vendedor['metaatingida'])
                                    <span class="label label-success">Atingida</span>
                                @endif                                
                            </td>
                            <td class="text-right">{{ formataNumero($vendedor['valorcomissaovendedor']) }}</td>
                            <td class="text-right">{{ formataNumero($vendedor['valorcomissaometavendedor']) }}</td>
                            <td class="text-right">{{ formataNumero($vendedor['primeirovendedor']) }}</td>
                            <td class="text-right"><strong>{{ formataNumero($vendedor['valortotalcomissao']) }}</strong></td>
                        </tr>
                        @endforeach
                    </tbody> 
                </table>
            </div>
            <div class="col-sm-6">
                <div class="row">
                    <h3>Xerox</h3>
                    <div class="panel panel-default">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Filial</th>
                                    <th>Vendedor</th>
                                    <th class="text-right">Vendas</th>
                                    <th class="text-right">Comissão</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($xeroxs as $xerox)
                                <tr>
                                    <td>{{ $xerox['filial'] }}</td>
                                    <td><a href="{{ url('pessoa/'.$xerox['codpessoa']) }}">{{ $xerox['pessoa'] }}</a></td>
                                    <td class="text-right"><strong>{{ formataNumero($xerox['valorvendas']) }}</strong></td>
                                    <td class="text-right"><strong>{{ formataNumero($xerox['comissao']) }}</strong></td>
                                </tr>
                                @endforeach
                            </tbody> 
                        </table> 
                    </div>
                </div>
            </div>
            <div class="col-sm-6"></div>
            <div class="row">
                <div class="col-sm-8">
                    <h3>Gráficos</h3>
                    <div class="panel panel-default">
                      <div class="panel-heading">
                        <h3 class="panel-title">Divisão</h3>
                      </div>
                      <div class="panel-body">    
                          <div id="pieChart" style="height: 400px; width: 100%"></div>
                      </div>
                    </div>      
                </div>
                <div class="col-sm-12">
                    <div class="panel panel-default">
                      <div class="panel-heading">
                        <h3 class="panel-title">Vendas por dia</h3>
                      </div>
                      <div class="panel-body">
                        <div id="lineChart"></div>
                      </div>
                    </div>                
                </div>    
            </div>
            <script type="text/javascript">
                var chart = c3.generate({
                    bindto: "#pieChart",
                    data: {
                        columns: [
                        @foreach($filiais as $filial)
                        ["{{ $filial['filial'] }}", {{ $filial['valorvendas'] }}],
                        @endforeach
                        ],
                        type : 'pie',
                    }
                });                   
                var lineChart = c3.generate({
                    bindto: "#lineChart",
                    data: {
                        x : 'date',
                        columns: [
                            ['date' 
                                @foreach($datas as $data)
                                <?php $data = Carbon\Carbon::parse($data);?>
                                ,"{{ $data->toDateString() }}"
                                @endforeach
                            ]
                            @foreach(array_values($colunas) as $coluna)
                            <?php $v = $coluna[0]; array_shift($coluna)?>
                            ,["{{$v}}", {{ implode(',', $coluna) }}]
                            @endforeach
                        ]
                    },
                    axis : {
                        x : {
                            type : 'timeseries',
                            tick : {
                                format: '%d',
                                culling: false
                            }
                        }
                    }
                });
                var pie = [];
            </script>            
        </div>
        @foreach($metasfiliais as $filial)
        <div role="tabpanel" class="tab-pane" id="{{ $filial['codfilial'] }}">
            @include('meta.filial', [
                'vendedores'    => $vendedores->where('codfilial', $filial['codfilial']),
                'filiais'       => $filiais->where('codfilial', $filial['codfilial']),
                'xeroxs'        => $xeroxs->where('codfilial', $filial['codfilial']),
                'i'            => 1
            ])
        </div>
        @endforeach
    </div>
@else
<h3>Nenhuma filial cadastrada para esse meta!</h3>
@endif
</div>
@section('inscript')
<link href="{{ URL::asset('public/vendor/c3/c3.css') }}" rel="stylesheet" type="text/css">
<script src="{{ URL::asset('public/vendor/c3/d3/d3.v3.min.js') }}" charset="utf-8"></script>
<script src="{{ URL::asset('public/vendor/c3/c3.min.js') }}"></script>
@endsection
@stop