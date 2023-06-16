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
                <th colspan='3'></th>
                <th colspan='3'></th>
                <th></th>
            </tr>
            <tr>
                <th>Marca</th>
                <th>Produto</th>
                <th class=''>Variação</th>
                <th>Estoque Mínimo</th>
                <th>Estoque Máximo</th>
                <th>Saldo Origem</th>
                <th>Saldo Destino</th>
                <th>Quantidade de Embalagem</th>
                <th>Transferir</th>
            </tr>
        </thead>
        @foreach ($dados['itens'] as $dadostransf)
        <tbody class='zebrada'>   
                <tr>
                    <td class="codigo text-center">{{$dadostransf->marca}}</td>
                    <td class="produto">
                        <a href="{{ url('produto', $dadostransf->codproduto) }}">{{$dadostransf->produto}}</a>
                    </td>
                    @if($dadostransf->variacao == null)
                    <td class='variacao text-right'>
                         Sem variação
                    </td>
                    @else
                    <td class='variacao text-right'>
                         {{$dadostransf->variacao}} 
                    </td>
                    @endif
                    <td class="estoqueminimo text-right">
                        {{formataNumero($dadostransf->estoqueminimo, 0)}}
                    </td>
                    <td class="estoquemaximo text-right">
                    {{formataNumero($dadostransf->estoquemaximo, 0)}}
                    </td>
                    <td class="saldoorigem text-right">
                    {{formataNumero($dadostransf->saldoorigem, 0)}}
                    </td>
                    <td class="saldodestino text-right">
                      {{formataNumero($dadostransf->saldodestino, 0)}}
                    </td>
                    <td class="quantidadeembalagem text-right">
                       {{formataNumero($dadostransf->quantidadeembalagem, 0)}}
                    </td>
                    <td class="transferir text-right">
                       {{formataNumero($dadostransf->transferir, 0)}}
                    </td>
                </tr>
        </tbody>
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
@stop
