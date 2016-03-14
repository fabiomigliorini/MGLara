<?php
$disabled = 0;
if(isset($model)){
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
    </div>    
</div>

<div class="form-group">
  <label for="data" class="col-sm-2 control-label">
    {!! Form::label('Data:') !!}
  </label>
  <div class="col-md-2 col-xs-4">
    {!! Form::text('data', null, ['class'=> 'form-control text-center', 'id'=>'data', 'required'=>'required']) !!}
  </div>
</div>

<div class="form-group">
  <label for="entradaquantidade" class="col-sm-2 control-label">
    {!! Form::label('Quantidade entrada:') !!}
  </label>
  <div class="col-md-2 col-xs-4">
    {!! Form::text('entradaquantidade', null, ['class'=> 'form-control text-right', 'id'=>'entradaquantidade']) !!}
  </div>
</div>

<div class="form-group">
  <label for="entradavalor" class="col-sm-2 control-label">
    {!! Form::label('Valor entrada:') !!}
  </label>
  <div class="col-md-2 col-xs-4">
    {!! Form::text('entradavalor', null, ['class'=> 'form-control text-right', 'id'=>'entradavalor']) !!}
  </div>
</div>

<div class="form-group">
  <label for="saidaquantidade" class="col-sm-2 control-label">
    {!! Form::label('Quantidade saída:') !!}
  </label>
  <div class="col-md-2 col-xs-4">
    {!! Form::text('saidaquantidade', null, ['class'=> 'form-control text-right', 'id'=>'saidaquantidade']) !!}
  </div>
</div>

<div class="form-group">
  <label for="saidavalor" class="col-sm-2 control-label">
    {!! Form::label('Valor saída:') !!}
  </label>
  <div class="col-md-2 col-xs-4">
      {!! Form::text('saidavalor', null, ['class'=> 'form-control text-right', 'id'=>'saidavalor']) !!} 
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
if (!empty($model->codestoquemovimentoorigem)) {
    $estoquelocal = $model->EstoqueMovimentoOrigem->EstoqueMes->EstoqueSaldo->EstoqueLocal->codestoquelocal;
    $produto = $model->EstoqueMovimentoOrigem->EstoqueMes->EstoqueSaldo->Produto->codproduto;
}

    
?>
@section('inscript')
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
    //console.log($('#codestoquemovimentotipo').val());
    $('#data').datetimepicker({
        locale: 'pt-br',
        format: 'DD/MM/YYYY HH:mm:ss'
    });
    $('#codestoquelocal').select2({
        allowClear: true,
        width: 'resolve'        
    })<?php echo (isset($estoquelocal) ? ".select2('val', $estoquelocal);" : ';');?>
    
    <?php if(isset($produto)) :?>
    $('#codproduto').val(<?php echo $produto;?>);
    <?php endif;?>
    /*
    $('#codproduto').select2({
        minimumInputLength: 3,
        allowClear: true,
        closeOnSelect: true,
        placeholder: 'Produto',
        formatResult:function(item) {
            var markup = "<div class='row'>";
            markup    += "<small class='text-muted col-md-2'><small>" + item.barras + "<br>" + item.codproduto + "</small></small>";
            markup    += "<div class='col-md-8'>" + item.descricao + "<small class='muted text-right pull-right'>" + item.referencia + "</small></div>";
            markup    += "<div><div class='col-md-8 text-right pull-right'><small class='span1 text-muted'>" + item.sigla + "</small>" + item.preco + "";
            markup    += "</div></div>";
            markup    += "</div>";
            return markup;
        },
        formatSelection:function(item) { 
            return item.barras + " - " + item.descricao + " - " + item.preco; 
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
                url: baseUrl+'/pessoa-ajax',
                data: "id=<?php if(isset($model))echo $model->codpessoa;?>",
                dataType: "json",
                success: function(result) { 
                    callback(result); 
                }
            });
        },
        width:'resolve'
    }); 
    */

    <?php if (isset($model->data)) {?>
        $("#data").val("<?php echo formataData($model->data, 'L');?>").change();
    <?php }?>
    
    $('#saidavalor, #entradavalor').autoNumeric('init', {aSep:'.', aDec:',', altDec:'.', mDec:2 });
    $('#saidaquantidade, #entradaquantidade').autoNumeric('init', {aSep:'.', aDec:',', altDec:'.', mDec:3 });
    
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

});

</script>
@endsection
