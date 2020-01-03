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
                    <span class="label label-success pull-right">{{ $i++ }}º</span>
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
            <div id="piechart{{ $filial['codfilial'] }}"></div>
          </div>
        </div>      
    </div>  
    <div class="col-sm-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Vendas por dia</h3>
          </div>
          <div class="panel-body">
              <div id="{{ $filial['filial'] }}" style="width: 90%"></div>
          </div>
        </div>                
    </div>    
</div>
<?php
    use MGLara\Models\Feriado;
    
    $dias_uteis = Feriado::diasUteis($model->periodoinicial, ($model->periodofinal <= Carbon\Carbon::today() ? $model->periodofinal : Carbon\Carbon::today()), true);
    $numeroDiasUteis = Feriado::numeroDiasUteis($model->periodoinicial, $model->periodofinal, true);
    $meta_dia = $filial['valormetavendedor'] / $numeroDiasUteis;
    $datas = [];
    $dias = [];
    
    foreach ($dias_uteis as $dia){
        $datas[] = $dia->toW3cString(); 
        $dia = substr($dia->toW3cString(), 0, -6);
        $dias[$dia] = [$dia];
    }
    
    $colunas = [];
    $coluna_xerox = [];
    
    foreach($vendedores as $vendedor) {
        $colunas[$vendedor['pessoa']] = [$vendedor['pessoa']];
        $coluna_xerox[0] = [$xerox['pessoa']];
        
        $vendedor_collect = collect($vendedor['valorvendaspordata']);
        $xerox_collect = collect($xerox['valorvendaspordata']);
        
        foreach ($dias as $dia) {
            if(!$vendedor_collect->contains('data', $dia[0])){
                if(is_null($vendedor['valorvendaspordata'])){
                    $vendedor['valorvendaspordata'] = [];
                }
                array_push($vendedor['valorvendaspordata'], ['data' => $dia[0], 'valorvendas' => 0]);
            }

            if(!$xerox_collect->contains('data', $dia[0])){
                array_push($xerox['valorvendaspordata'], ['data' => $dia[0], 'valorvendas' => 0]);
            }
        }        

        $valorvendaspordata = collect($vendedor['valorvendaspordata']);
        $valorvendaspordataxerox = collect($xerox['valorvendaspordata']);
        
        foreach ($valorvendaspordata->sortBy('data') as $venda) {
            $valorvendas = explode('.', $venda['valorvendas']);
            array_push($colunas[$vendedor['pessoa']], $valorvendas[0]);
        }
        
        foreach ($valorvendaspordataxerox->sortBy('data') as $venda) {
            $valorvendas = explode('.', $venda['valorvendas']);
            array_push($coluna_xerox[0], $valorvendas[0]);
        }

    }
    //dd($coluna_xerox[0]);
?>
<script type="text/javascript">
   
    pie[{{ $filial['codfilial'] }}] = c3.generate({
        bindto: "#piechart{{ $filial['codfilial'] }}",
        data: {
            columns: [
            @foreach($vendedores as $vendedor)
            ["{{ $vendedor['pessoa'] }}", {{ $vendedor['valorvendas'] }}],
            @endforeach
            ['Xerox', {{ $xerox['valorvendas'] }}],
            ['Sem Vendedor', {{ $filial['valorvendas'] - array_sum(array_column($vendedores->toArray(), 'valorvendas')) -  $xerox['valorvendas'] }}]
            ],
            type : 'pie',
        },
        legend: {
            position: 'inset',           
        }        
    });  
                
   
    var {{ $filial['filial'] }} = c3.generate({
        bindto: "#{{ $filial['filial'] }}",
        padding: {
          left: 20
        },        
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
		<?php 
                    $v = $coluna[0];
                    if (is_array($coluna)) {
                        array_shift($coluna); 
                    } 
                ?>
                ,["{{$v}}", {{ implode(',', $coluna) }}]
                @endforeach
		<?php 
                    if (isset($coluna_xerox[0])) {
                        array_shift($coluna_xerox[0]); 
                    }
                ?>
                ,["Xerox", {{ implode(',', $coluna_xerox[0]??[]) }}]
            ]
        },
        axis : {
            x : {
                type : 'timeseries',
                tick : {
                    format: '%d',
                    culling: false,
                }
            }
        },
        grid: {
            y: {
                lines: [{ value: {{ $meta_dia }}, text:'Meta dia' }]
            }
        },
        legend: {
            position: 'right'
        }
    });  
    
</script>
