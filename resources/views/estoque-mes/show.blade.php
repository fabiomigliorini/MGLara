@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <!--
            <li><a href="{{ url("estoque-movimento/create/$model->codestoquemes") }}"><span class="glyphicon glyphicon-plus"></span> Novo</a></li>             
            <li><a href="#" id="btnRecalculaMovimentoEstoque"><span class="glyphicon glyphicon-refresh"></span> Recalcular Movimento de Estoque</a></li>
            <li><a href="#" id="btnRecalculaCustoMedio"><span class="glyphicon glyphicon-usd"></span> Recalcular Custo Medio</a></li>
            -->
        </ul>
    </div>
</nav>

<h1 class="header">
    {!!
        titulo(
                $model->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->codproduto, 
                [
                    url("produto/{$model->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->codproduto}")=>$model->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->Produto->produto,
                    (empty($model->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->variacao))?"<i class='text-muted'>{ Sem Variação }</i>":$model->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->variacao,
                    url("estoque-local/{$model->EstoqueSaldo->EstoqueLocalProdutoVariacao->codestoquelocal}")=>$model->EstoqueSaldo->EstoqueLocalProdutoVariacao->EstoqueLocal->estoquelocal,
                    ($model->EstoqueSaldo->fiscal)?"Fiscal":"Fisico"
                ],
                $model->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->Produto->inativo,
                6
        )
    !!}
</h1>
<hr>
<?php

    $proximos = $model->buscaProximos(8);
    $anteriores = $model->buscaAnteriores(16 - sizeof($proximos));
    if (sizeof($anteriores) < 8)
        $proximos = $model->buscaProximos(16 - sizeof($anteriores));

?>

<ul class="nav nav-pills">
    @foreach($anteriores as $em)
        <li role="presentation"><a href="<?php echo url("estoque-mes/$em->codestoquemes");?>">{{ formataData($em->mes, 'EC') }}</a></li>
    @endforeach
    <li role="presentation" class="active"><a href="#">{{ formataData($model->mes, 'EC') }}</a></li>
    @foreach($proximos as $em)
        <li role="presentation"><a href="<?php echo url("estoque-mes/$em->codestoquemes");?>">{{ formataData($em->mes, 'EC') }}</a></li>
    @endforeach
