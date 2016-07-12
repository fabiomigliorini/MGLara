<?php

if (!isset($codestoquelocal_destaque))
    $codestoquelocal_destaque = null;

if (!isset($codestoquesaldo_destaque))
    $codestoquesaldo_destaque = null;

use Carbon\Carbon;
use MGLara\Models\EstoqueLocal;

function somaArray($array, $coluna)
{
    return array_sum(array_column(json_decode(json_encode($array), true), $coluna));
}

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
    
    if (!empty($ultimaconferencia_fisico))
    {
        $ultimaconferencia_fisico = new Carbon($ultimaconferencia_fisico);
        $dias_fisico = $ultimaconferencia_fisico->diffInDays();

        if ($dias_fisico > 30)
            $label_fisico = 'label-danger';
        elseif ($dias_fisico > 15)
            $label_fisico = 'label-warning';
        else
            $label_fisico = 'label-success';
    }
    
    if (!empty($ultimaconferencia_fiscal))
    {
        $ultimaconferencia_fiscal = new Carbon($ultimaconferencia_fiscal);
        $dias_fiscal = $ultimaconferencia_fiscal->diffInDays();

        if ($dias_fiscal > 30)
            $label_fiscal = 'label-danger';
        elseif ($dias_fiscal > 15)
            $label_fiscal = 'label-warning';
        else
            $label_fiscal = 'label-success';
    }
    
    if (empty($codprodutovariacao))
        $style = 'border-top: 1px dashed grey';
    else
        $style = '';
    ?>
    <div class="col-md-12" style="{{ $style }}">
        @if (empty($codprodutovariacao))
            <div class="col-md-4">
                <a href="#detalhesCodEstoqueLocal{{$codestoquelocal}}" data-toggle="collapse" >
                    {{ $estoquelocal }}
                </a>
            </div>
        @else
            <div class="col-md-4">
                @if (!empty($variacao))
                    {{ $variacao }}
                @else
                    <i class='text-muted'>{ Sem Variação }</i>
                @endif
            </div>
        @endif

        <!-- FISICO -->
        <?php
        $class = ($codestoquesaldo_fisico == $codestoquesaldo_destaque && !empty($codestoquesaldo_destaque))?'bg-info':'';
        $url = empty($codestoquesaldo_fisico)?"#detalhesCodEstoqueLocal{$codestoquelocal}":url("estoque-saldo/$codestoquesaldo_fisico");
        $toggle = empty($codestoquesaldo_fisico)?'data-toggle="collapse"':'';
        ?>
        <div class='text-right {{ $class }} col-md-2'>
            <a href='{{ $url }}' {!! $toggle !!}>
                {{ formataNumero($saldoquantidade_fisico, 3) }}&nbsp;
            </a>
            @if (!empty($codprodutovariacao))
                <a class="label pull-left {{ $label_fisico }}">
                    &nbsp;
                </a>
            @endif
        </div>
        @if (!$somentequantidade)
            <div class='text-right {{ $class }} col-md-1'>
                <a href='{{ $url }}' {!! $toggle !!}>
                    {{ formataNumero($saldovalor_fisico, 2) }}&nbsp;
                </a>
            </div>
            <div class='text-right {{ $class }} col-md-1'>
                {{ formataNumero($customedio_fisico, 6) }}&nbsp;
            </div>
        @endif

        <!-- FISCAL -->
        <?php
        $class = ($codestoquesaldo_fiscal == $codestoquesaldo_destaque && !empty($codestoquesaldo_destaque))?'bg-info':'';
        $url = empty($codestoquesaldo_fiscal)?"#detalhesCodEstoqueLocal{$codestoquelocal}":url("estoque-saldo/$codestoquesaldo_fiscal");
        $toggle = empty($codestoquesaldo_fiscal)?'data-toggle="collapse"':'';
        ?>
        <div class='text-right {{ $class }} col-md-2'>
            <a href='{{ $url }}' {!! $toggle !!}>
                {{ formataNumero($saldoquantidade_fiscal, 3) }}&nbsp;
            </a>
            @if (!empty($codprodutovariacao))
                <a class="label pull-left {{ $label_fiscal }}">
                    &nbsp;
                </a>
            @endif
        </div>
        @if (!$somentequantidade)
            <div class='text-right {{ $class }} col-md-1'>
                <a href='{{ $url }}' {!! $toggle !!}>
                    {{ formataNumero($saldovalor_fiscal, 2) }}&nbsp;
                </a>
            </div>
            <div class='text-right {{ $class }} col-md-1'>
                {{ formataNumero($customedio_fiscal, 6) }}&nbsp;
            </div>
        @endif
    </div>
    <?php
}


