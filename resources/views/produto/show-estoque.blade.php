<?php

use MGLara\Models\EstoqueLocal;
use MGLara\Models\EstoqueSaldo;
use MGLara\Models\EstoqueLocalProdutoVariacao;
use MGLara\Models\ProdutoVariacao;

$pvs = $model->ProdutoVariacaoS()->orderBy('variacao', 'ASC')->get();

function decideLabel($data)
{
    if ($data == null)
        return 'label-default';
    
    $dias = $data->diffInDays();
    
    if ($dias > 30)
        return 'label-danger';
  
    if ($dias > 15)
        return 'label-warning';
    
    return 'label-success';
}

?>
<div class="panel-group" id="div-estoque">
<small>
    <div class="panel panel-default panel-condensed">
        
        <!-- Titulo -->
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-8">
                    <b>Local de Estoque</b>
                    <b class='pull-right'>
                        <small>
                        Min <span class='glyphicon glyphicon-arrow-down'></span> 
                        Max <span class='glyphicon glyphicon-arrow-up'></span> 
                        Localização
                        </small>
                    </b>
                </div>
                <div class="col-md-2 text-right">
                    <b>Físico</b>
                </div>
                <div class="col-md-2 text-right">
                    <b>Fiscal</b>
                </div>
            </div>
        </div>
        
    </div>
        
    @foreach (EstoqueLocal::ativo()->orderBy('codestoquelocal', 'asc')->get() as $el)
        <?php
            $saldoQuantidadeFisico[$el->codestoquelocal] = [];
            $saldoQuantidadeFiscal[$el->codestoquelocal] = [];
            $ultimaConferenciaFisico[$el->codestoquelocal] = [];
            $ultimaConferenciaFiscal[$el->codestoquelocal] = [];
        ?>
        
        <div class="panel panel-default panel-condensed">
        
            <!-- Variacoes do Produto -->
            <div id="collapseEstoqueLocal{{ $el->codestoquelocal }}" class="panel-collapse collapse">
                <ul class="list-group list-group-condensed list-group-striped list-group-hover list-group-condensed">

                    @foreach ($pvs as $pv)
                        <?php
                            $saldoQuantidadeFisico[$el->codestoquelocal][$pv->codprodutovariacao] = 0;
                            $saldoQuantidadeFiscal[$el->codestoquelocal][$pv->codprodutovariacao] = 0;
                            $ultimaConferenciaFisico[$el->codestoquelocal][$pv->codprodutovariacao] = null;
                            $ultimaConferenciaFiscal[$el->codestoquelocal][$pv->codprodutovariacao] = null;
                        ?>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-md-8">
                                    @if (!empty($pv->variacao))
                                        {{ $pv->variacao }}
                                    @else
                                        <i class='text-muted'>{ Sem Variação }</i>
                                    @endif
                                    @if ($elpv = $pv->EstoqueLocalProdutoVariacaoS()->where('codestoquelocal', $el->codestoquelocal)->first())
                                            <small class="text-muted pull-right">
                                                @if (!empty($elpv->estoqueminimo))
                                                    {{ formataNumero($elpv->estoqueminimo, 0) }} <span class='glyphicon glyphicon-arrow-down'></span>
                                                @endif
                                                @if (!empty($elpv->estoquemaximo))
                                                    {{ formataNumero($elpv->estoquemaximo, 0) }} <span class='glyphicon glyphicon-arrow-up'></span>
                                                @endif
                                                {{ formataLocalEstoque($elpv->corredor, $elpv->prateleira, $elpv->coluna, $elpv->bloco) }}
                                            </small>
                                        </div>
                                        <div class="col-md-2 text-right">
                                            @if ($fisico = $elpv->EstoqueSaldoS->where('fiscal', false)->first())
                                                <?php
                                                    $saldoQuantidadeFisico[$el->codestoquelocal][$pv->codprodutovariacao] += $fisico->saldoquantidade;
                                                    if (!empty($fisico->ultimaconferencia))
                                                        if ((!isset($ultimaConferenciaFisico[$el->codestoquelocal][$pv->codprodutovariacao])) || ($fisico->ultimaconferencia->lt($ultimaConferenciaFisico[$el->codestoquelocal][$pv->codprodutovariacao])))
                                                            $ultimaConferenciaFisico[$el->codestoquelocal][$pv->codprodutovariacao] = $fisico->ultimaconferencia;
                                                ?>
                                                <a href="{{ url("estoque-saldo/{$fisico->codestoquesaldo}") }}">
                                                    {{ formataNumero($fisico->saldoquantidade, 0) }}
                                                </a>
                                                <span class="label {{ decideLabel($fisico->ultimaconferencia) }}">
                                                    &nbsp;
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-md-2 text-right">
                                            @if ($fiscal = $elpv->EstoqueSaldoS->where('fiscal', true)->first())
                                                <?php
                                                    $saldoQuantidadeFiscal[$el->codestoquelocal][$pv->codprodutovariacao] += $fiscal->saldoquantidade;
                                                    if (!empty($fiscal->ultimaconferencia))
                                                        if ((!isset($ultimaConferenciaFiscal[$el->codestoquelocal][$pv->codprodutovariacao])) || ($fiscal->ultimaconferencia->lt($ultimaConferenciaFiscal[$el->codestoquelocal][$pv->codprodutovariacao])))
                                                            $ultimaConferenciaFiscal[$el->codestoquelocal][$pv->codprodutovariacao] = $fiscal->ultimaconferencia;
                                                ?>
                                                <a href="{{ url("estoque-saldo/{$fiscal->codestoquesaldo}") }}">
                                                    {{ formataNumero($fiscal->saldoquantidade, 0) }}
                                                </a>
                                                <span class="label {{ decideLabel($fiscal->ultimaconferencia) }}">
                                                    &nbsp;
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        </div>
                                    @endif
                            </div>              
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Total Local -->
            <div class="panel-body">
                <a data-toggle="collapse" href="#collapseEstoqueLocal{{ $el->codestoquelocal }}">
                    <div class="row">
                        <div class="col-md-8">
                            <b>{{ $el->estoquelocal }}</b>
                        </div>
                        <div class="col-md-2 text-right">
                            {{ formataNumero(array_sum($saldoQuantidadeFisico[$el->codestoquelocal]), 0) }}
                            <span class="label {{ decideLabel(min($ultimaConferenciaFisico[$el->codestoquelocal])) }}">
                                &nbsp;
                            </span>
                        </div>
                        <div class="col-md-2 text-right">
                            {{ formataNumero(array_sum($saldoQuantidadeFiscal[$el->codestoquelocal]), 0) }}
                            <span class="label {{ decideLabel(min($ultimaConferenciaFiscal[$el->codestoquelocal])) }}">
                                &nbsp;
                            </span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    @endforeach
        
    <!-- Totais -->
    <?php
    
    $totalQuantidadeFisico = [];
    foreach($saldoQuantidadeFisico as $codestoquelocal => $arr)
    {
        foreach($arr as $codprodutovariacao => $saldo)
        {
            if (!isset($totalQuantidadeFisico[$codprodutovariacao]))
                $totalQuantidadeFisico[$codprodutovariacao] = 0;
            $totalQuantidadeFisico[$codprodutovariacao] += $saldo;
        }
    }
    
    $totalQuantidadeFiscal = [];
    foreach($saldoQuantidadeFiscal as $codestoquelocal => $arr)
    {
        foreach($arr as $codprodutovariacao => $saldo)
        {
            if (!isset($totalQuantidadeFiscal[$codprodutovariacao]))
                $totalQuantidadeFiscal[$codprodutovariacao] = 0;
            $totalQuantidadeFiscal[$codprodutovariacao] += $saldo;
        }
    }
    
    $minConferenciaFisico = [];
    foreach ($ultimaConferenciaFisico as $codestoquelocal => $arr)
    {
        foreach ($arr as $codprodutovariacao => $data)
        {
            if (!isset($minConferenciaFisico[$codprodutovariacao]))
                $minConferenciaFisico[$codprodutovariacao] = null;
            
            if ($minConferenciaFisico[$codprodutovariacao] == null)
                $minConferenciaFisico[$codprodutovariacao] = $data;
            elseif ($data !== null && $data->lt($minConferenciaFisico[$codprodutovariacao]))
                $minConferenciaFisico[$codprodutovariacao] = $data;
                
        }
    }
    
    $minConferenciaFiscal = [];
    foreach ($ultimaConferenciaFiscal as $codestoquelocal => $arr)
    {
        foreach ($arr as $codprodutovariacao => $data)
        {
            if (!isset($minConferenciaFiscal[$codprodutovariacao]))
                $minConferenciaFiscal[$codprodutovariacao] = null;
            
            if ($minConferenciaFiscal[$codprodutovariacao] == null)
                $minConferenciaFiscal[$codprodutovariacao] = $data;
            elseif ($data !== null && $data->lt($minConferenciaFiscal[$codprodutovariacao]))
                $minConferenciaFiscal[$codprodutovariacao] = $data;
                
        }
    }
    
    ?>
    <div class="panel panel-default panel-condensed">
        <div id="collapseEstoqueLocalFooter" class="panel-collapse collapse">
            <ul class="list-group list-group-condensed list-group-striped list-group-condensed">
                @foreach ($pvs as $pv)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-md-8">
                                @if (!empty($pv->variacao))
                                    {{ $pv->variacao }}
                                @else
                                    <i class='text-muted'>{ Sem Variação }</i>
                                @endif
                            </div>
                            <div class="col-md-2 text-right">
                                {{ formataNumero($totalQuantidadeFisico[$pv->codprodutovariacao], 0) }}
                                <span class="label {{ decideLabel($minConferenciaFisico[$pv->codprodutovariacao]) }}">
                                    &nbsp;
                                </span>
                            </div>
                            <div class="col-md-2 text-right">
                                {{ formataNumero($totalQuantidadeFiscal[$pv->codprodutovariacao], 0) }}
                                <span class="label {{ decideLabel($minConferenciaFiscal[$pv->codprodutovariacao]) }}">
                                    &nbsp;
                                </span>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="panel-heading">
            <a data-toggle="collapse" href="#collapseEstoqueLocalFooter">
                <div class='row'>
                    <div class="col-md-8">
                        <b>Total</b>
                    </div>
                    <div class="col-md-2 text-right">
                        <b>
                            {{ formataNumero(array_sum($totalQuantidadeFisico), 0) }}
                        </b>
                        <span class="label {{ decideLabel(min($minConferenciaFisico)) }}">
                            &nbsp;
                        </span>
                    </div>
                    <div class="col-md-2 text-right">
                        <b>
                            {{ formataNumero(array_sum($totalQuantidadeFiscal), 0) }}
                        </b>
                        <span class="label {{ decideLabel(min($minConferenciaFiscal)) }}">
                            &nbsp;
                        </span>
                    </div>
                </div>
            </a>
        </div>
    </div>
</small>    
</div>
