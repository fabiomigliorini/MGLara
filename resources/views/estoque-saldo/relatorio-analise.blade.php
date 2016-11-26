@extends('layouts.print-landscape')
@section('content')
<div class='cabecalho'>
  <a href="{{ $dados['urlfiltro'] }}">
    Análise Saldo de Estoque
  </a>
</div>
<div class='conteudo'>
    <!--
    1234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|
    -->
    
    @foreach($dados['agrupamentos'] as $codigo => $agr)
        <h1>
            @foreach ($agr['titulos'] as $titulo)
                <a href='{{ url($titulo['model'], $titulo['codigo']) }}'>
                    {{ $titulo['descricao'] }}
                </a>
                <a href='{{ $titulo['urldetalhes'] }}' style="font-size: 0.6em"><i class="fa fa-search-plus"></i></a>
                @if ($titulo != end($agr['titulos'])) 
                    /
                @endif
            @endforeach
        </h1>
        <table class="relatorio">
            <thead class='negativo'>
                <tr>
                    <td colspan="4"></td>
                    <th colspan="2">Variação</th>
                    <th colspan="3">Última Compra</th>
                    <th colspan="9">Estoque</th>
                    <th colspan="3">Vendas</th>
                    <th colspan="1" class="quinzena">Prev</th>
                </tr>
                <tr>
                    <th class="codigo">Código</th>
                    <th class="produto">Produto</th>
                    <th class="preco">Preço</th>
                    <th class="unidademedida">UM</th>
                    <th class="variacao">Variação</th>
                    <th class="referencia">Referência</th>
                    <th class="data">Data</th>
                    <th class="quantidade">Qtd</th>
                    <th class="valor">Valor</th>
                    <th class="local">Local</th>
                    <th class="data">Vencto</th>
                    <th class="prateleira">Prateleira</th>
                    <th class="saldo">Saldo</th>
                    <th class="dias">Dias</th>
                    <th class="medio">Médio</th>
                    <th class="data">Data</th>
                    <th class="minimo">Mín</th>
                    <th class="maximo">Máx</th>
                    <th class="bimestre">Bim</th>
                    <th class="semestre">Sem</th>
                    <th class="ano">Ano</th>
                    <th class="quinzena">Qui</th>
                </tr>
            </thead>
            <tbody class='zebrada'>
                @foreach($agr['produtos'] as $codproduto => $prod)
                    <?php
                    $qtdVariacoes = sizeof($prod['variacoes']);
                    $qtdLocaisTotal = 0;
                    foreach ($prod['variacoes'] as $codprodutovariacao => $var) {
                        $qtdLocais = sizeof($var['locais']);
                        $qtdLocaisTotal += ($qtdLocais > 1)?$qtdLocais+1:1;
                    }
                    $rowspanProduto = ($qtdVariacoes > 1)?$qtdLocaisTotal+1:$qtdLocaisTotal;
                    ?>
                    <tr>
                        <td rowspan='{{ $rowspanProduto }}'>{{ formataCodigo($codproduto, 6) }}</td>
                        <td rowspan='{{ $rowspanProduto }}'>
                            <a href='{{ $prod['urldetalhes'] }}'>
                                <i class="fa fa-search-plus"></i> 
                            </a>
                            @if (!empty($prod['inativo']))
                                <strike><a href='{{ url("produto/{$codproduto}") }}'>{{ $prod['produto'] }}</a></strike>
                                <span class='text-danger'>Inativo desde {{ formataData($prod['inativo']) }}</span>
                            @else
                                <a href='{{ url("produto/{$codproduto}") }}'>{{ $prod['produto'] }}</a>
                            @endif
                        </td>
                        <td rowspan='{{ $rowspanProduto }}' class='text-right'>{{ formataNumero($prod['preco'], 2) }}</td>
                        <td rowspan='{{ $rowspanProduto }}'>{{ $prod['siglaunidademedida'] }}</td>
                        @foreach ($prod['variacoes'] as $codprodutovariacao => $var)
                            <?php
                            $qtdLocais = sizeof($var['locais']);
                            $rowspanVariacao = ($qtdLocais > 1)?$qtdLocais+1:1;
                            ?>
                                <td rowspan='{{ $rowspanVariacao }}'>{{ $var['variacao'] }}</td>
                                <td rowspan='{{ $rowspanVariacao }}'>{{ $var['referencia'] }}</td>
                                <td rowspan='{{ $rowspanVariacao }}' class='text-center'>{{ formataData($var['dataultimacompra']) }}</td>
                                <td rowspan='{{ $rowspanVariacao }}' class='text-right'>{{ formataNumero($var['quantidadeultimacompra'], 0) }}</td>
                                <td rowspan='{{ $rowspanVariacao }}' class='text-right'>{{ formataNumero($var['custoultimacompra']) }}</td>
                                @foreach ($var['locais'] as $codestoquelocal => $loc)
                                        <td>{{ $loc['siglaestoquelocal'] }}</td>
                                        <td style='text-center'>{{ formataData($loc['vencimento']) }}</td>
                                        <td>{{ formataLocalEstoque($loc['corredor'], $loc['prateleira'], $loc['coluna'], $loc['bloco']) }}</td>
                                        <td class='text-right'>
                                            <a href='{{ url("estoque-saldo/{$loc['codestoquesaldo']}") }}'>
                                                {{ formataNumero($loc['saldoquantidade'], 0) }}
                                            </a>
                                        </td>
                                        <td class='text-right'>{{ formataNumero($loc['saldodias'], 0) }}</td>
                                        <td class='text-right'>{{ formataNumero($loc['customedio'], 4) }}</td>
                                        <td class='text-center'>{{ formataData($loc['dataentrada']) }}</td>
                                        <td class='text-right'>{{ formataNumero($loc['estoqueminimo'], 0) }}</td>
                                        <td class='text-right'>{{ formataNumero($loc['estoquemaximo'], 0) }}</td>
                                        <td class='text-right'>{{ formataNumero($loc['vendabimestrequantidade'], 0) }}</td>
                                        <td class='text-right'>{{ formataNumero($loc['vendasemestrequantidade'], 0) }}</td>
                                        <td class='text-right'>{{ formataNumero($loc['vendaanoquantidade'], 0) }}</td>
                                        <td class='text-right'>{{ formataNumero($loc['vendaprevisaoquinzena'], 0) }}</td>
                                    </tr>
                                    @if ($qtdLocais > 1)
                                        <tr>
                                    @endif
                                @endforeach
                                @if ($qtdLocais == 0)
                                        <td colspan='12' class='text-center'> --- Sem Movimento --- </td>
                                    </tr>
                                @elseif ($rowspanVariacao > 1)
                                        <td class='subtotal' colspan='3'>Total Variação</td>
                                        <td class='subtotal text-right'>{{ formataNumero($var['saldoquantidade'], 0) }}</td>
                                        <td class='subtotal text-right'>{{ formataNumero($var['saldodias'], 0) }}</td>
                                        <td class='subtotal text-right'>{{ formataNumero($var['customedio'], 4) }}</td>
                                        <td class='subtotal'></td>
                                        <td class='subtotal text-right'>{{ formataNumero($var['estoqueminimo'], 0) }}</td>
                                        <td class='subtotal text-right'>{{ formataNumero($var['estoquemaximo'], 0) }}</td>
                                        <td class='subtotal text-right'>{{ formataNumero($var['vendabimestrequantidade'], 0) }}</td>
                                        <td class='subtotal text-right'>{{ formataNumero($var['vendasemestrequantidade'], 0) }}</td>
                                        <td class='subtotal text-right'>{{ formataNumero($var['vendaanoquantidade'], 0) }}</td>
                                        <td class='subtotal text-right'>{{ formataNumero($var['vendaprevisaoquinzena'], 0) }}</td>
                                    </tr>
                                @endif
                        @endforeach
                        @if ($qtdVariacoes > 1)
                            <tr>
                                <td class='subtotal' colspan='5'>Total Produto</td>
                                <td class='subtotal' colspan='3'></td>
                                <td class='subtotal text-right'>{{ formataNumero($prod['saldoquantidade'], 0) }}</td>
                                <td class='subtotal text-right'>{{ formataNumero($prod['saldodias'], 0) }}</td>
                                <td class='subtotal text-right'>{{ formataNumero($prod['customedio'], 4) }}</td>
                                <td class='subtotal'></td>
                                <td class='subtotal text-right'>{{ formataNumero($prod['estoqueminimo'], 0) }}</td>
                                <td class='subtotal text-right'>{{ formataNumero($prod['estoquemaximo'], 0) }}</td>
                                <td class='subtotal text-right'>{{ formataNumero($prod['vendabimestrequantidade'], 0) }}</td>
                                <td class='subtotal text-right'>{{ formataNumero($prod['vendasemestrequantidade'], 0) }}</td>
                                <td class='subtotal text-right'>{{ formataNumero($prod['vendaanoquantidade'], 0) }}</td>
                                <td class='subtotal text-right'>{{ formataNumero($prod['vendaprevisaoquinzena'], 0) }}</td>
                            </tr>
                        @endif
                @endforeach
            </tbody>
            @if (sizeof($agr['produtos']) > 1)
                <tfoot>
                    <td class='total' colspan='12'>Total</td>
                    <td class='total text-right'>{{ formataNumero($agr['saldoquantidade'], 0) }}</td>
                    <td class='total text-right'>{{ formataNumero($agr['saldodias'], 0) }}</td>
                    <td class='total text-right'>{{ formataNumero($agr['customedio'], 4) }}</td>
                    <td class='total'></td>
                    <td class='total text-right'>{{ formataNumero($agr['estoqueminimo'], 0) }}</td>
                    <td class='total text-right'>{{ formataNumero($agr['estoquemaximo'], 0) }}</td>
                    <td class='total text-right'>{{ formataNumero($agr['vendabimestrequantidade'], 0) }}</td>
                    <td class='total text-right'>{{ formataNumero($agr['vendasemestrequantidade'], 0) }}</td>
                    <td class='total text-right'>{{ formataNumero($agr['vendaanoquantidade'], 0) }}</td>
                    <td class='total text-right'>{{ formataNumero($agr['vendaprevisaoquinzena'], 0) }}</td>
                </tfoot>
            @endif
        </table>
    @endforeach
    
    @if (sizeof($dados['agrupamentos']) > 1)
    <br>
    <br>
    <table>
        <tfoot>
            <tr>
                <td class=''></td>              
                <td class="saldo">Saldo</th>
                <td class="dias">Dias</th>
                <td class="medio">Médio</th>
                <td class="data">Data</th>
                <td class="minimo">Mín</th>
                <td class="maximo">Máx</th>
                <td class="bimestre">Bim</th>
                <td class="semestre">Sem</th>
                <td class="ano">Ano</th>
                <td class="quinzena">Qui</th>
            </tr>
            <tr>
                <td class='total-geral' rowspan="1">Total Geral</td>
                <td class='total-geral text-right '>{{ formataNumero($dados['saldoquantidade'], 0) }}</td>
                <td class='total-geral text-right '>{{ formataNumero($dados['saldodias'], 0) }}</td>
                <td class='total-geral text-right '>{{ formataNumero($dados['customedio'], 4) }}</td>
                <td class='total-geral text-right '></td>
                <td class='total-geral text-right '>{{ formataNumero($dados['estoqueminimo'], 0) }}</td>
                <td class='total-geral text-right '>{{ formataNumero($dados['estoquemaximo'], 0) }}</td>
                <td class='total-geral text-right '>{{ formataNumero($dados['vendabimestrequantidade'], 0) }}</td>
                <td class='total-geral text-right '>{{ formataNumero($dados['vendasemestrequantidade'], 0) }}</td>
                <td class='total-geral text-right '>{{ formataNumero($dados['vendaanoquantidade'], 0) }}</td>
                <td class='total-geral text-right '>{{ formataNumero($dados['vendaprevisaoquinzena'], 0) }}</td>
            </tr>
        </tfoot>
    </table>
    @endif
    
