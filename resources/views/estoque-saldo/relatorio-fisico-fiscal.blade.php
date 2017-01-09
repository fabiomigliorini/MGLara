@extends('layouts.print-portrait')
@section('content')
<div class='cabecalho'>
  <a href="{{ $dados['urlfiltro'] }}">
    Relatório Físico X Fiscal
  </a>
</div>
<div class='conteudo'>
    <!--
    1234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|
    -->
    
    <table class="relatorio">
        <thead class='negativo'>
            <tr>
                <th colspan='3'></th>
                <th colspan='3'>Físico</th>
                <th colspan='3'>Fiscal</th>
                <th></th>
            </tr>
            <tr>
                <th>#</th>
                <th>Produto</th>
                <th class=''>Venda</th>
                <th>Qtd</th>
                <th>Médio</th>
                <th>Valor</th>
                <th>Qtd</th>
                <th>Médio</th>
                <th>Valor</th>
                <th>Seção</th>
            </tr>
        </thead>
        <tbody class='zebrada'>
          <?php
              //dd($dados);
              ?>
              
            @foreach($dados['itens'] as $prod)
                <tr>
                    <td class="codigo text-center">{{ formataCodigo($prod->codproduto, 6) }}</td>
                    <td class="produto">
                        @if (!empty($prod->inativo))
                            <strike><a href="{{ url('produto', $prod->codproduto) }}">{{ $prod->produto }}</a></strike>
                            <span class='text-danger'>Inativo desde {{ formataData($prod->inativo) }}</span>
                        @else
                            <a href="{{ url('produto', $prod->codproduto) }}">{{ $prod->produto }}</a>
                        @endif
                      
                    </td>
                    <td class='preco text-right'>
                        {{ formataNumero($prod->preco, 2) }}
                    </td>
                    <td class="quantidade text-right">
                        {{ formataNumero($prod->fisico_saldoquantidade, 1) }}
                    </td>
                    <td class="custo text-right">
                        {{ formataNumero($prod->fisico_customedio, 2) }}
                    </td>
                    <td class="valor text-right">
                        {{ formataNumero($prod->fisico_saldovalor, 2) }}
                    </td>
                    <td class="quantidade text-right">
                        {{ formataNumero($prod->fiscal_saldoquantidade, 1) }}
                    </td>
                    <td class="custo text-right">
                        {{ formataNumero($prod->fiscal_customedio, 2) }}
                    </td>
                    <td class="valor text-right">
                        {{ formataNumero($prod->fiscal_saldovalor, 2) }}
                    </td>
                    <td class="secaoproduto">
                      <a href='{{ url('secao-produto', $prod->codsecaoproduto) }}'>
                        {{ $prod->secaoproduto }}
                      </a>
                      /
                      <a href='{{ url('familia-produto', $prod->codfamiliaproduto) }}'>
                        {{ $prod->familiaproduto }}
                      </a>
                      /
                      <a href='{{ url('grupo-produto', $prod->codgrupoproduto) }}'>
                        {{ $prod->grupoproduto }}
                      </a>
                      /
                      <a href='{{ url('sub-grupo-produto', $prod->codsubgrupoproduto) }}'>
                        {{ $prod->subgrupoproduto }}
                      </a>
                      /
                      <a href='{{ url('marca', $prod->codmarca) }}'>
                        {{ $prod->marca }}
                      </a>
                      
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td class="total" colspan='3'>Total</td>
                <td class="total quantidade text-right">
                    {{ formataNumero($dados['totais']['fisico_saldoquantidade'], 1) }}
                </td>
                <td class="total valor text-right" colspan='2'>
                    {{ formataNumero($dados['totais']['fisico_saldovalor'], 1) }}
                </td>
                <td class="total quantidade text-right">
                    {{ formataNumero($dados['totais']['fiscal_saldoquantidade'], 1) }}
                </td>
                <td class="total valor text-right" colspan='2'>
                    {{ formataNumero($dados['totais']['fiscal_saldovalor'], 1) }}
                </td>
                <td class="total"></td>
            </tr>
        </tfoot>
    </table>
    
</div>
<div class='rodape'>
</div>
@section('inscript')
<style>
  
    td, th {
        vertical-align: middle;
    }
    
    .secaoproduto {
        font-size: 0.7em;
        width: 5.0cm;
    }

    .codigo {
        width: 0.9cm;
    }
    
    .produto {
        width: 5.5cm;
    }

    .preco {
       font-size: 0.7em;
        width: 0.5cm;
    }

    .quantidade {
        width: 0.5cm;
        font-weight: bold;
    }
    
    .custo {
        font-size: 0.7em;
        width: 0.5cm;
    }
    .valor {
        font-size: 0.7em;
        width: 0.5cm;
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
