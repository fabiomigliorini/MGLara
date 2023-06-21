@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
{!! 
    titulo(
        $model->codproduto,
        [
            url("produto") => 'Produtos',
            url("produto/$model->codproduto") => $model->produto,
            'Editar Mínima e Máxima',
        ],
        $model->inativo,
        6
    ) 
!!}     
</ol>
<br>
<div class="col-md-12">
    <form id="form-minimo-maximo" method="POST" class="form-horizontal">
        {!! csrf_field() !!}
        <table class="table table-striped table-bordered table-hover table-condensed">
            <thead>
                <tr>
                    <th>
                        Variação
                    </th>
                    @foreach ($colunas as $coluna)
                        <th class="text-center" colspan="2">
                            {{$coluna->sigla}}
                        </th>
                    @endforeach
                    <th class="text-center" colspan="2">
                        Total
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($linhas as $linha)
                    <tr>
                        <th rowspan="2">
                            {{$linha->variacao}}
                        </th>
                        @foreach ($colunas as $coluna)
                            <?php
                                $valor = $valores->first(function ($key, $value) use ($linha, $coluna) {
                                    return 
                                        ($value->codestoquelocal == $coluna->codestoquelocal)
                                        && ($value->codprodutovariacao == $linha->codprodutovariacao);
                                });

                                $estoqueminimo = null;
                                $estoquemaximo = null;
                                if ($valor) {
                                    $estoqueminimo = $valor->estoqueminimo;
                                    $estoquemaximo = $valor->estoquemaximo;
                                }
                            ?>
                            <td class="input-group-sm">
                                <input 
                                    type="number" 
                                    name="estoqueminimo[{{$coluna->codestoquelocal}}][{{$linha->codprodutovariacao}}]" 
                                    id="estoqueminimo_{{$coluna->codestoquelocal}}_{{$linha->codprodutovariacao}}" 
                                    value="{{$estoqueminimo}}" 
                                    class="form-control text-center" 
                                    step="1" 
                                    min="0"
                                    max=""
                                    onchange="atualizaMinMaxInput({{$coluna->codestoquelocal}}, {{$linha->codprodutovariacao}}, 'minimo'); atualizaTotais()" 
                                    />                                  
                            </td>
                            <td class="input-group-sm">
                                <input 
                                    type="number" 
                                    name="estoquemaximo[{{$coluna->codestoquelocal}}][{{$linha->codprodutovariacao}}]" 
                                    id="estoquemaximo_{{$coluna->codestoquelocal}}_{{$linha->codprodutovariacao}}" 
                                    value="{{$estoquemaximo}}" 
                                    class="form-control text-center" 
                                    step="1" 
                                    min="0"
                                    max=""
                                    onchange="atualizaMinMaxInput({{$coluna->codestoquelocal}}, {{$linha->codprodutovariacao}}, 'maximo'); atualizaTotais()" 
                                    />
                            </td>
                        @endforeach 
                        <th class="text-center">
                            <span id="minimovariacao_{{$linha->codprodutovariacao}}"></span>
                        </th>
                        <th class="text-center">
                            <span id="maximovariacao_{{$linha->codprodutovariacao}}"></span>
                        </th>                                        
                    </tr>
                    <tr>
                        @foreach ($colunas as $coluna)
                            <?php
                                $valor = $valores->first(function ($key, $value) use ($linha, $coluna) {
                                    return 
                                        ($value->codestoquelocal == $coluna->codestoquelocal)
                                        && ($value->codprodutovariacao == $linha->codprodutovariacao);
                                });

                                $saldoquantidade = null;
                                $vendaano = null;
                                if ($valor) {
                                    $saldoquantidade = $valor->saldoquantidade;
                                    $vendaano = $valor->vendaano;
                                }
                            ?>
                            <td colspan="2" id="col_{{$coluna->codestoquelocal}}_{{$linha->codprodutovariacao}}" class="small text-center">
                                <input 
                                    type="hidden" 
                                    id="saldoquantidade_{{$coluna->codestoquelocal}}_{{$linha->codprodutovariacao}}" 
                                    value="{{$saldoquantidade}}" 
                                    />   
                                <input 
                                    type="hidden" 
                                    id="vendaano_{{$coluna->codestoquelocal}}_{{$linha->codprodutovariacao}}" 
                                    value="{{$vendaano}}" 
                                    />            
                                <span class="text-muted" id="label_{{$coluna->codestoquelocal}}_{{$linha->codprodutovariacao}}"></span>
                                <!-- <div class="progress" style="margin-bottom: 0px">
                                    <div class="progress-bar progress-bar-success  " style="width: 0%" id="pb_success_{{$coluna->codestoquelocal}}_{{$linha->codprodutovariacao}}">
                                        <span id="pb_label_success_{{$coluna->codestoquelocal}}_{{$linha->codprodutovariacao}}"></span>
                                    </div>                                    
                                    <div class="progress-bar progress-bar-danger " style="width: 0%" id="pb_danger_{{$coluna->codestoquelocal}}_{{$linha->codprodutovariacao}}">
                                        <span id="pb_label_danger_{{$coluna->codestoquelocal}}_{{$linha->codprodutovariacao}}"></span>
                                    </div>
                                    <div class="progress-bar progress-bar-warning " style="width: 0%" id="pb_warning_{{$coluna->codestoquelocal}}_{{$linha->codprodutovariacao}}">
                                        <span id="pb_label_warning_{{$coluna->codestoquelocal}}_{{$linha->codprodutovariacao}}"></span>
                                    </div>
                                </div> -->
                            </td>
                        @endforeach
                        <td colspan="2">
                            <div class="progress" style="margin-bottom: 0px">
                                <div class="progress-bar progress-bar-success" style="width: 0%" id="pb_success_{{$linha->codprodutovariacao}}">
                                    <span id="pb_label_success_{{$linha->codprodutovariacao}}"></span>
                                </div>                                    
                                <div class="progress-bar progress-bar-danger" style="width: 0%" id="pb_danger_{{$linha->codprodutovariacao}}">
                                    <span id="pb_label_danger_{{$linha->codprodutovariacao}}"></span>
                                </div>
                                <div class="progress-bar progress-bar-warning" style="width: 0%" id="pb_warning_{{$linha->codprodutovariacao}}">
                                    <span id="pb_label_warning_{{$linha->codprodutovariacao}}"></span>
                                </div>
                            </div>
                        </td>
                    </tr>

                @endforeach

                <tr>
                    <th rowspan="2">
                        Totais
                    </th>
                    @foreach ($colunas as $coluna)
                        <th rowspan="2" class="text-center">
                            <span id="minimoestoquelocal_{{$coluna->codestoquelocal}}"></span>
                        </th>
                        <th rowspan="2" class="text-center">
                            <span id="maximoestoquelocal_{{$coluna->codestoquelocal}}"></span>
                        </th>
                    @endforeach 
                    <th class="text-center">
                        <span id="minimo"></span>
                    </th>
                    <th class="text-center">
                        <span id="maximo"></span>
                    </th>                                               
                </tr>
                <tr>
                    <th class="text-center" colspan="2">
                        <div class="progress" style="margin-bottom: 0px">
                            <div class="progress-bar progress-bar-success  " style="width: 0%" id="pb_success_total">
                                <span id="pb_label_success_total"></span>
                            </div>                                    
                            <div class="progress-bar progress-bar-danger " style="width: 0%" id="pb_danger_total">
                                <span id="pb_label_danger_total"></span>
                            </div>
                            <div class="progress-bar progress-bar-warning " style="width: 0%" id="pb_warning_total">
                                <span id="pb_label_warning_total"></span>
                            </div>
                        </div>
                    </th>                    
                </tr>     
            </tbody>
        </table>
        <?php
        $embalagens[0] = $model->UnidadeMedida->sigla;
        $arrEmb[0] = 1;

        foreach ($model->ProdutoEmbalagemS()->orderBy('quantidade')->get() as $pe){
            $embalagens[$pe->codprodutoembalagem] = $pe->descricao;
            $arrEmb[$pe->codprodutoembalagem] = floatval($pe->quantidade);
        } 

        $optionsUnidadeMedida = [];
        ?>
        <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2" style="margin-right: 10px">
                <div class="form-group">
                    <label for="codprodutoembalagemcompra">Comprado em</label>
                    {!! Form::select('codprodutoembalagemcompra', $embalagens, $model->codprodutoembalagemcompra, ['class'=> 'form-control', 'id' => 'codprodutoembalagem']) !!}
                </div>
                <div class="form-group">
                    <label for="codprodutoembalagemtransferencia">Transferência em</label>
                    {!! Form::select('codprodutoembalagemtransferencia', $embalagens, $model->codprodutoembalagemtransferencia, ['class'=> 'form-control', 'id' => 'codprodutoembalagemtransferencia', 'onchange' => 'atualizaStepMax()']) !!}
                </div>
                <div class="form-group">
                    <button class="btn btn-primary" type="submit">Salvar</button>
                    <button class="btn btn-danger" type="button" onclick="limparForm()">Desfazer</button>
                </div>
            </div>

            <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                <div class="form-group">
                    <label for="diasloja">Sugerir nas lojas</label>
                    <div class="input-group">
                        <input 
                            type="number" 
                            id="diasloja" 
                            name="diasloja"
                            value="45" 
                            class="form-control text-right"
                            step="1"
                            min="1"
                            max="365"
                            />   
                        <div class="input-group-addon">Dias</div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="diasdeposito">Sugerir no Depósito</label>
                    <div class="input-group">
                        <input 
                            type="number" 
                            id="diasdeposito" 
                            name="diasdeposito"
                            value="120" 
                            class="form-control text-right"
                            step="1"
                            min="1"
                            max="365"
                            />   
                        <div class="input-group-addon">Dias</div>
                    </div>
                </div>                
                <div class="form-group">
                    <button class="btn btn-primary" type="button" onclick="SugerirMinimoMaximo()">Sugerir</button>
                </div>
            </div>
        </div>
    </form>
    <br>
