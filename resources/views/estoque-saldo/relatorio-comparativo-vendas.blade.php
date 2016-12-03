@extends('layouts.print-portrait')
@section('content')
<div class='cabecalho'>
  <a href="{{ $dados['urlfiltro'] }}">
    Relatório Vendas {{ $dados['estoquelocal_filial'] }} X Saldo {{ $dados['estoquelocal_deposito'] }}
  </a>
</div>
<div class='conteudo'>
    <!--
    1234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|
    -->
    
    @foreach($dados['agrupamentos'] as $codigo => $agr)
        <h2 style="border-bottom: 0px; padding-bottom: 0px;">
          <a href="{{ url('marca', $agr['codmarca']) }}">
            {{ $agr['marca'] }}
          </a>
        </h2>
        <table class="relatorio">
            <thead class='negativo'>
                <tr>
                    <th class="codigo">#</th>
                    <th class="produto">Produto</th>
                    <th class="referencia">Referência</th>
                    <th class="barras">Barras</th>
                    <th class="quantidade">Vendas</th>
                    <th class="quantidade">Saldo</th>
                    <th class="min_max">Min</th>
                    <th class="min_max">Máx</th>
                    <th class="quantidade">15d</th>
                    <th class="localizacao">Localização</th>
                    <th class="quantidade">Disp</th>
                </tr>
            </thead>
            <tbody class='zebrada'>
                @foreach($agr['produtos'] as $codprodutovariacao => $prod)
                <tr>
                    <td class="codigo">{{ formataCodigo($prod->codproduto, 6) }}</td>
                    <td class="produto">
                        @if (!empty($prod->inativo))
                            <strike><a href="{{ url('produto', $prod->codproduto) }}">{{ $prod->produto }}</a></strike>
                            <span class='text-danger'>Inativo desde {{ formataData($prod->inativo) }}</span>
                        @else
                            <a href="{{ url('produto', $prod->codproduto) }}">{{ $prod->produto }}</a>
                        @endif
                      
                      {{ $prod->variacao }}
                    </td>
                    <td class="referencia">{{ $prod->referencia }}</td>
                    <td class="barras">
                        @foreach ($prod->barrass as $barras)
                        {{ $barras }} <br>
                        @endforeach
                    </td>
                    <td class="quantidade text-right">{{ formataNumero($prod->quantidade_vendida, 0) }}</td>
                    <td class="quantidade text-right">
                      <a href="{{ url('estoque-saldo', $prod->codestoquesaldo_filial) }}">
                        {{ formataNumero($prod->saldoquantidade_filial, 0) }}
                      </a>
                    </td>
                    <td class="min_max text-right">{{ formataNumero($prod->estoqueminimo, 0) }}</td>
                    <td class="min_max text-right">{{ formataNumero($prod->estoquemaximo, 0) }}</td>
                    <td class="quantidade text-right">{{ formataNumero($prod->previsaoquantidade_quinzena, 0) }}</td>
                    <td class="localizacao text-center">
                        {{ formataLocalEstoque($prod->corredor, $prod->prateleira, $prod->coluna, $prod->bloco) }}
                    </td>
                    <td class="quantidade text-right">
                      <a href="{{ url('estoque-saldo', $prod->codestoquesaldo_deposito) }}">
                        {{ formataNumero($prod->saldoquantidade_deposito, 0) }}
                      </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
    
</div>
<div class='rodape'>
</div>
@section('inscript')
<style>
  
/*table.relatorio {table-layout:fixed; width:20cm;}*//*Setting the table width is important!*/
/*table.relatorio td {overflow:hidden;}*/ /*Hide text outside the cell.*/

    td, th {
        font-size: 0.9em;
        vertical-align: middle;
    }

  .codigo {
      width: 1.0cm;
  }
  .produto {
      width: 6.0cm;
  }
  .referencia {
      width: 1.5cm;
  }
  td.referencia {
      font-size: 0.7em;
  }
  .barras {
      width: 1.5cm;
  }
  td.barras {
      font-size: 0.7em;
  }
  .quantidade {
      width: 0.8cm;
  }
  .localizacao {
      width: 0.7cm;
  }
  .min_max {
      width: 0.8cm;
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
