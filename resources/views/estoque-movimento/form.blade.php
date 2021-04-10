{!! Form::hidden('codestoquemes') !!}

<div class="form-group">
    <label for="data" class="col-sm-2 control-label">
        Data:
    </label>
    <div class="col-md-2">
        {!! Form::datetimeLocal('data', $model->data, ['class'=> 'form-control text-center', 'id'=>'data', 'required'=>'required']) !!}
    </div>
</div>

<div class="form-group">
    <label for="codestoquemovimentotipo" class="col-sm-2 control-label">
        Tipo:
    </label>
    <div class="col-md-3">
        {!! Form::select2EstoqueMovimentoTipo('codestoquemovimentotipo', null, ['class'=> 'form-control', 'manual' => true, 'required'=>'required', 'id'=>'codestoquemovimentotipo', 'onChange' => 'habilitaValores()']) !!}
    </div>
</div>

<div class="form-group collapse" id='divOrigem'>
    <label for="codprodutoorigem" class="col-sm-2 control-label">
        Origem:
    </label>
    <div class="col-md-4">
        {!! Form::select2Produto('codprodutoorigem', $codprodutoorigem, ['class'=> 'form-control', 'id'=>'codprodutoorigem']) !!}
    </div>
    <div class="col-md-2">
        {!! Form::select2ProdutoVariacao('codprodutovariacaoorigem', $codprodutovariacaoorigem, ['class'=> 'form-control', 'id'=>'codprodutovariacaoorigem', 'codproduto' => 'codprodutoorigem']) !!}
    </div>
    <div class="col-md-2">
        {!! Form::select2EstoqueLocal('codestoquelocalorigem', $codestoquelocalorigem, ['class'=> 'form-control', 'style'=>'width:100%', 'id'=>'codestoquelocalorigem']) !!}
    </div>
</div>

<div class="form-group">
    <label for="entradaquantidade" class="col-sm-2 control-label">
        Entrada:
    </label>
    <div class="col-md-2">
        {!! Form::number('entradaquantidade', null, ['class'=> 'form-control text-right', 'id'=>'entradaquantidade', 'min' => 0, 'step' => 0.001, 'autofocus'=>'autofocus', 'onChange'=>'calculaTotal("entrada")']) !!}
    </div>
    <label for="entradaquantidade" class="col-sm-1 control-label">
        Unitário:
    </label>
    <div class="col-md-2">
        {!! Form::number('entradaunitario', null, ['class'=> 'form-control text-right', 'id'=>'entradaunitario', 'min' => 0, 'step' => 0.000001, 'onChange'=>'calculaTotal("entrada")']) !!}
    </div>
    <label for="entradaquantidade" class="col-sm-1 control-label">
        Total:
    </label>
    <div class="col-md-2">
        {!! Form::number('entradavalor', null, ['class'=> 'form-control text-right', 'id'=>'entradavalor', 'min' => 0, 'step' => 0.01, 'onChange'=>'calculaUnitario("entrada")']) !!}
    </div>
</div>

<div class="form-group">
    <label for="saidaquantidade" class="col-sm-2 control-label">
        Saída:
    </label>
    <div class="col-md-2">
        {!! Form::number('saidaquantidade', null, ['class'=> 'form-control text-right', 'min' => 0, 'step' => 0.001, 'id'=>'saidaquantidade', 'onChange'=>'calculaTotal("saida")']) !!}
    </div>
    <label for="saidaquantidade" class="col-sm-1 control-label">
        Saída:
    </label>
    <div class="col-md-2">
        {!! Form::number('saidaunitario', null, ['class'=> 'form-control text-right', 'id'=>'saidaunitario', 'min' => 0, 'step' => 0.000001, 'onChange'=>'calculaTotal("saida")']) !!}
    </div>
    <label for="saidaquantidade" class="col-sm-1 control-label">
        Total:
    </label>
    <div class="col-md-2">
        {!! Form::number('saidavalor', null, ['class'=> 'form-control text-right', 'id'=>'saidavalor', 'min' => 0, 'step' => 0.01, 'onChange'=>'calculaUnitario("saida")']) !!}
    </div>    
    
</div>




