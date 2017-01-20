<?php
//dd($vendedores);
/*
    $collection_filiais = collect($dados['filiais']);
    $collection_vededores = collect($dados['vendedores']);

    $dados['filiais'] = $collection_filiais->where('codfilial', (int)$filtro);
    $dados['vendedores'] = $collection_vededores->where('codfilial', (int)$filtro);
*/    
?>
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
            <!--
            <div id="piechart{{ $filial->filial }}"></div>
            <script type="text/javascript">
                google.charts.load('current', {
                    'packages':['corechart'],
                    'language': 'pt_BR'
                });
                google.charts.setOnLoadCallback(drawChart);
                var piechart = 'piechart{{ $filial->filial }}';
                var DataTable = [
                    ['Vendedores', 'Vendas'],
                    @foreach($vendedores as $vendedor)
                    ["{{ $vendedor['pessoa'] }}", {{ $vendedor['valorvendas'] }}],
                    @endforeach
                ];
                //console.log(piechart);
                function drawChart() {
                    var data = google.visualization.arrayToDataTable(DataTable);

                    var options2 = {
                        title: 'Porcentagem de vendas',
                        'width':900,
                        'height':500,
                    };

                    var chart2 = new google.visualization.PieChart(document.getElementById(piechart));
                    chart2.draw(data, options2);
                }
            </script>  
            -->