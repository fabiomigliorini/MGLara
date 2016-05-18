<?php

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

<div class="form-group">
    <label for="data" class="col-sm-2 control-label">
        {!! Form::label('Data:') !!}
    </label>
    <div class="col-md-2 col-xs-4">
        {!! Form::text('data', null, ['class'=> 'form-control text-center', 'id'=>'data', 'required'=>'required']) !!}
    </div>
</div>

<div class="form-group">
  <label for="codestoquemovimentotipo" class="col-sm-2 control-label">
    {!! Form::label('Tipo:') !!}
  </label>
  <div class="col-md-2 col-xs-4">
    {!! Form::select('codestoquemovimentotipo', $tipos, ['class'=> 'form-control', 'required'=>'required'], ['id'=>'codestoquemovimentotipo']) !!}
  </div>
</div>

<div id="origens" class="hide">
    <div class="form-group">
      <label for="codproduto" class="col-sm-2 control-label">
        {!! Form::label('Produto:') !!}
      </label>
      <div class="col-md-6 col-xs-4">
        {!! Form::text('codproduto', null, ['class'=> 'form-control', 'id'=>'codproduto']) !!}
      </div>
    </div>

    <div class="form-group">
      <label for="codestoquelocal" class="col-sm-2 control-label">
        {!! Form::label('Estoque Local:') !!}
      </label>
      <div class="col-md-2 col-xs-4">
        {!! Form::select('codestoquelocal', $el, ['class'=> 'form-control'], ['style'=>'width:100%', 'id'=>'codestoquelocal']) !!}
      </div>
        <div id="saldoEstoqueLocal" class="hide">
            <div role="tooltip" class="popover fade right in" id="popoversaldos">
                <div class="arrow" style="top: 17px;"></div>
                <div class="popover-content" id="saldoEstoqueLocalContent"></div>
            </div>           
        </div>
    </div>    
</div>


<div class="form-group">
  <label for="entradaquantidade" class="col-sm-2 control-label">
    {!! Form::label('Quantidade entrada:') !!}
  </label>
  <div class="col-md-2 col-xs-4">
    {!! Form::text('entradaquantidade', null, ['class'=> 'form-control text-right', 'id'=>'entradaquantidade']) !!}
  </div>
    <div class="col-md-2 col-xs-4">
        <strong class="pull-left" style="margin-top: 7px;">Valor:</strong> {!! Form::text('entradavalor', null, ['class'=> 'form-control text-right', 'id'=>'entradavalor', 'style' => 'float: right; width: 130px;']) !!}
    </div>
</div>

<div class="form-group">
  <label for="saidaquantidade" class="col-sm-2 control-label">
    {!! Form::label('Quantidade saída:') !!}
  </label>
  <div class="col-md-2 col-xs-4">
    {!! Form::text('saidaquantidade', null, ['class'=> 'form-control text-right', 'id'=>'saidaquantidade']) !!}
  </div>
    <div class="col-md-2 col-xs-4">
        <strong class="pull-left" style="margin-top: 7px;">Valor:</strong> {!! Form::text('saidavalor', null, ['class'=> 'form-control text-right', 'id'=>'saidavalor', 'style' => 'float: right; width: 130px;']) !!}
    </div>    
</div>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {!! Form::submit($submitTextButton, array('class' => 'btn btn-primary', 'id'=>'Submit')) !!}
    </div>
</div>
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
        $estoquelocal = $model->EstoqueMovimentoOrigem->EstoqueMes->EstoqueSaldo->EstoqueLocalProduto->EstoqueLocal->codestoquelocal;
        $produto = $model->EstoqueMovimentoOrigem->EstoqueMes->EstoqueSaldo->EstoqueLocalProduto->Produto->codproduto;
    } else {
        $estoquelocal = $model->EstoqueMes->EstoqueSaldo->EstoqueLocalProduto->EstoqueLocal->codestoquelocal;
        $produto = $model->EstoqueMes->EstoqueSaldo->EstoqueLocalProduto->Produto->codproduto;
    }
} else {
    //$datainicial = $model->EstoqueMes->mes->year.'-'.$model->EstoqueMes->mes->month.'-'. date("t", mktime(0,0,0,$model->EstoqueMes->mes->month,'01',$model->EstoqueMes->mes->year));
    $estoquelocal = $model->EstoqueMes->EstoqueSaldo->EstoqueLocalProduto->EstoqueLocal->codestoquelocal;
    $produto = $model->EstoqueMes->EstoqueSaldo->EstoqueLocalProduto->Produto->codproduto;
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
            
            $("#origens").fadeOut("slow", function() {
                $(this).addClass("hide");
            });            
        } else {
            $("#origens").fadeIn("slow", function() {
                $(this).removeClass("hide");
            });              
        }
    });
    
    if($('#codestoquemovimentotipo').val()) {
        if(tipos[$('#codestoquemovimentotipo').val()+'origem'] == null) {
            
            $("#origens").fadeOut("slow", function() {
                $(this).addClass("hide");
            });            
        } else {
            $("#origens").fadeIn("slow", function() {
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
            markup    += "<small class='text-muted col-md-2'> <small>#" /*+ item.barras + "<br>"*/ + item.id + "</small></small>";
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
            url: baseUrl+'/produto/ajax',
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
                url: baseUrl+'/produto/ajax',
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