<div class="form-group">
    <label for="observacoes" class="col-sm-2 control-label">
        Observações:
    </label>
    <div class="col-md-8">
        {!! Form::textarea('observacoes', null, ['class'=> 'form-control', 'id'=>'observacoes']) !!}
    </div>
</div>


<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {!! Form::submit('Salvar', array('class' => 'btn btn-primary', 'id'=>'Submit')) !!}
        <a href='{{url("estoque-mes/{$model->codestoquemes}")}}' class='btn btn-danger'>
            Cancelar
        </a>
    </div>
</div>

@section('inscript')
<script type="text/javascript">

function habilitaValores()
{
    var tipoPrecoInformado = {{ json_encode($tipoPrecoInformado) }};
    var tipoOrigem = {{ json_encode($tipoOrigem) }};
    var codestoquemovimentotipo = parseInt($('#codestoquemovimentotipo').val());

    if ($.inArray(codestoquemovimentotipo, tipoPrecoInformado) >= 0) {
        $("#entradaunitario").removeAttr('disabled');
        $("#saidaunitario").removeAttr('disabled');
        $("#entradavalor").removeAttr('disabled');
        $("#saidavalor").removeAttr('disabled');
    } else {
        $("#entradaunitario").prop('disabled', true);
        $("#saidaunitario").prop('disabled', true);
        $("#entradavalor").prop('disabled', true);
        $("#saidavalor").prop('disabled', true);
    }
    
    if ($.inArray(codestoquemovimentotipo, tipoOrigem) >= 0) {
        $("#codestoquelocalorigem").removeAttr('disabled');
        $("#codprodutoorigem").removeAttr('disabled');
        $("#codprodutovariacaoorigem").removeAttr('disabled');
        $('#divOrigem').collapse('show');
        $("#saidaquantidade").prop('disabled', true);
        $("#saidaunitario").prop('disabled', true);
        $("#saidavalor").prop('disabled', true);
    } else {
        $("#codestoquelocalorigem").prop('disabled', true);
        $("#codprodutoorigem").prop('disabled', true);
        $("#codprodutovariacaoorigem").prop('disabled', true);
        $('#divOrigem').collapse('hide');
        $("#saidaquantidade").removeAttr('disabled');
        $("#saidaunitario").removeAttr('disabled');
        $("#saidavalor").removeAttr('disabled');
    }
    
}

function calculaUnitario(campo) 
{
    
    var qtd = $('#' + campo + 'quantidade').val();
    
    var tot = $('#' + campo + 'valor').val();
    
    if (qtd > 0) {
        $('#' + campo + 'unitario').val(Math.round((tot/qtd)*1000000)/1000000);
    }
    
}    

function calculaTotal(campo) 
{
    
    var qtd = $('#' + campo + 'quantidade').val();
    
    if (qtd == '') {
        qtd = 1;
    }
        
    var un = $('#' + campo + 'unitario').val();

    var total = Math.round(qtd * un * 100) / 100;
    
    if (un == '') {
        $('#' + campo + 'valor').val(null);
    } else {
        $('#' + campo + 'valor').val(total);
    }
    
}    