?>
<div class='table-condensed'>
    <div class="row">
    <div class="col-md-12">
        @if (!$somentequantidade)
            <div class="col-md-12">
                <div class='col-md-4'>
                </div>
                <div class='text-center col-md-4'>
                    Físico
                </div>
                <div class='text-center col-md-4'>
                    Fiscal
                </div>
            </div>
        @endif
        <div>
            <div class='col-md-4 text-right'>
                Local
            </div>
            <div class='col-md-2 text-right'>
                @if (!$somentequantidade)
                    Saldo
                @else
                    Físico
                @endif
            </div>
            @if (!$somentequantidade)
                <div class='col-md-1 text-right'>
                    Valor
                </div>
                <div class='col-md-1 text-right'>
                    Custo
                </div>
            @endif
            <div class='col-md-2 text-right'>
                @if (!$somentequantidade)
                    Saldo
                @else
                    Fiscal
                @endif
            </div>
            @if (!$somentequantidade)
                <div class='col-md-1 text-right'>
                    Valor
                </div>
                <div class='col-md-1 text-right'>
                    Custo
                </div>
            @endif
        </div>
    </div>
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
        
        if (somaArray($saldos, 'codestoquesaldo_fisico') == 0)
        {
            $totais[$el->codestoquelocal]['saldoquantidade_fisico'] = null;
            $totais[$el->codestoquelocal]['saldovalor_fisico'] = null;
            $totais[$el->codestoquelocal]['ultimaconferencia_fisico'] = null;
        }
        else
        {
            $totais[$el->codestoquelocal]['saldoquantidade_fisico'] = somaArray($saldos, 'saldoquantidade_fisico');
            $totais[$el->codestoquelocal]['saldovalor_fisico'] = somaArray($saldos, 'saldovalor_fisico');
            $totais[$el->codestoquelocal]['ultimaconferencia_fisico'] = @max(array_column($saldos, 'ultimaconferencia_fisico'));
            if ($totais[$el->codestoquelocal]['saldoquantidade_fisico'] != 0)
                $customedio_fisico = $totais[$el->codestoquelocal]['saldovalor_fisico'] / $totais[$el->codestoquelocal]['saldoquantidade_fisico'];
            else 
                $customedio_fisico = null;
        }
        
        if (somaArray($saldos, 'codestoquesaldo_fiscal') == 0)
        {
            $totais[$el->codestoquelocal]['saldoquantidade_fiscal'] = null;
            $totais[$el->codestoquelocal]['saldovalor_fiscal'] = null;
            $totais[$el->codestoquelocal]['ultimaconferencia_fiscal'] = null;
        }
        else
        {
            $totais[$el->codestoquelocal]['saldoquantidade_fiscal'] = somaArray($saldos, 'saldoquantidade_fiscal');
            $totais[$el->codestoquelocal]['saldovalor_fiscal'] = somaArray($saldos, 'saldovalor_fiscal');
            $totais[$el->codestoquelocal]['ultimaconferencia_fiscal'] = @max(array_column($saldos, 'ultimaconferencia_fiscal'));
            if ($totais[$el->codestoquelocal]['saldoquantidade_fiscal'] != 0)
                $customedio_fiscal = $totais[$el->codestoquelocal]['saldovalor_fiscal'] / $totais[$el->codestoquelocal]['saldoquantidade_fiscal'];
            else
                $customedio_fiscal = null;
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
        
        if ($el->codestoquelocal == $codestoquelocal_destaque)
            $class = 'in';
        else
            $class = '';
        ?>
        <div class="panel-collapse collapse {{ $class }}" id="detalhesCodEstoqueLocal{{ $el->codestoquelocal }}">
        @foreach ($saldos as $saldo)
                <?php
                linha(
                        $el->codestoquelocal, 
                        $el->estoquelocal, 
                        $saldo->codprodutovariacao, 
                        $saldo->variacao, 
                        $codestoquesaldo_destaque,
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
        </div>
        <?php
    }

    ?>
    <div class="col-md-12" style="border-top: double grey">
        <div>
            <div class='col-md-4'>
                Total
            </div>
            <?php
            $customedio_fisico = null;
            $saldoquantidade_fisico = somaArray($totais, 'saldoquantidade_fisico');
            $saldovalor_fisico = somaArray($totais, 'saldovalor_fisico');
            if ($saldoquantidade_fisico != 0)
                $customedio_fisico = $saldovalor_fisico / $saldoquantidade_fisico;
            else 
                $customedio_fisico = null;
            ?>
            <div class='col-md-2 text-right'>
                {{ formataNumero($saldoquantidade_fisico, 3) }}
            </div>
            @if (!$somentequantidade)
                <div class='col-md-1 text-right'>
                    {{ formataNumero($saldovalor_fisico, 2) }}
                </div>
                <div class='col-md-1 text-right'>
                    {{ formataNumero($customedio_fisico, 6) }}
                </div>
            @endif
            <?php
            $customedio_fiscal = null;
            $saldoquantidade_fiscal = somaArray($totais, 'saldoquantidade_fiscal');
            $saldovalor_fiscal = somaArray($totais, 'saldovalor_fiscal');
            if ($saldoquantidade_fiscal != 0)
                $customedio_fiscal = $saldovalor_fiscal / $saldoquantidade_fiscal;
            else
                $customedio_fiscal = null;
            ?>
            <div class='col-md-2 text-right'>
                {{ formataNumero($saldoquantidade_fiscal, 3) }}
            </div>
            @if (!$somentequantidade)
                <div class='col-md-1 text-right'>
                    {{ formataNumero($saldovalor_fiscal, 2) }}
                </div>
                <div class='col-md-1 text-right'>
                    {{ formataNumero($customedio_fiscal, 6) }}
                </div>
            @endif
        </div>                
    </div>
</div>
</div>