</div>



@section('inscript')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript"> 

$(document).ready(function() {
    $('form').on("submit", function(e) {
        var currentForm = this;
        e.preventDefault();
        bootbox.confirm("Tem certeza que deseja salvar?", function(result) {
            if (result) {
                currentForm.submit();
            }
        });
    });
    atualizaMinMaxTodosInputs();
    atualizaTotais();
    atualizaStepMax();
});


const codestoquelocals = <?php echo $colunas->pluck('codestoquelocal')->toJson() ?>; 
const codprodutovariacaos = <?php echo $linhas->pluck('codprodutovariacao')->toJson() ?>; 
const embalagens = <?php echo json_encode($arrEmb) ?>; 

function limparForm()
{
    $('#form-minimo-maximo').each (function(){
        this.reset();
        atualizaMinMaxTodosInputs();
        atualizaTotais();
    });
}

function atualizaMinMaxTodosInputs()
{
    codestoquelocals.forEach(function(codestoquelocal) {
        codprodutovariacaos.forEach(function(codprodutovariacao) {
            atualizaMinMaxInput(codestoquelocal, codprodutovariacao);
        });
    });
}

function atualizaStepMax()
{
    var cod = $("#codprodutoembalagemtransferencia").val();
    var step = embalagens[cod];
    codestoquelocals.forEach(function(codestoquelocal) {
        codprodutovariacaos.forEach(function(codprodutovariacao) {
            $('#estoquemaximo_' + codestoquelocal + '_' + codprodutovariacao).attr('step', step);
        });
    });
}