$(document).ready(function() {
    calculaUnitario('entrada');
    calculaUnitario('saida');
    habilitaValores();
    
    $('#form-estoque-movimento').on("submit", function(e) {
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
@endsection

<?php

/*
use MGLara\Models\EstoqueLocal;
use MGLara\Models\EstoqueMovimentoTipo;

$el = EstoqueLocal::lists('estoquelocal', 'codestoquelocal')->all();
$tipos = [''=>''] + EstoqueMovimentoTipo::lists('descricao', 'codestoquemovimentotipo')->all();
$options = EstoqueMovimentoTipo::all();

$disabled = 0;
if(isset($model->EstoqueMovimentoTipo)) {
    if($model->EstoqueMovimentoTipo->preco == 1) {
        $disabled = 1;
    } elseif ($model->EstoqueMovimentoTipo->preco == 2) {
        $disabled = 2;
    } elseif ($model->EstoqueMovimentoTipo->preco == 3) {
        $disabled = 3;
    }
}
?>




<?php 
$items = [];
foreach ($options as $option)
{
    $items[$option['codestoquemovimentotipo']] = $option['preco'];
    $items[$option['codestoquemovimentotipo'].'origem'] = $option['codestoquemovimentotipoorigem'];
}
if(isset($model)) {
    //$datainicial = $model->data;
    if (!empty($model->codestoquemovimentoorigem)) {
        $estoquelocal = $model->EstoqueMovimentoOrigem->EstoqueMes->EstoqueSaldo->EstoqueLocalProdutoVariacao->EstoqueLocal->codestoquelocal;
        $produto = $model->EstoqueMovimentoOrigem->EstoqueMes->EstoqueSaldo->EstoqueLocalProdutoVariacao->Produto->codproduto;
    } else {
        $estoquelocal = $model->EstoqueMes->EstoqueSaldo->EstoqueLocalProdutoVariacao->EstoqueLocal->codestoquelocal;
        $produto = $model->EstoqueMes->EstoqueSaldo->EstoqueLocalProdutoVariacao->Produto->codproduto;
    }
} else {
    //$datainicial = $model->EstoqueMes->mes->year.'-'.$model->EstoqueMes->mes->month.'-'. date("t", mktime(0,0,0,$model->EstoqueMes->mes->month,'01',$model->EstoqueMes->mes->year));
    $estoquelocal = $model->EstoqueMes->EstoqueSaldo->EstoqueLocalProdutoVariacao->EstoqueLocal->codestoquelocal;
    $produto = $model->EstoqueMes->EstoqueSaldo->EstoqueLocalProdutoVariacao->Produto->codproduto;
}
?>
@section('inscript')
<style type="text/css">
    #saldoEstoqueLocal {
        position: relative;
    }
    #popoversaldos {
        top: 0px; 
        left: 420px; 
        display: block;
        min-width: 300px;
    }
    #saldoEstoqueLocalContent strong {
        float: left;
        margin-right: 5px;
        text-align: right;
        width: 105px;
    }    
</style>
<script type="text/javascript">
$(document).ready(function() {
    
    $('#estoqueMovimento').on("submit", function(e){
        var currentForm = this;
        e.preventDefault();
        bootbox.confirm("Tem certeza que deseja salvar?", function(result) {
            if (result) {
                currentForm.submit();
            }
        });
    });    

    var tipos = <?php echo json_encode($items)?>;
    $('#codestoquemovimentotipo').select2({
        allowClear: true,
        width: 'resolve'        
    })<?php echo (isset($model->codestoquemovimentotipo) ? ".select2('val', $model->codestoquemovimentotipo);" : ';');?>
    
    $('#data').datetimepicker({
        locale: 'pt-br',
        format: 'DD/MM/YYYY HH:mm:ss'
    });
    $("#data").val("<?php echo formataData($model->data, 'L');?>").change();
    
    $('#codestoquelocal').select2({
        allowClear: true,
        width: 'resolve'        
    })<?php echo (isset($estoquelocal) ? ".select2('val', $estoquelocal);" : ';');?>
    

    $('#saidavalor, #entradavalor').autoNumeric('init', {aSep:'.', aDec:',', altDec:'.', mDec:2 });
    $('#saidaquantidade, #entradaquantidade, .saldoquantidade').autoNumeric('init', {aSep:'.', aDec:',', altDec:'.', mDec:3 });
    
    
    <?php if ($disabled > 1) :?>
        $('#entradavalor, #saidavalor').prop("disabled", true);
    <?php endif;?>
     
    $('#codestoquemovimentotipo').change(function() {
        if(tipos[$('#codestoquemovimentotipo').val()] == 1) {
            $('#entradavalor, #saidavalor').prop("disabled", false);
        } else {
            $('#entradavalor, #saidavalor').prop("disabled", true);
        }
        
        if(tipos[$('#codestoquemovimentotipo').val()+'origem'] == null) {
            
            $("#divOrigem").fadeOut("slow", function() {
                $(this).addClass("hide");
            });            
        } else {
            $("#divOrigem").fadeIn("slow", function() {
                $(this).removeClass("hide");
            });              
        }
    });
    
    if($('#codestoquemovimentotipo').val()) {
        if(tipos[$('#codestoquemovimentotipo').val()+'origem'] == null) {
            
            $("#divOrigem").fadeOut("slow", function() {
                $(this).addClass("hide");
            });            
        } else {
            $("#divOrigem").fadeIn("slow", function() {
                $(this).removeClass("hide");
            });              
        }
    }

    $('#codproduto').select2({
        minimumInputLength: 3,
        allowClear: true,
        closeOnSelect: true,
        placeholder: 'Produto',
        formatResult:function(item) {
            var markup = "<div class='row'>";
            markup    += "<small class='text-muted col-md-2'> <small>#" + item.barras + "<br>" + item.id + "</small></small>";
            markup    += "<div class='col-md-8'>" + item.produto + "<small class='muted text-right pull-right'></small></div>";
            markup    += "<div><div class='col-md-8 text-right pull-right'><small class='span1 text-muted'></small>" + item.preco + "";
            markup    += "</div></div>";
            markup    += "</div>";
            return markup;
        },
        formatSelection:function(item) { 
            return item.produto + " - " + item.preco; 
        },
        ajax: {
            url: baseUrl+'/produto/listagem-json',
            dataType: 'json',
            quietMillis: 500,
            data: function(term, current_page) { 
                return {
                    q: term, 
                    per_page: 10, 
                    current_page: current_page
                }; 
            },
            results:function(data,page) {
                //var more = (current_page * 20) < data.total;
                return {
                    results: data, 
                    //more: data.mais
                };
            }
        },
        initSelection: function (element, callback) {
            $.ajax({
                type: "GET",
                url: baseUrl+'/produto/listagem-json',
                data: "id=<?php echo $produto;?>",
                dataType: "json",
                success: function(result) { 
                    callback(result[0]); 
                }
            });
        },
        width:'resolve'
    })<?php echo (isset($produto) ? ".select2('val', $produto);" : ';');?>

    if($('#codestoquelocal').val()) {
        $("#saldoEstoqueLocalContent").empty();
        var codproduto = $('#codproduto').val();
        var codestoquelocal = $('#codestoquelocal').val();
        var fiscal = <?php echo $model->EstoqueMes->EstoqueSaldo->fiscal; ?>;
        estoqueSaldo(codproduto, codestoquelocal, fiscal);
    }
    
    $('#codestoquelocal').change(function() {
        $("#saldoEstoqueLocalContent").empty();
        var codproduto = $('#codproduto').val();
        var codestoquelocal = $('#codestoquelocal').val();
        var fiscal = <?php echo $model->EstoqueMes->EstoqueSaldo->fiscal; ?>;
        estoqueSaldo(codproduto, codestoquelocal, fiscal);
    });
    
    function estoqueSaldo(codproduto, codestoquelocal, fiscal) {
        $.getJSON(baseUrl + '/produto/estoque-saldo?codproduto='+codproduto+'&codestoquelocal='+codestoquelocal+'&fiscal='+fiscal)
            .done(function(data) {
                $('#saldoEstoqueLocal').removeClass('hide');
                if(data.length > 0){
                    $.each(data, function(k, v) {
                        $('#saldoEstoqueLocalContent').prepend('<p><strong>Quantidade:</strong> <span class="saldoquantidade">' + v.saldoquantidade + '</span></p>' +
                            '<p><strong>Valor:</strong> <span class="saldovalor">' + v.saldovalor +'</span></p>' +
                            '<p><strong>Custo médio:</strong> <span class="customedio">' + v.customedio +'</span></p>');
                    });
                    $('.saldoquantidade').autoNumeric('init', {aSep:'.', aDec:',', altDec:'.', mDec:3 });
                    $('.customedio').autoNumeric('init', {aSep:'.', aDec:',', altDec:'.', mDec:6 });
                    $('.saldovalor').autoNumeric('init', {aSep:'.', aDec:',', altDec:'.', mDec:2 });
                } else {
                    $('#saldoEstoqueLocalContent').prepend('<p>Sem saldo</p>');
                }
            }).fail(function(error ) {
                return console.log(error)
            });    
        return false;    
    }
});

</script>
@endsection
 * 
 */
