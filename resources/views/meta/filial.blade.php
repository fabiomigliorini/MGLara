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
                            <th class="text-right">Prêmio</th>
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
                                @if($filial['premio'])
                                    <span class="label label-success">Atingida</span>
                                @endif                                
                            </td>
                            <td class="text-right">{{ formataNumero($filial['premio']) }}</td>
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
                <th class="text-right">Prêmio</th>
                <th class="text-right">Meta</th>
                <th class="text-right">Primeiro</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vendedores as $vendedor)
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
                        <th class="text-right">Prêmio</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($xeroxs as $xerox)
                    <tr>
                        <td>{{ $xerox['filial'] }}</td>
                        <td><a href="{{ url('pessoa/'.$xerox['codpessoa']) }}">{{ $xerox['pessoa'] }}</a></td>
                        <td class="text-right">{{ formataNumero($xerox['valorvendas']) }}</td>
                        <td class="text-right">{{ formataNumero($xerox['premio']) }}</td>
                    </tr>
                    @endforeach
                </tbody> 
            </table> 
        </div>
    </div>
</div>
<div class="col-sm-6"></div>
<div class="col-sm-8">
    <div id="piechart{{ $filial['codfilial'] }}"></div>
</div>
<script type="text/javascript">
    google.charts.load('current', {
        'packages':['corechart'],
        'language': 'pt_BR'
    });
    google.charts.setOnLoadCallback(drawChart);
    DataTableFilial[{{ $filial['codfilial'] }}] = [
        ['Vendedores', 'Vendas'],
        @foreach($vendedores as $vendedor)
        ["{{ $vendedor['pessoa'] }}", {{ $vendedor['valorvendas'] }}],
        @endforeach
        ['Xerox', {{ $xerox['valorvendas'] }}],
        ['Sem Vendedor', {{ $filial['valorvendas'] - array_sum(array_column($vendedores->toArray(), 'valorvendas')) -  $xerox['valorvendas'] }}]
    ];
    function drawChart() {
        var data = google.visualization.arrayToDataTable(DataTableFilial[{{ $filial['codfilial'] }}]);
        optionsFilial[{{ $filial['codfilial'] }}] = {
            title: 'Divisão',
            'width':900,
            'height':500,
        };

        piechartFilial[{{ $filial['codfilial'] }}] = new google.visualization.PieChart(document.getElementById('piechart'+{{ $filial['codfilial'] }}));
        piechartFilial[{{ $filial['codfilial'] }}].draw(data, optionsFilial[{{ $filial['codfilial'] }}]);
    }
</script>