function atualizaMinMaxInput(codestoquelocal, codprodutovariacao, campo)
{
    var minimo = parseFloat($('#estoqueminimo_' + codestoquelocal + '_' + codprodutovariacao).val());
    var maximo = parseFloat($('#estoquemaximo_' + codestoquelocal + '_' + codprodutovariacao).val());
    if (campo == 'minimo' && maximo < minimo) {
        $('#estoquemaximo_' + codestoquelocal + '_' + codprodutovariacao).val(minimo);   
        maximo = minimo;
    }
    if (campo == 'maximo' && minimo > maximo) {
        $('#estoqueminimo_' + codestoquelocal + '_' + codprodutovariacao).val(maximo);  
        minimo = maximo;
    }
    $('#estoqueminimo_' + codestoquelocal + '_' + codprodutovariacao).attr('max', maximo);
    $('#estoquemaximo_' + codestoquelocal + '_' + codprodutovariacao).attr('min', minimo);
}

function atualizaTotais()
{
    var minimovariacao = 0;
    var maximovariacao = 0;
    var vendaanovariacao = 0;
    var saldovariacao = 0;
    
    var minimoestoquelocal = 0;
    var maximoestoquelocal = 0;

    var minimo = 0;
    var maximo = 0;
    var vendaano = 0;
    var saldo = 0;

    codprodutovariacaos.forEach(function(codprodutovariacao) {
        minimovariacao = 0;
        maximovariacao = 0;
        vendaanovariacao = 0;
        estoquevariacao = 0;
            
        $('#pb_success_' + codprodutovariacao).css('width', '0%');
        $('#pb_danger_' + codprodutovariacao).css('width', '0%');
        $('#pb_warning_' + codprodutovariacao).css('width', '0%');
        $('#pb_label_success_' + codprodutovariacao).html('');
        $('#pb_label_danger_' + codprodutovariacao).html('');
        $('#pb_label_warning_' + codprodutovariacao).html('');        

        codestoquelocals.forEach(function(codestoquelocal) {
            var min = parseFloat($('#estoqueminimo_' + codestoquelocal + '_' + codprodutovariacao).val());
            if (min) {
                minimovariacao += min;
                minimo += min;
            }
            var max = parseFloat($('#estoquemaximo_' + codestoquelocal + '_' + codprodutovariacao).val());
            if (max) {
                maximovariacao += max;
                maximo += max;
            } else {
                max = 0;
            }
            var sld = parseFloat($('#saldoquantidade_' + codestoquelocal + '_' + codprodutovariacao).val());
            if (sld) {
                saldovariacao += sld;
                saldo += sld;
            } else {
                sld = 0;
            }    
            var vda = parseInt($('#vendaano_' + codestoquelocal + '_' + codprodutovariacao).val());
            if (vda) {
                vendaanovariacao += vda;
                vendaano += vda;
            } else {
                vda = 0;
            }
            $('#pb_success_' + codestoquelocal + '_' + codprodutovariacao).css('width', '0%');
            $('#pb_danger_' + codestoquelocal + '_' + codprodutovariacao).css('width', '0%');
            $('#pb_warning_' + codestoquelocal + '_' + codprodutovariacao).css('width', '0%');
            $('#pb_label_success_' + codestoquelocal + '_' + codprodutovariacao).html('');
            $('#pb_label_danger_' + codestoquelocal + '_' + codprodutovariacao).html('');
            $('#pb_label_warning_' + codestoquelocal + '_' + codprodutovariacao).html('');
        
            // CALCULA % , SALDO E SALDO ANO 
            var perc = 0;
            if (max > 0) {
                perc = parseInt((sld/max) * 100);
            } 
            var html = '';
            if (vda) {
                var dias = Math.round(parseFloat(min / (vda / 365)));
                if (dias) {
                    html += dias + ' / ';
                }
                dias = Math.round(parseFloat(max / (vda / 365)));
                if (dias) {
                    html += dias + ' dias ';
                }
                html += '  (' + vda + ' ano)';
            }
            html += '<br> Saldo ' + sld + ' (' + perc + '%)';
            
            $('#label_' + codestoquelocal + '_' + codprodutovariacao).html(html);
            $('#col_' + codestoquelocal + '_' + codprodutovariacao).removeClass('success');
            $('#col_' + codestoquelocal + '_' + codprodutovariacao).removeClass('warning');
            $('#col_' + codestoquelocal + '_' + codprodutovariacao).removeClass('danger');
            if (perc > 100) {
                $('#col_' + codestoquelocal + '_' + codprodutovariacao).addClass('warning');
                $('#pb_warning_' + codestoquelocal + '_' + codprodutovariacao).css('width', '100%');
                $('#pb_label_warning_' + codestoquelocal + '_' + codprodutovariacao).html(html);
            } else {
                if (sld > min) {
                    $('#col_' + codestoquelocal + '_' + codprodutovariacao).addClass('success');
                    $('#pb_success_' + codestoquelocal + '_' + codprodutovariacao).css('width', perc + '%');
                    $('#pb_label_success_' + codestoquelocal + '_' + codprodutovariacao).html(html);
                } else {
                    $('#col_' + codestoquelocal + '_' + codprodutovariacao).addClass('danger');
                    $('#pb_success_' + codestoquelocal + '_' + codprodutovariacao).css('width', perc + '%');
                    $('#pb_danger_' + codestoquelocal + '_' + codprodutovariacao).css('width', (100 - perc) + '%');
                    $('#pb_label_danger_' + codestoquelocal + '_' + codprodutovariacao).html(html);
                }
            }

        });
        
        $('#minimovariacao_' + codprodutovariacao).html(minimovariacao);
        $('#maximovariacao_' + codprodutovariacao).html(maximovariacao);

        //     var minimovariacao = 0;
        // var maximovariacao = 0;
        // var vendaanovariacao = 0;
        // var saldovariacao = 0;

        // CALCULA % , SALDO E SALDO ANO 
        if (maximovariacao > 0) {
            var perc = parseInt((saldovariacao/maximovariacao) * 100);
        }
        var html = saldovariacao + ' (' + perc + '%)';
        if (vendaanovariacao) {
            html += ' / ' + vendaanovariacao + ' Ano';
        }        
        if (perc > 100) {
            $('#pb_warning_' + codprodutovariacao).css('width', '100%');
            $('#pb_label_warning_' + codprodutovariacao).html(html);
        } else {
            if (saldovariacao > minimovariacao) {
                $('#pb_success_' + codprodutovariacao).css('width', perc + '%');
                $('#pb_label_success_' + codprodutovariacao).html(html);
            } else {
                $('#pb_success_' + codprodutovariacao).css('width', perc + '%');
                $('#pb_danger_' + codprodutovariacao).css('width', (100 - perc) + '%');
                $('#pb_label_danger_' + codprodutovariacao).html(html);
            }
        }        
    });

    codestoquelocals.forEach(function(codestoquelocal) {
        minimoestoquelocal = 0;
        maximoestoquelocal = 0;
        codprodutovariacaos.forEach(function(codprodutovariacao) {
            var val = parseFloat($('#estoqueminimo_' + codestoquelocal + '_' + codprodutovariacao).val());
            if (val) {
                minimoestoquelocal += val;
            }
            var val = parseFloat($('#estoquemaximo_' + codestoquelocal + '_' + codprodutovariacao).val());
            if (val) {
                maximoestoquelocal += val;
            }
        });
        $('#minimoestoquelocal_' + codestoquelocal).html(minimoestoquelocal);
        $('#maximoestoquelocal_' + codestoquelocal).html(maximoestoquelocal);
    });    
    $('#minimo').html(minimo);
    $('#maximo').html(maximo);
}

