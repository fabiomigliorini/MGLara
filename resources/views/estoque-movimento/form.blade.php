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
  {!! Form::submit($submitTextButton, array('class' => 'btn btn-primary')) !!}
  </div>
</div>
<?php 
$items = [];
foreach ($options as $option)
{
    $items[$option['codestoquemovimentotipo']] = $option['preco'];
}

?>
@section('inscript')
<script type="text/javascript">
$(document).ready(function() {
    var tipos = <?php echo json_encode($items)?>;
    $('#codestoquemovimentotipo').select2({
        allowClear: true,
        width: 'resolve'        
    })<?php echo (isset($model->codestoquemovimentotipo) ? ".select2('val', $model->codestoquemovimentotipo);" : ';');?>
    $('#data').datetimepicker({
        locale: 'pt-br',
        format: 'DD/MM/YYYY HH:mm:ss'
    });

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
    });
     
     
});

function onSubmit ()
{
    /*
    var entradaquantidade = $('#entradaquantidade').val();
    var saidaquantidade = $('#saidaquantidade').val();
    var saidavalor = $('#saidavalor').val();
    var entradavalor = $('#entradavalor').val();
    
    val = $('#entradaquantidade').autoNumeric('get');
    $('#entradaquantidade').autoNumeric('destroy');
    $('#entradaquantidade').set(val);
    
    $('#saidaquantidade').autoNumeric('destroy');
    $('#saidaquantidade').autoNumeric('destroy');
    $('#saidavalor').autoNumeric('destroy');
    $('#entradavalor').autoNumeric('destroy');
    */
}   
</script>
@endsection
