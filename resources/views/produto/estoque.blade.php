<?php
    $arr_saldos = [];
    $arr_totais = [false => 0, true => 0];
    foreach ($model->EstoqueLocalProdutoS as $es)
    {
        $arr_totais[$es->EstoqueSaldoS->first()->fiscal] += $es->EstoqueSaldoS->first()->saldoquantidade;
        $arr_saldos[] = $es;
    }
?>
<div class='panel panel-info'>
    <div class="panel-heading">
        <div class="row item">
            <div class="col-md-6">Estoque</div>
            <div class="col-md-2 text-right">Local</div>
            <div class="col-md-2 text-right">Físico</div>
            <div class="col-md-2 text-right">Fiscal</div>
        </div>
    </div>            
    <ul class="list-group bg-infoo">
        @foreach($arr_saldos as $saldo)
        <li class="list-group-item">
            <div class="row item">            
                <div class="col-md-6">
                    {{ $saldo->EstoqueLocal->estoquelocal }}
                </div>
                <div class="col-md-2 text-right">
                    {{ formataLocalEstoque($saldo->corredor, $saldo->prateleira, $saldo->coluna, $saldo->bloco) }}
                </div>
                <div class="col-md-2 text-right">
                    <a href='{{ url("estoque-saldo/{$saldo->EstoqueSaldoS->first()->codestoquesaldo}") }}'>
                        {{ ($saldo->EstoqueSaldoS->first()->fiscal) ? '' : formataNumero($saldo->EstoqueSaldoS->first()->saldoquantidade, 0) }}
                    </a>
                </div>
                <div class="col-md-2 text-right">
                    <a href='{{ url("estoque-saldo/{$saldo->EstoqueSaldoS->first()->codestoquesaldo}") }}'>
                        {{ ($saldo->EstoqueSaldoS->first()->fiscal) ? formataNumero($saldo->EstoqueSaldoS->first()->saldoquantidade, 0) : '' }}
                    </a>
                </div>
            </div>            
        </li>
        @endforeach    
        <li class="list-group-item">
            <div class="row item">            
                <div class="col-md-6">
                    <strong>Total</strong>
                </div>
                <div class="col-md-2 text-right"></div>
                <div class="col-md-2 text-right">
                    <strong>{{ formataNumero($arr_totais[false], 0) }}</strong>
                </div>
                <div class="col-md-2 text-right">
                    <strong>{{ formataNumero($arr_totais[true], 0) }}</strong>
                </div>
            </div>            
        </li>
    </ul>
</div>