function SugerirMinimoMaximo()
{
    const codestoquelocaldeposito = 101001;
    const diasloja = parseInt($('#diasloja').val());
    const diasdeposito = parseInt($('#diasdeposito').val());
    var sugestaomaximo = 0;
    var sugestaominimo = 0;
    var codestoquelocal = 0;
    var codprodutovariacao = 0;

    codestoquelocals.forEach(function(codestoquelocal) {
        if (codestoquelocal == codestoquelocaldeposito) {
            return;
        }
        codprodutovariacaos.forEach(function(codprodutovariacao) {
            sugestaomaximo = 0;
            sugestaominimo = 0;
            var vendaano = parseInt($('#vendaano_' + codestoquelocal + '_' + codprodutovariacao).val());
            if(isNaN(vendaano)){
                vendaano = 0;
            }
            sugestaomaximo = Math.ceil((vendaano/365) * diasloja);
            sugestaominimo = Math.floor((sugestaomaximo/2));
        
            $('#estoqueminimo_' + codestoquelocal + '_' + codprodutovariacao).val(sugestaominimo);
            $('#estoquemaximo_' + codestoquelocal + '_' + codprodutovariacao).val(sugestaomaximo);
        })
    })

    codprodutovariacaos.forEach(function(codprodutovariacao) {
        var totalmaximolojas = 0;
        var totalvendaano = 0;
        codestoquelocals.forEach(function(codestoquelocal) {
            var vendaano = parseInt($('#vendaano_' + codestoquelocal + '_' + codprodutovariacao).val());
            if(isNaN(vendaano)){
                vendaano = 0;
            }   
            totalvendaano += vendaano;         
            if (codestoquelocal == codestoquelocaldeposito) {
                return;
            }          
            var maximolojas = parseInt($('#estoquemaximo_' + codestoquelocal + '_' + codprodutovariacao).val());
            if(isNaN(maximolojas)){
                maximolojas = 0;
            }   
            totalmaximolojas += maximolojas;         
        })

        console.log(codprodutovariacao, totalmaximolojas, totalvendaano);
        // console.log(totalvendaano);

        sugestaomaximo = Math.ceil((totalvendaano/365) * diasdeposito) - totalmaximolojas;
        if (sugestaomaximo < 0) {
            sugestaomaximo = 0;
        }
        sugestaominimo = Math.floor((sugestaomaximo/2));
    
        $('#estoqueminimo_' + codestoquelocaldeposito + '_' + codprodutovariacao).val(sugestaominimo);
        $('#estoquemaximo_' + codestoquelocaldeposito + '_' + codprodutovariacao).val(sugestaomaximo);
    })
    atualizaMinMaxTodosInputs();
    atualizaTotais();
}
</script>
@endsection
@stop

