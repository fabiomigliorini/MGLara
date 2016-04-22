<?php
    //...
?>
<div class="form-group">
    <label for="formapagamento" class="col-sm-2 control-label">{!! Form::label('Forma de pagamento:') !!}</label>
    <div class="col-sm-3">{!! Form::text('formapagamento', null, ['class'=> 'form-control', 'id'=>'formapagamento']) !!}</div>
</div>
<div class="form-group">
    <label for="boleto" class="col-sm-2 control-label">{!! Form::label('Boleto:') !!}</label>
    <div class="col-sm-10" id="wrapper-boleto">{!! Form::checkbox('boleto', null, null, ['id'=>'boleto', 'data-off-text' => 'Não', 'data-on-text' => 'Sim']) !!}</div>
</div>
<div class="form-group">
    <label for="fechamento" class="col-sm-2 control-label">{!! Form::label('Fechamento:') !!}</label>
    <div class="col-sm-10" id="wrapper-site">{!! Form::checkbox('fechamento', null, null, ['id'=>'fechamento', 'data-off-text' => 'Não', 'data-on-text' => 'Sim']) !!}</div>
</div>    
<div class="form-group">
    <label for="notafiscal" class="col-sm-2 control-label">{!! Form::label('Nota Fiscal:') !!}</label>
    <div class="col-sm-10" id="wrapper-notafiscal">{!! Form::checkbox('notafiscal', null, null, ['id'=>'notafiscal', 'data-off-text' => 'Não', 'data-on-text' => 'Sim']) !!}</div>
</div>
<div class="form-group">
    <label for="parcelas" class="col-sm-2 control-label">{!! Form::label('Parcelas:') !!}</label>
    <div class="col-sm-1">{!! Form::text('parcelas', null, ['class'=> 'form-control', 'id'=>'parcelas']) !!}</div>
</div>
<div class="form-group">
    <label for="diasentreparcelas" class="col-sm-2 control-label">{!! Form::label('Dias entre parcelas:') !!}</label>
    <div class="col-sm-1">{!! Form::text('diasentreparcelas', null, ['class'=> 'form-control', 'id'=>'diasentreparcelas']) !!}</div>
</div>
<div class="form-group">
    <label for="avista" class="col-sm-2 control-label">{!! Form::label('Á vista:') !!}</label>
    <div class="col-sm-10" id="wrapper-avista">{!! Form::checkbox('avista', null, null, ['id'=>'avista', 'data-off-text' => 'Não', 'data-on-text' => 'Sim']) !!}</div>
</div>
<div class="form-group">
    <label for="formapagamentoecf" class="col-sm-2 control-label">{!! Form::label('Forma de Pagamento ECF:') !!}</label>
    <div class="col-sm-1">{!! Form::text('formapagamentoecf', null, ['class'=> 'form-control', 'id'=>'formapagamentoecf']) !!}</div>
</div>
<div class="form-group">
    <label for="entrega" class="col-sm-2 control-label">{!! Form::label('Entrega:') !!}</label>
    <div class="col-sm-10" id="wrapper-entrega">{!! Form::checkbox('entrega', null, null, ['id'=>'entrega', 'data-off-text' => 'Não', 'data-on-text' => 'Sim']) !!}</div>
</div>
<hr>
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {!! Form::submit($submitTextButton, array('class' => 'btn btn-primary')) !!}
    </div>
</div>

@section('inscript')
<script type="text/javascript">
$(document).ready(function() {
    $('#form-forma-pagamento').on("submit", function(e) {
        var currentForm = this;
        e.preventDefault();
        bootbox.confirm("Tem certeza que deseja salvar?", function(result) {
            if (result) {
                currentForm.submit();
            }
        });
    });
    $('#formapagamento').prop('required', true);
    $('#boleto').bootstrapSwitch('state', <?php echo ($model->boleto == 1 ? 'true' : 'false'); ?>);
    $('input[name="boleto"]').on('switchChange.bootstrapSwitch', function(event, state) {
        var valor;
        if (state === true) {
          valor = 1;
        } else {
          valor = 0;
        }
        $('#boleto').val(valor);
    });
    $('#fechamento').bootstrapSwitch('state', <?php echo ($model->fechamento == 1 ? 'true' : 'false'); ?>);    
    $('input[name="fechamento"]').on('switchChange.bootstrapSwitch', function(event, state) {
        var valor;
        if (state === true) {
          valor = 1;
        } else {
          valor = 0;
        }
        $('#fechamento').val(valor);
    });    
    $('#notafiscal').bootstrapSwitch('state', <?php echo ($model->notafiscal == 1 ? 'true' : 'false'); ?>);    
    $('input[name="notafiscal"]').on('switchChange.bootstrapSwitch', function(event, state) {
        var valor;
        if (state === true) {
          valor = 1;
        } else {
          valor = 0;
        }
        $('#notafiscal').val(valor);
    });    
    $('#avista').bootstrapSwitch('state', <?php echo ($model->avista == 1 ? 'true' : 'false'); ?>);    
    $('input[name="avista"]').on('switchChange.bootstrapSwitch', function(event, state) {
        var valor;
        if (state === true) {
          valor = 1;
        } else {
          valor = 0;
        }
        $('#avista').val(valor);
    });    
    $('#entrega').bootstrapSwitch('state', <?php echo ($model->entrega == 1 ? 'true' : 'false'); ?>);    
    $('input[name="entrega"]').on('switchChange.bootstrapSwitch', function(event, state) {
        var valor;
        if (state === true) {
          valor = 1;
        } else {
          valor = 0;
        }
        $('#entrega').val(valor);
    });    

});
</script>
@endsection