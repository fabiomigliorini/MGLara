@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
  <div class="container-fluid"> 
    <ul class="nav navbar-nav">
      <!--
      <li>
        <a href="<?php echo url('permissao/create');?>"><span class="glyphicon glyphicon-plus"></span> Novo</a>
      </li>
      -->
    </ul>
  </div>
</nav>
<h1 class="header">
    <a href="{{ url("grupo-produto/$model->codgrupoproduto") }}">
        {{ $model->GrupoProduto->grupoproduto }} 
    </a>
    > {{ $model->subgrupoproduto }}
</h1>
<br>

<?php

foreach($model->ProdutoS as $prod)
{
    foreach ($prod->EstoqueSaldoS as $es)
    {
        $arr_saldos[$prod->codproduto][$es->codestoquelocal][$es->fiscal] = [
            'codestoquesaldo' => $es->codestoquesaldo,
            'saldoquantidade' => $es->saldoquantidade,
            'saldovalor' => $es->saldovalor,
            'saldovalorunitario' => $es->saldovalorunitario,
        ];
        
        if (!isset($arr_totais[$es->codestoquelocal][$es->fiscal]))
            $arr_totais[$es->codestoquelocal][$es->fiscal] = [
                'saldoquantidade' => 0,
                'saldovalor' => 0
            ];

        $arr_totais[$es->codestoquelocal][$es->fiscal]['saldoquantidade'] += $es->saldoquantidade;
        $arr_totais[$es->codestoquelocal][$es->fiscal]['saldovalor'] += $es->saldovalor;
    }
}

?>

<table class="table table-striped table-condensed table-hover table-bordered">
    <thead>
        <th colspan="2">
            Grupo Produto
        </th>
        @foreach ($els as $el)
        <th colspan='3' class='text-center' style='border-left-width: 2px'>
            {{ $el->estoquelocal }}
        </th>
        @endforeach
    </thead>
    
    <tbody>
        @foreach($model->ProdutoS as $row)
        <?php
        if (!isset($arr_saldos[$row->codproduto]))
            continue;
        ?>
        <tr>
            <th rowspan="2">
                <small class='text-muted'>
                    {{ formataCodigo($row->codproduto, 6) }}                    
                </small>
                <a href="{{ url("produto/$row->codproduto") }}">{{$row->produto}}</a>
                <div class='pull-right'>
                    {{ formataNumero($row->preco) }}
                </div>
                <br>
                @if (isset($row->codmarca))
                    {{ $row->Marca->marca }}
                @endif
            </th>
            <th>
                Físico
            </th>
            @foreach ($els as $el)
            <td class='text-right' style='border-left-width: 2px'>
                @if (isset($arr_saldos[$row->codproduto][$el->codestoquelocal][0]))
                    <?php $codestoquesaldo = $arr_saldos[$row->codproduto][$el->codestoquelocal][0]['codestoquesaldo']; ?>
                    <a href="{{ url("estoque-saldo/$codestoquesaldo") }}">
                        {{ formataNumero($arr_saldos[$row->codproduto][$el->codestoquelocal][0]['saldoquantidade'], 0) }}
                    </a>
                @endif
            </td>
            <td class='text-right'>
                @if (isset($arr_saldos[$row->codproduto][$el->codestoquelocal][0]))
                    <?php $codestoquesaldo = $arr_saldos[$row->codproduto][$el->codestoquelocal][0]['codestoquesaldo']; ?>
                    <a href="{{ url("estoque-saldo/$codestoquesaldo") }}">
                        {{ formataNumero($arr_saldos[$row->codproduto][$el->codestoquelocal][0]['saldovalorunitario'], 6) }}
                    </a>
                @endif
            </td>
            <td class='text-right'>
                @if (isset($arr_saldos[$row->codproduto][$el->codestoquelocal][0]))
                    <?php $codestoquesaldo = $arr_saldos[$row->codproduto][$el->codestoquelocal][0]['codestoquesaldo']; ?>
                    <a href="{{ url("estoque-saldo/$codestoquesaldo") }}">
                        {{ formataNumero($arr_saldos[$row->codproduto][$el->codestoquelocal][0]['saldovalor'], 2) }}
                    </a>
                @endif
            </td>
            @endforeach
        </tr>
        <tr>
            <th>
                Fiscal
            </th>
            @foreach ($els as $el)
            <td class='text-right' style='border-left-width: 2px'>
                @if (isset($arr_saldos[$row->codproduto][$el->codestoquelocal][1]))
                    <?php $codestoquesaldo = $arr_saldos[$row->codproduto][$el->codestoquelocal][1]['codestoquesaldo']; ?>
                    <a href="{{ url("estoque-saldo/$codestoquesaldo") }}">
                        {{ formataNumero($arr_saldos[$row->codproduto][$el->codestoquelocal][1]['saldoquantidade'], 0) }}
                    </a>
                @endif
            </td>
            <td class='text-right'>
                @if (isset($arr_saldos[$row->codproduto][$el->codestoquelocal][1]))
                    <?php $codestoquesaldo = $arr_saldos[$row->codproduto][$el->codestoquelocal][1]['codestoquesaldo']; ?>
                    <a href="{{ url("estoque-saldo/$codestoquesaldo") }}">
                        {{ formataNumero($arr_saldos[$row->codproduto][$el->codestoquelocal][1]['saldovalorunitario'], 6) }}
                    </a>
                @endif
            </td>
            <td class='text-right'>
                @if (isset($arr_saldos[$row->codproduto][$el->codestoquelocal][1]))
                    <?php $codestoquesaldo = $arr_saldos[$row->codproduto][$el->codestoquelocal][1]['codestoquesaldo']; ?>
                    <a href="{{ url("estoque-saldo/$codestoquesaldo") }}">
                        {{ formataNumero($arr_saldos[$row->codproduto][$el->codestoquelocal][1]['saldovalor'], 2) }}
                    </a>
                @endif
            </td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
    
    <tfoot>
        <tr>
            <th rowspan="2">
                Totais
            </th>
            <th>
                Físico
            </th>
            @foreach ($els as $el)
            <th class='text-right' style='border-left-width: 2px'>
                @if (isset($arr_totais[$el->codestoquelocal][0]))
                    {{ formataNumero($arr_totais[$el->codestoquelocal][0]['saldoquantidade'], 0) }}
                @endif
            </th>
            <th>
            </th>
            <th class='text-right'>
                @if (isset($arr_totais[$el->codestoquelocal][0]))
                    {{ formataNumero($arr_totais[$el->codestoquelocal][0]['saldovalor'], 2) }}
                @endif
            </th>
            @endforeach
        </tr>
        <tr>
            <th>
                Fiscal
            </th>
            @foreach ($els as $el)
            <th class='text-right' style='border-left-width: 2px'>
                @if (isset($arr_totais[$el->codestoquelocal][1]))
                    {{ formataNumero($arr_totais[$el->codestoquelocal][1]['saldoquantidade'], 0) }}
                @endif
            </th>
            <th>
            </th>
            <th class='text-right'>
                @if (isset($arr_totais[$el->codestoquelocal][1]))
                    {{ formataNumero($arr_totais[$el->codestoquelocal][1]['saldovalor'], 2) }}
                @endif
            </th>
            @endforeach
        </tr>
    </tfoot>
</table>

@if (count($model) === 0)
    <h3>Nenhum registro encontrado!</h3>
@endif    

@section('inscript')
<script type="text/javascript">
  $(document).ready(function() {
      
  });
</script>
@endsection
@stop
