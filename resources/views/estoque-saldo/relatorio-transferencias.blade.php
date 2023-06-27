@extends('layouts.print-portrait')
@section('content')
<div class='cabecalho'>
    <a href="{{$dados['urlfiltro']}}">
        Relatório Transferências de Estoque
    </a>
    <small class='pull-right text-right' style='font-size: 0.5em'>
        {{$localorigem->estoquelocal}} X {{$localdestino->estoquelocal}}
        <br>
        {{date('d/m/Y H:i:s')}}
    </small>
</div>
<div class='conteudo'>
    <table class="relatorio">
        <thead class='negativo'>
            <tr>
                <th colspan='3'></th>
                <th colspan='3'>{{$localdestino->estoquelocal}}</th>
                <th colspan='3'>{{$localorigem->estoquelocal}}</th>
            </tr>
            <tr>
                <th>Marca</th>
                <th>Produto</th>
                <th>Barras</th>
                <th>Min</th>
                <th>Max</th>
                <th>Sld</th>
                <th>Sld</th>
                <th>Emb</th>
                <th>Trn</th>
            </tr>
        </thead>
        @foreach ($dados['itens'] as $dadostransf)
        <tbody class='zebrada'>   
                <tr>
                    <td class="codigo text-center">
                        {{$dadostransf->marca}} <br> {{$dadostransf->referencia}}
                    </td>
                    <td class="produto">
                        <a href="{{ url('produto', $dadostransf->codproduto) }}">
                         {{$dadostransf->produto}}
                            @if (!empty($dadostransf->variacao))
                                {{$dadostransf->variacao}}
                            @endif
                        </a> <br>
                        {{formataCodigo($dadostransf->codproduto, 6)}}
                    </td>               
                    <td class="codbarras text-left">
                    <b> @foreach (explode(';', $dadostransf->barras) as $barra)
                            {{$barra}} <br>
                        @endforeach
                    </b>
                    </td>
                    <td class="estoqueminimo text-right">
                        {{formataNumero($dadostransf->estoqueminimo, 0)}}
                    </td>
                    <td class="estoquemaximo text-right">
                    {{formataNumero($dadostransf->estoquemaximo, 0)}}
                    </td>
                    <td class="saldoorigem text-right">
                    {{formataNumero($dadostransf->saldodestino, 0)}}
                    </td>
                    <td class="saldodestino text-right">
                      {{formataNumero($dadostransf->saldoorigem, 0)}}
                    </td>
                    <td class="quantidadeembalagem text-right">
                       {{formataNumero($dadostransf->quantidadeembalagem, 0)}}
                    </td>
                    <td class="transferir text-right" id="transferir">
                       <b>{{formataNumero($dadostransf->transferir, 0)}}
                    </td>
                </tr>
        </tbody>
                <th colspan='1'></th>
                <th colspan='1'></th>
                <th colspan='1'></th>
                <th colspan='1'></th>
                <th colspan='1'></th>
                <th colspan='1'></th>
                <th colspan='1'></th>
                <th colspan='1'></th>
                <th colspan='1'></th>
                
        @endforeach
    </table>
</div>
<div class='rodape'>
</div>
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

const teste = $('#transferir').text();

console.log(teste);
</script>
@stop
