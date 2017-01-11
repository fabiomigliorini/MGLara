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
    <?php
        $metasfiliais = $model->MetaFilialS()->get();
    ?>
@if(count($metasfiliais)>0)
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#geral" aria-controls="geral" role="tab" data-target="#geral" data-toggle="tab">Geral</a></li>
        @foreach($metasfiliais as $metafilial)
        <li role="presentation"><a href="{{ url("meta/{$model->codmeta}?codfilial=$metafilial->codfilial") }}" aria-controls="{{ $metafilial->codfilial }}" data-target="#{{ $metafilial->codfilial }}" role="tab" data-toggle="tab" class="tab-filial">{{ $metafilial->Filial->filial }}</a></li>
        @endforeach
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="geral">
            <table class="table table-striped">
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
                    @foreach($dados['filiais'] as $filial)
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
            <br>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Filial</th>
                        <th>Vendedor</th>
                        <th class="text-right">Meta</th>
                        <th class="text-right">Vendas</th>
                        <th class="text-right">Falta</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">1º Vendedor</th>
                        <th class="text-right">Comissão</th>
                        <th class="text-right">R$ Meta</th>
                        <th class="text-right">R$ Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1;?>
                    @foreach($dados['vendedores'] as $vendedor)
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
                        <td class="text-center">
                            @if($vendedor['primeirovendedor'])
                            <i class="glyphicon glyphicon-star text-success"></i>
                            @endif
                        </td>
                        <td class="text-right">{{ formataNumero($vendedor['valorcomissaovendedor']) }}</td>
                        <td class="text-right">{{ formataNumero($vendedor['valorcomissaometavendedor']) }}</td>
                        <td class="text-right">{{ formataNumero($vendedor['valortotalcomissao']) }}</td>
                    </tr>
                    @endforeach
                </tbody> 
            </table>
            
            @if(Request::get('codfilial'))
            <div id="piechart{{ $filial->filial }}"></div>
            @else
            <div id="piechartGeral"></div>
            @endif
            
            <script type="text/javascript">
                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawChart);
                
                @if(Request::get('codfilial'))
                    var piechart = 'piechart{{ $filial->filial }}';
                    var DataTable = [
                        ['Vendedores', 'Vendas'],
                        @foreach($dados['vendedores'] as $vendedor)
                        ["{{ $vendedor['pessoa'] }}", {{ $vendedor['valorvendas'] }}],
                        @endforeach
                        ['Sem Vendedor', {{ $filial->valorvendas - array_sum(array_column($dados['vendedores'], 'valorvendas')) }}]
                    ];
                @else
                    var piechart = 'piechartGeral';
                    var DataTable = [
                        ['Lojas', 'Vendas'],
                        @foreach($dados['filiais'] as $filial)
                        ["{{ $filial->filial }}", {{ $filial->valorvendas }}],
                        @endforeach
                    ];
                @endif
                //console.log(piechart);
                function drawChart() {
                    var data = google.visualization.arrayToDataTable(DataTable);

                    var options = {
                        title: 'Porcentagem de vendas',
                        'width':900,
                        'height':500
                    };


                    var chart = new google.visualization.PieChart(document.getElementById(piechart));
                    chart.draw(data, options);
                }
            </script>            
        </div>
        @foreach($metasfiliais as $filial)
        <div role="tabpanel" class="tab-pane" id="{{ $filial->codfilial }}"></div>
        @endforeach
    </div>
@else
<h3>Nenhuma filial cadastrada para esse meta!</h3>
@endif

</div>
@section('inscript')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('.tab-filial').click(function(e) {
        var $this = $(this),
            loadurl = $this.attr('href'),
            targ = $this.attr('data-target');

        if ($(targ).text().length == 0 ) {
            $.get(loadurl, function(data) {
                $(targ).html(jQuery(data).find('#geral').html());
                drawChart();
            });
        }
        
        $this.tab('show');
        return false;
    });
});
</script>

@endsection
@stop