<?php

use Carbon\Carbon;
use MGLara\Models\EstoqueLocal;

function linha(
        $codestoquelocal, 
        $estoquelocal,
        $codprodutovariacao,
        $variacao,
        $codestoquesaldo_destaque,
        $codestoquesaldo_fisico,
        $saldoquantidade_fisico,
        $saldovalor_fisico,
        $customedio_fisico,
        $ultimaconferencia_fisico,
        $codestoquesaldo_fiscal,
        $saldoquantidade_fiscal,
        $saldovalor_fiscal,
        $customedio_fiscal,
        $ultimaconferencia_fiscal,
        $somentequantidade)
{
    $label_fisico = 'label-default';
    $label_fiscal = 'label-default';
    
    if (is_a($ultimaconferencia_fisico, 'Carbon'))
    {
        $dias_fisico = $ultimaconferencia_fisico->diffInDays();

        if ($dias_fisico > 30)
            $label_fisico = 'label-danger';
        elseif ($dias_fisico > 15)
            $label_fisico = 'label-warning';
        else
            $label_fisico = 'label-success';
    }
    
    if (is_a($ultimaconferencia_fisico, 'Carbon'))
    {
        $dias_fiscal = $ultimaconferencia_fiscal->diffInDays();

        if ($dias_fiscal > 30)
            $label_fiscal = 'label-danger';
        elseif ($dias_fiscal > 15)
            $label_fiscal = 'label-warning';
        else
            $label_fiscal = 'label-success';
    }
    
    if (empty($codprodutovariacao))
    {
        $class = '';
        $id = '';
    }
    else
    {
        $class = 'panel-collapse collapse';
        $id = "detalhesCodEstoqueLocal$codestoquelocal";
    }
    ?>
    <tr class="{{ $class }}" id="{{ $id }}">
        @if (empty($codprodutovariacao))
            <th>
                <a href="#detalhesCodEstoqueLocal{{$codestoquelocal}}" data-toggle="collapse" >
                    {{ $estoquelocal }}
                </a>
            </th>
        @else
            <td>
                @if (!empty($variacao))
                    {{ $variacao }}
                @else
                    <i class='text-muted'>{ Sem Variação }</i>
                @endif
            </td>
        @endif
        
        <!-- FISICO -->
        <?php
        $class = ($codestoquesaldo_fisico == $codestoquesaldo_destaque && !empty($codestoquesaldo_destaque))?'info':'';
        $url = empty($codestoquesaldo_fisico)?"#detalhesCodEstoqueLocal{$codestoquelocal}":url("estoque-saldo/$codestoquesaldo_fisico");
        $toggle = empty($codestoquesaldo_fisico)?'data-toggle="collapse"':'';
        ?>
        <td class='text-right {{ $class }}'>
            <a href='{{ $url }}' {!! $toggle !!}>
                {{ formataNumero($saldoquantidade_fisico, 3) }}
            </a>
            <a class="label pull-left {{ $label_fisico }}">
                &nbsp;
            </a>
        </td>
        @if (!$somentequantidade)
            <td class='text-right {{ $class }}'>
                <a href='{{ $url }}' {!! $toggle !!}>
                    {{ formataNumero($saldovalor_fisico, 2) }}
                </a>
            </td>
            <td class='text-right {{ $class }}'>
                {{ formataNumero($customedio_fisico, 6) }}
            </td>
        @endif
        
        <!-- FISCAL -->
        <?php
        $class = ($codestoquesaldo_fisico == $codestoquesaldo_destaque && !empty($codestoquesaldo_destaque))?'info':'';
        $url = empty($codestoquesaldo_fiscal)?"#detalhesCodEstoqueLocal{$codestoquelocal}":url("estoque-saldo/$codestoquesaldo_fiscal");
        $toggle = empty($codestoquesaldo_fisico)?'data-toggle="collapse"':'';
        ?>
        <td class='text-right {{ $class }}'>
            <a class="label pull-left {{ $label_fiscal }}">
                &nbsp;
            </a>
            <a href='{{ $url }}' {!! $toggle !!}>
                {{ formataNumero($saldoquantidade_fiscal, 3) }}
            </a>
        </td>
        @if (!$somentequantidade)
            <td class='text-right {{ $class }}'>
                <a href='{{ $url }}' {!! $toggle !!}>
                    {{ formataNumero($saldovalor_fiscal, 2) }}
                </a>
            </td>
            <td class='text-right {{ $class }}'>
                {{ formataNumero($customedio_fiscal, 6) }}
            </td>
        @endif
    </tr>
    <?php
}


