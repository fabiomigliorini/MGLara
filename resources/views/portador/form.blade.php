<?php
use MGLara\Models\Banco;
use MGLara\Models\Filial;

 $bancos = [''=>''] + Banco::lists('banco', 'codbanco')->all(); 
 $filiais = [''=>''] + Filial::lists('filial', 'codfilial')->all(); 
?>
<div class="form-group">
    <label for="produto" class="col-sm-2 control-label">{!! Form::label('Portador:') !!}</label>
    <div class="col-sm-4">{!! Form::text('portador', null, ['class'=> 'form-control', 'id'=>'portador']) !!}</div>
</div>
<div class="form-group">
    <label for="codfilial" class="col-sm-2 control-label">{!! Form::label('Banco:') !!}</label>
    <div class="col-sm-2">{!! Form::select('codbanco', $bancos, null, ['class'=> 'form-control', 'id' => 'codbanco', 'style'=>'width:100%']) !!}</div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">{!! Form::label('Agencia:') !!}</label>
    <div class="col-sm-1">{!! Form::text('agencia', null, ['class'=> 'form-control text-right', 'id'=>'agencia']) !!}</div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">{!! Form::label('Digito:') !!}</label>
    <div class="col-sm-1">{!! Form::text('agenciadigito', null, ['class'=> 'form-control text-right', 'id'=>'agenciadigito']) !!}</div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">{!! Form::label('Conta:') !!}</label>
    <div class="col-sm-1">{!! Form::text('conta', null, ['class'=> 'form-control text-right', 'id'=>'conta']) !!}</div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">{!! Form::label('Digito:') !!}</label>
    <div class="col-sm-1">{!! Form::text('contadigito', null, ['class'=> 'form-control text-right', 'id'=>'contadigito']) !!}</div>
</div>
<div class="form-group">
    <label for="site" class="col-sm-2 control-label">{!! Form::label('Emitir boleto:') !!}</label>
    <div class="col-sm-10" id="wrapper-site">{!! Form::checkbox('emiteboleto', true, null, ['id'=>'emiteboleto', 'data-off-text' => 'Não', 'data-on-text' => 'Sim']) !!}</div>
</div>
<div class="form-group">
    <label for="codfilial" class="col-sm-2 control-label">{!! Form::label('Filial:') !!}</label>
    <div class="col-sm-2">{!! Form::select('codfilial', $filiais, null, ['class'=> 'form-control', 'id' => 'codfilial', 'style'=>'width:100%']) !!}</div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">{!! Form::label('Convênio:') !!}</label>
    <div class="col-sm-3">{!! Form::text('convenio', null, ['class'=> 'form-control', 'id'=>'convenio']) !!}</div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">{!! Form::label('Diretório Remessa:') !!}</label>
    <div class="col-sm-4">{!! Form::text('diretorioremessa', null, ['class'=> 'form-control', 'id'=>'diretorioremessa']) !!}</div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">{!! Form::label('Diretório Retorno:') !!}</label>
    <div class="col-sm-4">{!! Form::text('diretorioretorno', null, ['class'=> 'form-control', 'id'=>'diretorioretorno']) !!}</div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">{!! Form::label('Carteira:') !!}</label>
    <div class="col-sm-2">{!! Form::text('carteira', null, ['class'=> 'form-control', 'id'=>'carteira']) !!}</div>
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
    $('#form-portador').on("submit", function(e) {
        var currentForm = this;
        e.preventDefault();
        bootbox.confirm("Tem certeza que deseja salvar?", function(result) {
            if (result) {
                currentForm.submit();
            }
        });
    });
    $('#portador').prop('required', true);
    $('#agenciadigito, #contadigito, #carteira').autoNumeric('init', {mDec:0, aSep:'' });
    $('#codfilial').select2({
        placeholder: 'Filial',
        allowClear: true,
        closeOnSelect: true
    })<?php echo (isset($model->codfilial) ? ".select2('val', $model->codfilial);" : ';');?>   
    $('#codbanco').select2({
        placeholder: 'Banco',
        allowClear: true,
        closeOnSelect: true
    })<?php echo (isset($model->codbanco) ? ".select2('val', $model->codbanco);" : ';');?>
    $('#emiteboleto').bootstrapSwitch();
});
</script>
@endsection