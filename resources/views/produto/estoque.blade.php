<?php
    $arr_saldos = [];
    $arr_totais = [false => 0, true => 0];
/*
    foreach ($model->EstoqueLocalProdutoVariacaoS as $es)
    {
        $arr_totais[$es->EstoqueSaldoS->first()->fiscal] += $es->EstoqueSaldoS->first()->saldoquantidade;
        $arr_saldos[] = $es;
    }
 * 
 */
?>
<div class='panel panel-info'>
    <div class="panel-heading">
        <div class="row item">
            <div class="col-md-6">Local</div>
            <div class="col-md-3 text-right">Físico</div>
            <div class="col-md-3 text-right">Fiscal</div>
        </div>
    </div>            
    <ul class="list-group bg-infoo">
        @foreach($model->EstoqueLocalProdutoVariacaos as $elp)
        <li class="list-group-item">
            <div class="row item">            
                <div class="col-md-3">
                    {{ $elp->EstoqueLocal->estoquelocal }}
                </div>
                <div class="col-md-3">
                    {{ formataLocalEstoque($elp->corredor, $elp->prateleira, $elp->coluna, $elp->bloco) }}
                </div>
                <?php
                $saldo = $elp->EstoqueSaldoS()->where('fiscal', false)->first();
                ?>
                <div class="col-md-3 text-right">
                    @if ($saldo != NULL)
                    <?php $arr_totais[$saldo->fiscal] += $saldo->saldoquantidade; ?>
                    <a href='{{ url("estoque-saldo/{$saldo->codestoquesaldo}") }}'>
                        {{ formataNumero($saldo->saldoquantidade, 0) }}
                    </a>
                    @endif
                </div>                
                <?php
                $saldo = $elp->EstoqueSaldoS()->where('fiscal', true)->first();
                ?>
                <div class="col-md-3 text-right">
                    @if ($saldo != NULL)
                    <?php $arr_totais[$saldo->fiscal] += $saldo->saldoquantidade; ?>
                    <a href='{{ url("estoque-saldo/{$saldo->codestoquesaldo}") }}'>
                        {{ formataNumero($saldo->saldoquantidade, 0) }}
                    </a>
                    @endif
                </div>                
            </div>
        </li>
        @endforeach
        <li class="list-group-item">
            <div class="row item">            
                <div class="col-md-6">
                    <strong>Total</strong>
                </div>
                <div class="col-md-3 text-right">
                    <strong>{{ formataNumero($arr_totais[false], 0) }}</strong>
                </div>
                <div class="col-md-3 text-right">
                    <strong>{{ formataNumero($arr_totais[true], 0) }}</strong>
                </div>
            </div>            
        </li>
    </ul>
</div>