?>
<div class="panel panel-default">
<table class='table table-hover table-condensed table-bordered'>
    <thead>
        @if (!$somentequantidade)
            <tr>
                <th rowspan='2' class='col-md-2 text-left'>
                    Local
                </th>
                <th colspan='3' class='text-center'>
                    Físico
                </th>
                <th colspan='3' class='text-center'>
                    Fiscal
                </th>
            </tr>
        @endif
        <tr>
            @if ($somentequantidade)
            <th class='col-md-2 text-right'>
                Local
            </th>
            @endif
            <th class='col-md-1 text-right'>
                @if (!$somentequantidade)
                    Saldo
                @else
                    Físico
                @endif
            </th>
            @if (!$somentequantidade)
                <th class='col-md-1 text-right'>
                    Valor
                </th>
                <th class='col-md-1 text-right'>
                    Custo
                </th>
            @endif
            <th class='col-md-1 text-right'>
                @if (!$somentequantidade)
                    Saldo
                @else
                    Fiscal
                @endif
            </th>
            @if (!$somentequantidade)
                <th class='col-md-1 text-right'>
                    Valor
                </th>
                <th class='col-md-1 text-right'>
                    Custo
                </th>
            @endif
        </tr>
    </thead>
    <?php

    foreach (EstoqueLocal::ativo()->get() as $el)
    {
        $sql = "select 
            pv.codprodutovariacao,
            pv.variacao,
            fisico.codestoquesaldo as codestoquesaldo_fisico, fisico.saldoquantidade as saldoquantidade_fisico, fisico.saldovalor as saldovalor_fisico, fisico.customedio as customedio_fisico, fisico.ultimaconferencia as ultimaconferencia_fisico,
            fiscal.codestoquesaldo as codestoquesaldo_fiscal, fiscal.saldoquantidade as saldoquantidade_fiscal, fiscal.saldovalor as saldovalor_fiscal, fiscal.customedio as customedio_fiscal, fiscal.ultimaconferencia as ultimaconferencia_fiscal
        from tblprodutovariacao pv 
        inner join tblestoquelocalprodutovariacao elpv on (elpv.codestoquelocal = $el->codestoquelocal and elpv.codprodutovariacao = pv.codprodutovariacao)
        left join tblestoquesaldo fisico on (fisico.codestoquelocalprodutovariacao = elpv.codestoquelocalprodutovariacao and fisico.fiscal = false)
        left join tblestoquesaldo fiscal on (fiscal.codestoquelocalprodutovariacao = elpv.codestoquelocalprodutovariacao and fiscal.fiscal = true)
        where pv.codproduto = $codproduto
        order by pv.variacao ASC NULLS FIRST
        ";

        $saldos = DB::select($sql);
        
        $customedio_fisico = null;
        $customedio_fiscal = null;
        
        if (array_sum(array_column($saldos, 'codestoquesaldo_fisico')) == 0)
        {
            $totais[$el->codestoquelocal]['saldoquantidade_fisico'] = null;
            $totais[$el->codestoquelocal]['saldovalor_fisico'] = null;
            $totais[$el->codestoquelocal]['ultimaconferencia_fisico'] = null;
        }
        else
        {
            $totais[$el->codestoquelocal]['saldoquantidade_fisico'] = array_sum(array_column($saldos, 'saldoquantidade_fisico'));
            $totais[$el->codestoquelocal]['saldovalor_fisico'] = array_sum(array_column($saldos, 'saldovalor_fisico'));
            $totais[$el->codestoquelocal]['ultimaconferencia_fisico'] = @max(array_column($saldos, 'ultimaconferencia_fisico'));
            if ($totais[$el->codestoquelocal]['saldoquantidade_fisico'] != 0)
                $customedio_fisico = $totais[$el->codestoquelocal]['saldovalor_fisico'] / $totais[$el->codestoquelocal]['saldoquantidade_fisico'];
        }
        
        if (array_sum(array_column($saldos, 'codestoquesaldo_fiscal')) == 0)
        {
            $totais[$el->codestoquelocal]['saldoquantidade_fiscal'] = null;
            $totais[$el->codestoquelocal]['saldovalor_fiscal'] = null;
            $totais[$el->codestoquelocal]['ultimaconferencia_fiscal'] = null;
        }
        else
        {
            $totais[$el->codestoquelocal]['saldoquantidade_fiscal'] = array_sum(array_column($saldos, 'saldoquantidade_fiscal'));
            $totais[$el->codestoquelocal]['saldovalor_fiscal'] = array_sum(array_column($saldos, 'saldovalor_fiscal'));
            $totais[$el->codestoquelocal]['ultimaconferencia_fiscal'] = @max(array_column($saldos, 'ultimaconferencia_fiscal'));
            if ($totais[$el->codestoquelocal]['saldoquantidade_fiscal'] != 0)
                $customedio_fiscal = $totais[$el->codestoquelocal]['saldovalor_fiscal'] / $totais[$el->codestoquelocal]['saldoquantidade_fiscal'];
        }
            
        
        linha(
                $el->codestoquelocal, 
                $el->estoquelocal, 
                null, 
                null, 
                null,
                null, 
                $totais[$el->codestoquelocal]['saldoquantidade_fisico'],
                $totais[$el->codestoquelocal]['saldovalor_fisico'],
                $customedio_fisico,
                $totais[$el->codestoquelocal]['ultimaconferencia_fisico'],
                null, 
                $totais[$el->codestoquelocal]['saldoquantidade_fiscal'],
                $totais[$el->codestoquelocal]['saldovalor_fiscal'],
                $customedio_fiscal,
                $totais[$el->codestoquelocal]['ultimaconferencia_fiscal'],
                $somentequantidade
                );
        
        ?>
        @foreach ($saldos as $saldo)
        
            <?php
            linha(
                    $el->codestoquelocal, 
                    $el->estoquelocal, 
                    $saldo->codprodutovariacao, 
                    $saldo->variacao, 
                    65370,
                    $saldo->codestoquesaldo_fisico, 
                    $saldo->saldoquantidade_fisico, 
                    $saldo->saldovalor_fisico, 
                    $saldo->customedio_fisico, 
                    $saldo->ultimaconferencia_fisico, 
                    $saldo->codestoquesaldo_fiscal, 
                    $saldo->saldoquantidade_fiscal, 
                    $saldo->saldovalor_fiscal, 
                    $saldo->customedio_fiscal, 
                    $saldo->ultimaconferencia_fiscal,
                    $somentequantidade
                    );
            ?>
        @endforeach

        <?php
    }

    ?>
    <tfoot>
        <tr>
            <th class='col-md-1'>
                Total
            </th>
            <?php
            $customedio_fisico = null;
            $saldoquantidade_fisico = array_sum(array_column($totais, 'saldoquantidade_fisico'));
            $saldovalor_fisico = array_sum(array_column($totais, 'saldovalor_fisico'));
            if ($saldovalor_fisico != 0)
                $customedio_fisico = $saldovalor_fisico / $saldoquantidade_fisico;
            ?>
            <th class='col-md-1 text-right'>
                {{ formataNumero($saldoquantidade_fisico, 3) }}
            </th>
            @if (!$somentequantidade)
                <th class='col-md-1 text-right'>
                    {{ formataNumero($saldovalor_fisico, 2) }}
                </th>
                <th class='col-md-1 text-right'>
                    {{ formataNumero($customedio_fisico, 6) }}
                </th>
            @endif
            <?php
            $customedio_fiscal = null;
            $saldoquantidade_fiscal = array_sum(array_column($totais, 'saldoquantidade_fiscal'));
            $saldovalor_fiscal = array_sum(array_column($totais, 'saldovalor_fiscal'));
            if ($saldovalor_fiscal != 0)
                $customedio_fiscal = $saldovalor_fiscal / $saldoquantidade_fiscal;
            ?>
            <th class='col-md-1 text-right'>
                {{ formataNumero($saldoquantidade_fiscal, 3) }}
            </th>
            @if (!$somentequantidade)
                <th class='col-md-1 text-right'>
                    {{ formataNumero($saldovalor_fiscal, 2) }}
                </th>
                <th class='col-md-1 text-right'>
                    {{ formataNumero($customedio_fiscal, 6) }}
                </th>
            @endif
        </tr>                
    </tfoot>
</table>
</div>