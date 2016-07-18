<?php

function decideIconeUltimaConferencia($data)
{
    if ($data == null)
        return 'glyphicon-remove-sign text-muted';
    
    $dias = $data->diffInDays();
    
    if ($dias > 30)
        return 'glyphicon-question-sign text-danger';
  
    if ($dias > 15)
        return 'glyphicon-question-sign text-warning';
    
    return 'glyphicon-ok-sign text-success';
}

function divSaldo($arr) {
    ?>
    @if (!empty($arr['codestoquesaldo']))
        <a href="{{ url("estoque-saldo/{$arr['codestoquesaldo']}") }}">
    @endif
    {{ formataNumero($arr['saldoquantidade'], 0) }}
    @if (!empty($arr['codestoquesaldo']))
        </a>
    @endif
    <span class='glyphicon {{ decideIconeUltimaConferencia($arr['ultimaconferencia']) }}'></span>
    <?php
}

function divDescricao($arr) {
    ?>
    <div class="col-md-4">
        @if (is_array($arr['variacao'] ))
            <b>
            @if (!empty($arr['estoquelocal'] ))
                {{ $arr['estoquelocal'] }}
            @else
                Total
            @endif
            </b>
        @elseif (!empty($arr['variacao'] ))
            {{ $arr['variacao'] }}
        @else
            <i class='text-muted'>{ Sem Variação }</i>
        @endif
    </div>
    <div class="col-md-4 text-muted">
        @if (isset($arr['corredor']))
            {{ formataLocalEstoque($arr['corredor'], $arr['prateleira'], $arr['coluna'], $arr['bloco']) }}
        @endif
        <div class='pull-right'>
            @if (!empty($arr['estoqueminimo']))
                @if ($arr['estoqueminimo'] > $arr['fisico']['saldoquantidade'])
                    <b class='text-danger'>
                @endif
                {{ formataNumero($arr['estoqueminimo'], 0) }} <span class='glyphicon glyphicon-arrow-down'></span>
                @if ($arr['estoqueminimo'] > $arr['fisico']['saldoquantidade'])
                    </b>
                @endif
            @endif
            @if (!empty($arr['estoquemaximo']))
                @if ($arr['estoquemaximo'] < $arr['fisico']['saldoquantidade'])
                    <b class='text-danger'>
                @endif
                {{ formataNumero($arr['estoquemaximo'], 0) }} <span class='glyphicon glyphicon-arrow-up'></span>
                @if ($arr['estoquemaximo'] < $arr['fisico']['saldoquantidade'])
                    </b>
                @endif
            @endif
        </div>            
    </div>
    <?php
}

?>

<div id='div-estoque'>
    <div class="panel-group">

        <div class="panel panel-default panel-condensed">

            <!-- Titulo -->
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-4">
                        <b>Local</b>
                    </div>
                    <div class="col-md-4">
                        <b>Corredor</b>
                        <b class='pull-right'>
                            Min <span class='glyphicon glyphicon-arrow-down'></span> 
                            Max <span class='glyphicon glyphicon-arrow-up'></span> 
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

        @foreach($estoque['local'] as $codestoquelocal => $arrLocal)
            <div class="panel panel-default panel-condensed">

                <!-- Total Local -->
                <div class="{{ ($codestoquelocal == 'total')?'panel-footer':'panel-body' }}">
                    <a data-toggle="collapse" href="#collapseEstoqueLocal{{ $codestoquelocal }}">
                        <div class="row">
                            {{ divDescricao($arrLocal) }}
                            <div class="col-md-2 text-right">
                                {{ divSaldo($arrLocal['fisico']) }}
                            </div>
                            <div class="col-md-2 text-right">
                                {{ divSaldo($arrLocal['fiscal']) }}
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Variacoes do Produto -->
                <div id="collapseEstoqueLocal{{ $codestoquelocal }}" class="panel-collapse collapse">
                    <ul class="list-group list-group-condensed list-group-striped list-group-hover list-group-condensed">

                        @foreach ($arrLocal['variacao'] as $arrVar)
                            <li class="list-group-item">
                                <div class="row">
                                    {{ divDescricao($arrVar) }}
                                    <div class="col-md-2 text-right">
                                        {{ divSaldo($arrVar['fisico']) }}
                                    </div>
                                    <div class="col-md-2 text-right">
                                        {{ divSaldo($arrVar['fiscal']) }}
                                    </div>
                                </div>              
                            </li>
                        @endforeach
                    </ul>
                </div>

            </div>
        @endforeach

    </div>
</div>