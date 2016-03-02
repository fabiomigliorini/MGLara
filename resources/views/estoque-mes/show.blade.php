@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="{{ url('estoque-mes') }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>             
        </ul>
    </div>
</nav>

<h1 class="header"><small>{{ formataCodigo($model->EstoqueSaldo->codproduto, 6) }}</small> {{ $model->EstoqueSaldo->Produto->produto }} <small>{{ $model->EstoqueSaldo->EstoqueLocal->estoquelocal }} - {{ ($model->EstoqueSaldo->fiscal)?"Fiscal":"Fisico" }}</small></h1>
<ul class="nav nav-tabs">
    @foreach($model->buscaAnteriores() as $em)
        <li role="presentation"><a href="<?php echo url("estoque-mes/$em->codestoquemes");?>">{{ formataData($em->mes, 'EC') }}</a></li>
    @endforeach
    <li role="presentation" class="active"><a href="#">{{ formataData($model->mes, 'EC') }}</a></li>
    @foreach($model->buscaProximos() as $em)
        <li role="presentation"><a href="<?php echo url("estoque-mes/$em->codestoquemes");?>">{{ formataData($em->mes, 'EC') }}</a></li>
    @endforeach
</ul>
<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th rowspan="2">Data</th>
            <th rowspan="2">Tipo</th>
            <th colspan="2">Entrada</th>
            <th colspan="2">Saída</th>
            <th colspan="2">Saldo</th>
            <th rowspan="2">Unitário</th>
            <th rowspan="2">Documento</th>
        </tr>
        <tr>
            <th>Quantidade</th>
            <th>Valor</th>
            <th>Quantidade</th>
            <th>Valor</th>
            <th>Quantidade</th>
            <th>Valor</th>            
        </tr>
    </thead>
    <tbody>
        <?php
        $saldoquantidade = $model->inicialquantidade;
        $saldovalor = $model->inicialvalor;
        $saldovalorunitario = ($saldoquantidade != 0)?$saldovalor/$saldoquantidade:0;
        ?>
        <tr>
            <td></td>
            <th colspan="5">Saldo Inicial</th>
            <td class="text-right <?php echo ($saldoquantidade < 0)?"text-danger":""; ?>">{{ formataNumero($saldoquantidade) }}</td>
            <td class="text-right <?php echo ($saldovalor < 0)?"text-danger":""; ?>">{{ formataNumero($saldovalor) }}</td>
            <td class="text-right">{{ formataNumero($saldovalorunitario, 6) }}</td>
            <td></td>
        </tr>
        @foreach($model->EstoqueMovimentoS()->orderBy('data', 'asc')->orderBy('entradaquantidade', 'asc')->get() as $row)
        <tr>
            <?php
                $saldoquantidade += $row->entradaquantidade - $row->saidaquantidade;
                $saldovalor += $row->entradavalor - $row->saidavalor;
                $saldovalorunitario = ($saldoquantidade != 0)?$saldovalor/$saldoquantidade:0;
            ?>
            <td>{{ formataData($row->data, 'L') }}</td>
            <td>{{ $row->EstoqueMovimentoTipo->descricao }}</td>
            <td class="text-right">{{ formataNumero($row->entradaquantidade) }}</td>
            <td class="text-right">{{ formataNumero($row->entradavalor) }}</td>
            <td class="text-right">{{ formataNumero($row->saidaquantidade) }}</td>
            <td class="text-right">{{ formataNumero($row->saidavalor) }}</td>
            <td class="text-right <?php echo ($saldoquantidade < 0)?"text-danger":""; ?>">{{ formataNumero($saldoquantidade) }}</td>
            <td class="text-right <?php echo ($saldovalor < 0)?"text-danger":""; ?>"">{{ formataNumero($saldovalor) }}</td>
            <td class="text-right">{{ formataNumero($saldovalorunitario, 6) }}</td>
            <td>
                @if (isset($row->codnotafiscalprodutobarra))
                    {{ formataNumero($row->NotaFiscalProdutoBarra->NotaFiscal->numero, 0) }} -
                    {{ $row->NotaFiscalProdutoBarra->NotaFiscal->Pessoa->fantasia }}
                @endif
            </td>
        </tr>
        @endforeach
        @if (count($model) === 0)
        <tr>
            <th colspan="10">Nenhum registro encontrado!</th>
        </tr>
        @endif
    <tfoot>
        <tr>
            <th></th>
            <th>Totais</th>
            <th class="text-right">{{ formataNumero($model->entradaquantidade) }}</th>
            <th class="text-right">{{ formataNumero($model->entradavalor) }}</th>
            <th class="text-right">{{ formataNumero($model->saidaquantidade) }}</th>
            <th class="text-right">{{ formataNumero($model->saidavalor) }}</th>
            <th class="text-right <?php echo ($model->saldoquantidade < 0)?"text-danger":""; ?>">{{ formataNumero($model->saldoquantidade) }}</th>
            <th class="text-right <?php echo ($model->saldovalor < 0)?"text-danger":""; ?>"">{{ formataNumero($model->saldovalor) }}</th>
            <th class="text-right">{{ formataNumero($model->saldovalorunitario, 6) }}</th>
            <th></th>
        </tr>
    </tfoot>
    </tbody>
</table>
<hr>
@stop