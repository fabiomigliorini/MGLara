<?php

use \Carbon\Carbon;

?>
<div class="row">
    <div class="col-xs-3">
        @if ($ultimaCompra == null)
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-12">
                            Sem registro de compra!
                        </div>
                    </div>
                </div>
            </div>
        @else 
            <div class="panel panel-success">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-12">
                            Última compra em
                            {{ Carbon::parse($ultimaCompra)->diffForHumans() }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <div class="col-xs-3">
        @if ($ultimaVenda == null)
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-12">
                            Sem registro de venda!
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="panel panel-success">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-12">
                            Última venda em
                            {{ Carbon::parse($ultimaVenda)->diffForHumans() }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <div class="col-xs-6">
        <div id="div-revisao">
            <div class="panel <?php echo (empty($model->revisao))?'panel-danger':'panel-success' ?>">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-12">
                            @if (empty($model->revisao))
                                Cadastro do produto nunca revisado!
                            @else
                                Ultima Revisão do cadastro <abbr title='{{$model->revisao->format("d/m/Y H:i:s")}}'>{{$model->revisao->diffForHumans()}}</abbr>!
                                <button type="button" class="btn btn-sm btn-danger pull-right" style="margin-right: 5px" onclick="btnDesmarcarRevisaoClick()">X</button>
                                &nbsp
                            @endif
                            <button type="button" class="btn btn-sm btn-success pull-right" style="margin-right: 5px" onclick="btnRevisarClick()">Revisado</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


                
