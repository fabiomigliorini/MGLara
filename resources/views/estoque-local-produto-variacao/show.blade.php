<?php
use MGLara\Models\EstoqueLocalProdutoVariacao;
use MGLara\Models\EstoqueSaldo;
//dd ($prod);
//dd ($locais);
?>
@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
        </ul>
    </div>
</nav>
<ol class="breadcrumb header">
{!! 
    titulo(
        $prod->codproduto,
        [
            url("produto") => 'Produtos',
            url("produto/{$prod->codproduto}") => $prod->produto,
            'Locais de Estoque',
        ],
        $prod->inativo,
        6
    ) 
!!}     
</ol>
<hr>
@foreach($prod->ProdutoVariacaoS as $pv)
    <h3>{{ $pv->variacao }}</h3>
    @foreach ($locais as $el)
        <?php
        $elpv = EstoqueLocalProdutoVariacao::where('codestoquelocal', $el->codestoquelocal)->where('codprodutovariacao', $pv->codprodutovariacao)->first();
        if ($elpv == null) {
            $elpv = new EstoqueLocalProdutoVariacao;
            $elpv->codestoquelocal = $el->codestoquelocal;
            $elpv->codprodutovariacao = $pv->codprodutovariacao;
        }
        $es = $elpv->EstoqueSaldoS()->where('fiscal', false)->first();
        if ($es == null) {
            $es = new EstoqueSaldo;
            $es->codestoquelocalprodutovariacao = $elpv->codestoquelocalprodutovariacao;
        }
        ?>
        <div class='row'>
            <div class='col-lg-1'>
                {{ $el->estoquelocal }}
            </div>
            <div class='col-lg-1 text-center'>
                {{ formataLocalEstoque($elpv->corredor, $elpv->prateleira, $elpv->coluna, $elpv->bloco) }}
            </div>
            <div class='col-lg-1 text-center'>
                @if (!empty($elpv->estoqueminimo))
                    {{ formataNumero($elpv->estoqueminimo, 2)}} <span class='glyphicon glyphicon-arrow-down'></span> 
                @endif
                @if (!empty($elpv->estoquemaximo))
                    {{ formataNumero($elpv->estoquemaximo, 2)}} <span class='glyphicon glyphicon-arrow-up'></span> 
                @endif
            </div>
            <div class='col-lg-1 text-center'>
                @if ($elpv->vendadiaquantidadeprevisao != 0)
                    {{ formataNumero($elpv->vendadiaquantidadeprevisao * 15, 3)}} 
                    <span class='glyphicon glyphicon glyphicon-forward'></span> 
                @endif
            </div>
            <div class='col-lg-1 text-center'>
                {{ formataNumero($elpv->vendabimestrequantidade, 0)}} /
                {{ formataNumero($elpv->vendasemestrequantidade, 0)}} /
                {{ formataNumero($elpv->vendaanoquantidade, 0)}}
                 <span class='glyphicon glyphicon-shopping-cart'></span> 
            </div>
            <div class='col-lg-1 text-center'>
                {{ formataNumero($es->saldoquantidade, 0)}} /
                @if ($elpv->vendadiaquantidadeprevisao != 0)
                    {{ formataNumero($es->saldoquantidade / $elpv->vendadiaquantidadeprevisao, 0)}}
                @else
                    &infin;
                @endif
            </div>
            <div class='col-lg-1 text-center'>
                @if (!empty($elpv->vencimento))
                    {{ formataData($elpv->vencimento)}} <span class='glyphicon glyphicon-calendar'></span> 
                @endif
            </div>
            

        </div>
    @endforeach
    <hr>
@endforeach
<hr>
@stop