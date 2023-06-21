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
                    <th class="text-center" colspan="2" style="min-width: 160px">
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
                            </td>
                        @endforeach
                        <td colspan="2" id="col_{{$linha->codprodutovariacao}}" class="small text-center">
                            <span class="text-muted" id="label_{{$linha->codprodutovariacao}}"></span>
                        </td>
                    </tr>

                @endforeach

                <tr>
                    <th>
                        Totais
                    </th>
                    @foreach ($colunas as $coluna)
                        <th class="text-center">
                            <span id="minimoestoquelocal_{{$coluna->codestoquelocal}}"></span>
                        </th>
                        <th class="text-center">
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
    var step = Math.floor(embalagens[$("#codprodutoembalagemtransferencia").val()]/2);
    if (step == 0) {
        step = 1
    }
    codestoquelocals.forEach(function(codestoquelocal) {
        codprodutovariacaos.forEach(function(codprodutovariacao) {
            $('#estoquemaximo_' + codestoquelocal + '_' + codprodutovariacao).attr('step', step);
            $('#estoqueminimo_' + codestoquelocal + '_' + codprodutovariacao).attr('step', step);
        });
    });
}

function atualizaMinMaxInput(codestoquelocal, codprodutovariacao, campo)
{
    var minimo = parseFloat($('#estoqueminimo_' + codestoquelocal + '_' + codprodutovariacao).val());
    if (isNaN(minimo)) {
        minimo = 0;
    }
    var maximo = parseFloat($('#estoquemaximo_' + codestoquelocal + '_' + codprodutovariacao).val());
    if (isNaN(maximo)) {
        maximo = 0;
    }
    if (campo == 'minimo' && maximo < minimo) {
        $('#estoquemaximo_' + codestoquelocal + '_' + codprodutovariacao).val(minimo);
        maximo = minimo;
    }
    if (campo == 'maximo' && minimo > maximo) {
        $('#estoqueminimo_' + codestoquelocal + '_' + codprodutovariacao).val(maximo);
        minimo = maximo;
    }
    // $('#estoqueminimo_' + codestoquelocal + '_' + codprodutovariacao).attr('max', maximo);
    // $('#estoquemaximo_' + codestoquelocal + '_' + codprodutovariacao).attr('min', minimo);
}

function atualizaTotais()
{
    // total da variacao (linha)
    var minimovariacao = 0;
    var maximovariacao = 0;
    var vendaanovariacao = 0;
    var saldovariacao = 0;

    // total do local (coluna)
    var minimoestoquelocal = 0;
    var maximoestoquelocal = 0;

    // total geral
    var minimo = 0;
    var maximo = 0;

    // percorre as linhas (variacoes)
    codprodutovariacaos.forEach(function(codprodutovariacao) {

        // inicializa totais da variacao
        minimovariacao = 0;
        maximovariacao = 0;
        vendaanovariacao = 0;
        estoquevariacao = 0;

        // percorre os locais (colunas)
        codestoquelocals.forEach(function(codestoquelocal) {

            // minimo
            var min = parseFloat($('#estoqueminimo_' + codestoquelocal + '_' + codprodutovariacao).val());
            if (min) {
                minimovariacao += min;
                minimo += min;
            } else {
                min = 0;
            }

            // maximo
            var max = parseFloat($('#estoquemaximo_' + codestoquelocal + '_' + codprodutovariacao).val());
            if (max) {
                maximovariacao += max;
                maximo += max;
            } else {
                max = 0;
            }

            // saldo
            var sld = parseFloat($('#saldoquantidade_' + codestoquelocal + '_' + codprodutovariacao).val());
            if (sld) {
                saldovariacao += sld;
            } else {
                sld = 0;
            }

            // venda
            var vda = parseInt($('#vendaano_' + codestoquelocal + '_' + codprodutovariacao).val());
            if (vda) {
                vendaanovariacao += vda;
            } else {
                vda = 0;
            }

            // percentual
            var perc = 0;
            if (max > 0) {
                perc = parseInt((sld/max) * 100);
            }

            // monta o html pra mostrar no span  abaixo dos campos de minimo e maximo
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

            // mostra
            $('#label_' + codestoquelocal + '_' + codprodutovariacao).html(html);
            $('#col_' + codestoquelocal + '_' + codprodutovariacao).removeClass('success');
            $('#col_' + codestoquelocal + '_' + codprodutovariacao).removeClass('warning');
            $('#col_' + codestoquelocal + '_' + codprodutovariacao).removeClass('danger');
            if (perc > 100) {
                $('#col_' + codestoquelocal + '_' + codprodutovariacao).addClass('warning');
            } else {
                if (sld > min) {
                    $('#col_' + codestoquelocal + '_' + codprodutovariacao).addClass('success');
                } else {
                    $('#col_' + codestoquelocal + '_' + codprodutovariacao).addClass('danger');
                }
            }

        });

        $('#minimovariacao_' + codprodutovariacao).html(minimovariacao);
        $('#maximovariacao_' + codprodutovariacao).html(maximovariacao);

        // percentual
        var perc = 0;
        if (maximovariacao > 0) {
            perc = parseInt((saldovariacao/maximovariacao) * 100);
        }

        // monta o html pra mostrar no span  abaixo dos campos de minimo e maximo
        var html = '';
        if (vendaanovariacao) {
            var dias = Math.round(parseFloat(minimovariacao / (vendaanovariacao / 365)));
            if (dias) {
                html += dias + ' / ';
            }
            dias = Math.round(parseFloat(maximovariacao / (vendaanovariacao / 365)));
            if (dias) {
                html += dias + ' dias ';
            }
            html += '  (' + vendaanovariacao + ' ano)';
        }
        html += '<br> Saldo ' + saldovariacao + ' (' + perc + '%)';

        // mostra
        $('#label_' + codprodutovariacao).html(html);
        $('#col_' + codprodutovariacao).removeClass('success');
        $('#col_' + codprodutovariacao).removeClass('warning');
        $('#col_' + codprodutovariacao).removeClass('danger');
        if (perc > 100) {
            $('#col_' + codprodutovariacao).addClass('warning');
        } else {
            if (saldovariacao > minimovariacao) {
                $('#col_' + codprodutovariacao).addClass('success');
            } else {
                $('#col_' + codprodutovariacao).addClass('danger');
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
    var step = Math.floor(embalagens[$("#codprodutoembalagemtransferencia").val()]/2);
    if (step == 0) {
        step = 1
    }
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
            sugestaomaximo = Math.ceil(((vendaano/365) * diasloja) / step) * step;
            sugestaominimo = Math.floor((sugestaomaximo/2) / step) * step;

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
        sugestaomaximo = (Math.ceil(((totalvendaano/365) * diasdeposito) / step) * step) - totalmaximolojas;

        if (sugestaomaximo < 0) {
            sugestaomaximo = 0;
        }
        sugestaominimo = Math.floor((sugestaomaximo/2) / step) * step;

        $('#estoqueminimo_' + codestoquelocaldeposito + '_' + codprodutovariacao).val(sugestaominimo);
        $('#estoquemaximo_' + codestoquelocaldeposito + '_' + codprodutovariacao).val(sugestaomaximo);
    })
    atualizaMinMaxTodosInputs();
    atualizaTotais();
}
</script>
@endsection
@stop
