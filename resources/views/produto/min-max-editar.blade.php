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
    <form id="form-minimo-maximo" method="POST">
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
                        <th>
                            {{$linha->variacao}}
                        </th>
                        @foreach ($colunas as $coluna)
                            <?php
                                $valor = $valores->first(function ($key, $value) use ($linha, $coluna) {
                                    return 
                                        ($value->codestoquelocal == $coluna->codestoquelocal)
                                        && ($value->codprodutovariacao == $linha->codprodutovariacao);
                                });
                                // dd([
                                //     $linha, $coluna, $valor
                                // ]);

                                $estoqueminimo = null;
                                $estoquemaximo = null;
                                if ($valor) {
                                    $estoqueminimo = $valor->estoqueminimo;
                                    $estoquemaximo = $valor->estoquemaximo;
                                }
                            ?>
                            <td>
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
                            <td>
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

        foreach ($model->ProdutoEmbalagemS()->orderBy('quantidade')->get() as $pe){
            $embalagens[$pe->codprodutoembalagem] = $pe->descricao;
        } 

        $optionsUnidadeMedida = [
            'class'=> 'form-control',
            'id' => 'codprodutoembalagem',
            'style'=>'width:100%',
        ];
        ?>
        <div class="row">
            <label for="codprodutoembalagemcompra" class="col-sm-1">{!! Form::label('Compra:') !!}</label>
            <div class="col-sm-2">{!! Form::select('codprodutoembalagemcompra', $embalagens, $model->codprodutoembalagemcompra, $optionsUnidadeMedida) !!}</div>
            <label for="codprodutoembalagemtransferencia" class="col-sm-1 control-label">{!! Form::label('Transferência:') !!}</label>
            <div class="col-sm-2">{!! Form::select('codprodutoembalagemtransferencia', $embalagens, $model->codprodutoembalagemtransferencia, $optionsUnidadeMedida) !!}</div>
        </div>

        <button class="btn btn-primary" type="submit">Salvar</button>
    </form>
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
});
</script>

<script type="text/javascript">

const codestoquelocals = <?php echo $colunas->pluck('codestoquelocal')->toJson() ?>; 
const codprodutovariacaos = <?php echo $linhas->pluck('codprodutovariacao')->toJson() ?>; 

function atualizaMinMaxTodosInputs()
{
    codestoquelocals.forEach(function(codestoquelocal) {
        codprodutovariacaos.forEach(function(codprodutovariacao) {
            atualizaMinMaxInput(codestoquelocal, codprodutovariacao);
        })
    })
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
    var minimoestoquelocal = 0;
    var maximoestoquelocal = 0;
    var minimo = 0;
    var maximo = 0;
    codprodutovariacaos.forEach(function(codprodutovariacao) {
        minimovariacao = 0;
        maximovariacao = 0;
        codestoquelocals.forEach(function(codestoquelocal) {
            var val = parseFloat($('#estoqueminimo_' + codestoquelocal + '_' + codprodutovariacao).val());
            if (val) {
                minimovariacao += val;
                minimo += val;
            }
            var val = parseFloat($('#estoquemaximo_' + codestoquelocal + '_' + codprodutovariacao).val());
            if (val) {
                maximovariacao += val;
                maximo += val;
            }
        });
        $('#minimovariacao_' + codprodutovariacao).html(minimovariacao);
        $('#maximovariacao_' + codprodutovariacao).html(maximovariacao);
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

$(document).ready(function() {
    atualizaMinMaxTodosInputs();
    atualizaTotais();
});

</script>
@endsection
@stop

