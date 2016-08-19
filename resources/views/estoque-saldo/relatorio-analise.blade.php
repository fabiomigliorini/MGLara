@extends('layouts.print-landscape')
@section('content')
<div class='cabecalho'>
    Análise Saldo de Estoque
</div>
<div class='conteudo'>
    <!--
    1234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|
    -->
    
    @foreach($dados as $codquebra => $quebra)
        <h1>{{ formataCodigo($codquebra) }} - {{ $quebra['descricao'] }}</h1>
        <table>
            <thead class='negativo'>
                <tr>
                    <th colspan="5">Produto</th>
                    <th colspan="2">Variação</th>
                    <th colspan="3">Última Compra</th>
                    <th colspan="8">Estoque</th>
                    <th colspan="3">Vendas</th>
                    <th colspan="1">Prev</th>
                </tr>
                <tr>
                    <th>Código</th>
                    <th>Produto</th>
                    <th>Venda</th>
                    <th>UM</th>
                    <th>Marca</th>
                    <th>Variação</th>
                    <th>Referência</th>
                    <th>Data</th>
                    <th>Qtd</th>
                    <th>Valor</th>
                    <th>Local</th>
                    <th>Prateleira</th>
                    <th>Saldo</th>
                    <th>Dias</th>
                    <th>Médio</th>
                    <th>Data</th>
                    <th>Mín</th>
                    <th>Máx</th>
                    <th>Bim</th>
                    <th>Sem</th>
                    <th>Ano</th>
                    <th>Qui</th>
                </tr>
            </thead>
            <tbody class='zebrada'>
                @foreach($quebra['produtos'] as $codproduto => $prod)
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
                            <a href='{{ url("produto/{$codproduto}") }}'>
                                {{ $prod['produto'] }}
                            </a>
                        </td>
                        <td rowspan='{{ $rowspanProduto }}' class='text-right'>{{ formataNumero($prod['preco'], 2) }}</td>
                        <td rowspan='{{ $rowspanProduto }}'>{{ $prod['sigla'] }}</td>
                        <td rowspan='{{ $rowspanProduto }}'>{{ $prod['marca'] }}</td>
                        @foreach ($prod['variacoes'] as $codprodutovariacao => $var)
                            <?php
                            $qtdLocais = sizeof($var['locais']);
                            $rowspanVariacao = ($qtdLocais > 1)?$qtdLocais+1:1;
                            ?>
                                <td rowspan='{{ $rowspanVariacao }}'>{{ $var['variacao'] }}</td>
                                <td rowspan='{{ $rowspanVariacao }}'>{{ $var['referencia'] }}</td>
                                <td rowspan='{{ $rowspanVariacao }}' class='text-right'>{{ formataData($var['ultimacompra']) }}</td>
                                <td rowspan='{{ $rowspanVariacao }}' class='text-right'>{{ formataNumero($var['quantidadecompra'], 0) }}</td>
                                <td rowspan='{{ $rowspanVariacao }}' class='text-right'>{{ formataNumero($var['custocompra']) }}</td>
                                @foreach ($var['locais'] as $codestoquelocal => $loc)
                                        <td>{{ $loc['estoquelocal'] }}</td>
                                        <td>{{ formataLocalEstoque($loc['corredor'], $loc['prateleira'], $loc['coluna'], $loc['bloco']) }}</td>
                                        <td class='text-right'>
                                            <a href='{{ url("estoque-saldo/{$loc['codestoquesaldo']}") }}'>
                                                {{ formataNumero($loc['saldoquantidade'], 0) }}
                                            </a>
                                        </td>
                                        <td class='text-right'>{{ formataNumero($loc['saldodias'], 0) }}</td>
                                        <td class='text-right'>{{ formataNumero($loc['customedio'], 4) }}</td>
                                        <td class='text-right'>{{ formataData($loc['dataentrada']) }}</td>
                                        <td class='text-right'>{{ formataNumero($loc['estoqueminimo'], 0) }}</td>
                                        <td class='text-right'>{{ formataNumero($loc['estoquemaximo'], 0) }}</td>
                                        <td class='text-right'>{{ formataNumero($loc['vendabimestre'], 0) }}</td>
                                        <td class='text-right'>{{ formataNumero($loc['vendasemestre'], 0) }}</td>
                                        <td class='text-right'>{{ formataNumero($loc['vendaano'], 0) }}</td>
                                        <td class='text-right'>{{ formataNumero($loc['vendaprevisaoquinzena'], 0) }}</td>
                                    </tr>
                                    @if ($qtdLocais > 1)
                                        <tr>
                                    @endif
                                @endforeach
                                @if ($qtdLocais == 0)
                                        <td colspan='11' class='text-center'> --- Sem Movimento --- </td>
                                    </tr>
                                @elseif ($rowspanVariacao > 1)
                                        <td class='total' colspan='2'>Sub-Total Variação</td>
                                        <td class='total text-right'>{{ formataNumero($var['saldoquantidade'], 0) }}</td>
                                        <td class='total text-right'>{{ formataNumero($var['saldodias'], 0) }}</td>
                                        <td class='total text-right'>{{ formataNumero($var['customedio'], 4) }}</td>
                                        <td class='total'></td>
                                        <td class='total text-right'>{{ formataNumero($var['estoqueminimo'], 0) }}</td>
                                        <td class='total text-right'>{{ formataNumero($var['estoquemaximo'], 0) }}</td>
                                        <td class='total text-right'>{{ formataNumero($var['vendabimestre'], 0) }}</td>
                                        <td class='total text-right'>{{ formataNumero($var['vendasemestre'], 0) }}</td>
                                        <td class='total text-right'>{{ formataNumero($var['vendaano'], 0) }}</td>
                                        <td class='total text-right'>{{ formataNumero($var['vendaprevisaoquinzena'], 0) }}</td>
                                    </tr>
                                @endif
                        @endforeach
                        @if ($qtdVariacoes > 1)
                            <tr>
                                <td class='total' colspan='3'>Sub-Total Produto</td>
                                <td class='total text-right'>{{ formataNumero($prod['quantidadecompra'], 0) }}</td>
                                <td class='total text-right'>{{ formataNumero($prod['custocompra']) }}</td>
                                <td class='total' colspan='2'></td>
                                <td class='total text-right'>{{ formataNumero($prod['saldoquantidade'], 0) }}</td>
                                <td class='total text-right'>{{ formataNumero($prod['saldodias'], 0) }}</td>
                                <td class='total text-right'>{{ formataNumero($prod['customedio'], 4) }}</td>
                                <td class='total'></td>
                                <td class='total text-right'>{{ formataNumero($prod['estoqueminimo'], 0) }}</td>
                                <td class='total text-right'>{{ formataNumero($prod['estoquemaximo'], 0) }}</td>
                                <td class='total text-right'>{{ formataNumero($prod['vendabimestre'], 0) }}</td>
                                <td class='total text-right'>{{ formataNumero($prod['vendasemestre'], 0) }}</td>
                                <td class='total text-right'>{{ formataNumero($prod['vendaano'], 0) }}</td>
                                <td class='total text-right'>{{ formataNumero($prod['vendaprevisaoquinzena'], 0) }}</td>
                            </tr>
                        @endif
                @endforeach
            </tbody>
        </table>
    @endforeach
    <?php /*
     * 
     */
    ?>
</div>
<div class='rodape'>

</div>
@section('inscript')
<script type="text/javascript">
$(document).ready(function() {
});
</script>
@endsection
@stop