</div>
<div class='rodape'>
</div>
@section('inscript')
<style>
  
/*table.relatorio {table-layout:fixed; width:20cm;}*//*Setting the table width is important!*/
/*table.relatorio td {overflow:hidden;}*/ /*Hide text outside the cell.*/

  .codigo {
      width: 1.1cm;
  }
  .produto {
      width: 8cm;
  }
  .preco {
      width: 1cm;
  }
  .unidademedida {
      width: 0.3cm;
  }
  .variacao {
      width: 2cm;
  }
  .referencia {
      width: 1.5cm;
  }
  .data {
      width: 1.1cm;
  }
  .quantidade {
      width: 0.5cm;
  }
  .valor {
      width: 1cm;
  }
  .local {
      width: 0.7cm;
  }
  .prateleira {
      width: 1.4cm;
  }
  .saldo {
      width: 0.8cm;
  }
  .dias {
      width: 0.8cm;
  }
  .medio {
      width: 1cm;
  }
  .minimo {
      width: 0.5cm;
  }
  .maximo {
      width: 0.5cm;
  }
  .bimestre {
      width: 0.5cm;
  }
  .semestre {
      width: 0.5cm;
  }
  .ano {
      width: 0.5cm;
  }
  .quinzena {
      width: 0.5cm;
  }
  
</style>
<script type="text/javascript">
    /*
$(document).ready(function() {
    var color = $('a:link').css('color');
    console.log(color);
});
*/
</script>
@endsection
@stop
