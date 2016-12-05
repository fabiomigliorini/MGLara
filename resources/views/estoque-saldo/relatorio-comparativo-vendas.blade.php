@extends('layouts.print-portrait')
@section('content')
<div class='cabecalho'>
  <a href="{{ $dados['urlfiltro'] }}">
    Vendas {{ $dados['estoquelocal_filial'] }} X Saldo {{ $dados['estoquelocal_deposito'] }}
  </a>
    <small class='pull-right text-right' style='font-size: 0.5em'>
        De: {{ formataData($dados['filtro']['datainicial'], 'L') }}  
        <br>
        Até: {{ formataData($dados['filtro']['datafinal'], 'L') }}
    </small>
</div>
<div class='conteudo'>
    <!--
    1234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|
    -->
    
    <table class="relatorio">
        <thead class='negativo'>
            <tr>
                <th class="marca">Marca</th>
                <th class="codigo">#</th>
                <th class="produto">Produto</th>
                <th class="referencia">Referência</th>
                <th class="barras">Barras</th>
                <th class="quantidade">Vendas</th>
                <th class="quantidade">Prev. {{ $dados['filtro']['dias_previsao'] }}dd</th>
                <th class="quantidade">Saldo</th>
                <th class="min_max">Min</th>
                <th class="min_max">Máx</th>
                <th class="localizacao">Localização</th>
                <th class="quantidade">Disp</th>
            </tr>
        </thead>
        <tbody class='zebrada'>
          <?php
              //dd($dados);
              ?>
              
            @foreach($dados['itens'] as $prod)
                <tr>
          <?php
              //dd($prod);
              ?>
                    <td class="marca">
                      <a href='{{ url('marca', $prod->codmarca) }}'>
                        {{ $prod->marca }}
                      </a>
                    </td>
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
                        @foreach (json_decode($prod->json_barras) as $barras)
                        {{ $barras }} <br>
                        @endforeach
                    </td>
                    <td class="quantidade text-right">{{ formataNumero($prod->quantidade_vendida, 0) }}</td>
                    <td class="quantidade text-right">{{ formataNumero($prod->previsao_vendas, 0) }}</td>
                    <td class="quantidade text-right">
                      <a href="{{ url('estoque-saldo', $prod->codestoquesaldo_filial) }}">
                        {{ formataNumero($prod->saldoquantidade_filial, 0) }}
                      </a>
                    </td>
                    <td class="min_max text-right">{{ formataNumero($prod->estoqueminimo, 0) }}</td>
                    <td class="min_max text-right">{{ formataNumero($prod->estoquemaximo, 0) }}</td>
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
});
*/
</script>
@endsection
@stop
