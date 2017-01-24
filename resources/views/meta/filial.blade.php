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
    <div id="piechart{{ $filial->codfilial }}"></div>
</div>
<script type="text/javascript">
    google.charts.load('current', {
        'packages':['corechart'],
        'language': 'pt_BR'
    });
    google.charts.setOnLoadCallback(drawChart);
    DataTableFilial[{{ $filial->codfilial }}] = [
        ['Vendedores', 'Vendas'],
        @foreach($vendedores as $vendedor)
        ["{{ $vendedor['pessoa'] }}", {{ $vendedor['valorvendas'] }}],
        @endforeach
        ['Xerox', {{ $filial->valorvendasxerox }}],
        ['Sem Vendedor', {{ $filial->valorvendas - array_sum(array_column($vendedores->toArray(), 'valorvendas')) -  $filial->valorvendasxerox}}]
    ];
    function drawChart() {
        var data = google.visualization.arrayToDataTable(DataTableFilial[{{ $filial->codfilial }}]);
        optionsFilial[{{ $filial->codfilial }}] = {
            title: 'Divisão',
            'width':900,
            'height':500,
        };

        piechartFilial[{{ $filial->codfilial }}] = new google.visualization.PieChart(document.getElementById('piechart'+{{ $filial->codfilial }}));
        piechartFilial[{{ $filial->codfilial }}].draw(data, optionsFilial[{{ $filial->codfilial }}]);
    }
</script>