</ul>
<br>
<table class="table table-striped table-bordered table-condensed small">
    <thead>
        <tr>
            <th rowspan="2" class='col-sm-3'>Movimento</th>
            <th colspan="2">Entrada</th>
            <th colspan="2">Saída</th>
            <th colspan="2">Saldo</th>
            <th rowspan="2" class='col-sm-1'>Custo Médio</th>
            <th rowspan="2" class='col-sm-2'>Documento</th>
        </tr>
        <tr>
            <th class='col-sm-1'>Quantidade</th>
            <th class='col-sm-1'>Valor</th>
            <th class='col-sm-1'>Quantidade</th>
            <th class='col-sm-1'>Valor</th>
            <th class='col-sm-1'>Quantidade</th>
            <th class='col-sm-1'>Valor</th>            
        </tr>
    </thead>
    <tbody>
        <?php
            $saldoquantidade = $model->inicialquantidade;
            $saldovalor = $model->inicialvalor;
            $customedio = ($saldoquantidade != 0)?$saldovalor/$saldoquantidade:0;
        ?>
        <tr>
            <th colspan="5">Saldo Inicial</th>
            <td class="text-right <?php echo ($saldoquantidade < 0)?"text-danger":""; ?>">{{ formataNumero($saldoquantidade, 3) }}</td>
            <td class="text-right <?php echo ($saldovalor < 0)?"text-danger":""; ?>">{{ formataNumero($saldovalor) }}</td>
            <td class="text-right">{{ formataNumero($customedio, 6) }}</td>
            <td></td>
        </tr>
        @foreach($model->EstoqueMovimentoS()->orderBy('data', 'asc')->orderBy('entradaquantidade', 'asc')->negativos(app('request')->input('negativos'))->get() as $row)
        <tr>
            <?php
                $saldoquantidade += $row->entradaquantidade - $row->saidaquantidade;
                $saldovalor += $row->entradavalor - $row->saidavalor;
                $customedio = (($row->entradaquantidade + $row->saidaquantidade) != 0)?($row->entradavalor + $row->saidavalor)/(($row->entradaquantidade + $row->saidaquantidade)):0;
            ?>
            <td>
                <span class='pull-right'>
                    {{ $row->data->format('d/m/y H:i') }}
                </span>
                {{ $row->EstoqueMovimentoTipo->descricao }}
                @if (isset($row->codestoquemovimentoorigem))
                    De 
                    <a href="{{ url("estoque-mes/" . $row->EstoqueMovimentoOrigem->codestoquemes) }}">
                       {{ $row->EstoqueMovimentoOrigem->EstoqueMes->EstoqueSaldo->EstoqueLocalProdutoVariacao->EstoqueLocal->estoquelocal }}
                    </a>
                @endif

                @foreach ($row->EstoqueMovimentoS as $em)
                    P/
                    <a href="{{ url("estoque-mes/" . $em->codestoquemes) }}">
                        {{ $em->EstoqueMes->EstoqueSaldo->EstoqueLocalProdutoVariacao->EstoqueLocal->estoquelocal }}
                    </a>
                @endforeach
            </td>
            <td class="text-right">{{ formataNumero($row->entradaquantidade, 3) }}</td>
            <td class="text-right">{{ formataNumero($row->entradavalor) }}</td>
            <td class="text-right">{{ formataNumero($row->saidaquantidade, 3) }}</td>
            <td class="text-right">{{ formataNumero($row->saidavalor) }}</td>
            <td class="text-right <?php echo ($saldoquantidade < 0)?"text-danger":""; ?>">{{ formataNumero($saldoquantidade, 3) }}</td>
            <td class="text-right <?php echo ($saldovalor < 0)?"text-danger":""; ?>"">{{ formataNumero($saldovalor) }}</td>
            <td class="text-right">{{ formataNumero($customedio, 6) }}</td>
            <td>
                @if (isset($row->codnotafiscalprodutobarra))
                    <a href='{{ url("nota-fiscal/{$row->NotaFiscalProdutoBarra->NotaFiscal->codnotafiscal}" )}}'>{{ formataCodigo($row->NotaFiscalProdutoBarra->NotaFiscal->codnotafiscal) }}</a>
                    -
                    <a href='{{ url("pessoa/{$row->NotaFiscalProdutoBarra->NotaFiscal->codpessoa}" )}}'>{{ $row->NotaFiscalProdutoBarra->NotaFiscal->Pessoa->fantasia }}</a>
                @endif
                
                @if (isset($row->codnegocioprodutobarra))
                    <a href='{{ url("negocio/{$row->NegocioProdutoBarra->Negocio->codnegocio}" )}}'>{{ formataCodigo($row->NegocioProdutoBarra->Negocio->codnegocio) }}</a>
                    -
                    <a href='{{ url("pessoa/{$row->NegocioProdutoBarra->Negocio->codpessoa}" )}}'>{{ $row->NegocioProdutoBarra->Negocio->Pessoa->fantasia }}</a>
                @endif
                
                @if($row->manual)
                <a href="{{ url("estoque-movimento/$row->codestoquemovimento/edit") }}" class="pull-right btn btn-info btn-sm">
                    <i class="glyphicon glyphicon-pencil"></i>
                </a>
                @endif
                
                {{ $row->observacoes }}
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
            <th>Totais</th>
            <th class="text-right">{{ formataNumero($model->entradaquantidade, 3) }}</th>
            <th class="text-right">{{ formataNumero($model->entradavalor) }}</th>
            <th class="text-right">{{ formataNumero($model->saidaquantidade, 3) }}</th>
            <th class="text-right">{{ formataNumero($model->saidavalor) }}</th>
            <th class="text-right <?php echo ($model->saldoquantidade < 0)?"text-danger":""; ?>">{{ formataNumero($model->saldoquantidade, 3) }}</th>
            <th class="text-right <?php echo ($model->saldovalor < 0)?"text-danger":""; ?>"">{{ formataNumero($model->saldovalor) }}</th>
            <th class="text-right">{{ formataNumero($model->customedio, 6) }}</th>
            <th></th>
        </tr>
    </tfoot>
    </tbody>
</table>
@stop