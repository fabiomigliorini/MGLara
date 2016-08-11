<div class="form-group">
  <label for="usuario" class="col-sm-2 control-label">
  	{!! Form::label('Usuário', 'Usuário:') !!}
  </label>
  <div class="col-md-2 col-xs-4">
  	{!! Form::text('usuario', null, ['class'=> 'form-control', 'id'=>'usuario', 'required'=>'required']) !!}
  </div>
</div>

<div class="form-group">
  <label for="senha" class="col-sm-2 control-label">
  	{!! Form::label('Senha', 'Senha:') !!}
  </label>
  <div class="col-md-2 col-xs-4">
  	{!! Form::password('senha', ['class'=> 'form-control', 'id'=>'senha', isset($model) ? null : 'required=required', 'minlength'=>'6' ]) !!}
  </div>
</div>

<div class="form-group">
  <label for="senha" class="col-sm-2 control-label">
  	{!! Form::label('Confirmação', 'Confirmação:') !!}
  </label>
  <div class="col-md-2 col-xs-4">
  	{!! Form::password('repetir_senha', ['class'=> 'form-control', 'id'=>'repetir_senha', isset($model) ? null : 'required=required']) !!}
        <span id="error-rpt"></span>
  </div>
</div>

<div class="form-group">
  <label for="codecf" class="col-sm-2 control-label">
  	{!! Form::label('ECF', 'ECF:') !!}
  </label>
  <div class="col-md-2 col-xs-4">
  	{!! Form::select('codecf', $ecfs, ['class'=> 'form-control'], ['style'=>'width:100%', 'id'=>'codecf']) !!}
  </div>
</div>

<div class="form-group">
  <label for="codfilial" class="col-sm-2 control-label">
  	{!! Form::label('Filial', 'Filial:') !!}
  </label>
  <div class="col-md-2 col-xs-4">
        {!! Form::select2Filial('codfilial', null, ['class' => 'form-control', 'id'=>'codfilial']) !!}
  </div>
</div>

<div class="form-group">
  <label for="codoperacao" class="col-sm-2 control-label">
  	{!! Form::label('Operação', 'Operação:') !!}
  </label>
  <div class="col-md-2 col-xs-4">
  	{!! Form::select('codoperacao', $ops, ['class'=> 'form-control'], ['style'=>'width:100%', 'id'=>'codoperacao']) !!}
  </div>
</div>

<div class="form-group">
  <label for="codpessoa" class="col-sm-2 control-label">
  	{!! Form::label('Pessoa', 'Pesso:') !!}
  </label>
  <div class="col-sm-4">
        {!! Form::select2Pessoa('codpessoa', null, ['class' => 'form-control', 'id'=>'codpessoa', 'placeholder' => 'Pessoa', 'ativo' => 1]) !!}
  </div>
</div>

<div class="form-group">
  <label for="impressoramatricial" class="col-sm-2 control-label">
  	{!! Form::label('Impressora Matricial', 'Impressora Matricial:') !!}
  </label>
  <div class="col-sm-3">
  	{!! Form::select('impressoramatricial', $prints, ['class'=> 'form-control'], ['id'=>'impressoramatricial','required'=>'required']) !!}
  </div>
</div>

<div class="form-group">
  <label for="impressoratermica" class="col-sm-2 control-label">
  	{!! Form::label('Impressora Térmica', 'Impressora Térmica:') !!}
  </label>
  <div class="col-sm-3">
  	{!! Form::select('impressoratermica', $prints, ['class'=> 'form-control'], ['id'=>'impressoratermica','required'=>'required']) !!}
  </div>
</div>

<div class="form-group">
  <label for="impressoratermica" class="col-sm-2 control-label">
  	{!! Form::label('Impressora tela negócio', 'Impressora tela negócio:') !!}
  </label>
  <div class="col-sm-3">
  	{!! Form::select('impressoratelanegocio', $prints, ['class'=> 'form-control'], ['id'=>'impressoratelanegocio']) !!}
  </div>
</div>

<div class="form-group">
  <label for="codportador" class="col-sm-2 control-label">
  	{!! Form::label('Portador', 'Portador:') !!}
  </label>
  <div class="col-sm-2">
  	{!! Form::select('codportador', $portadores, ['class'=> 'form-control'], ['style'=>'width:100%', 'id'=>'codportador']) !!}
  </div>
</div>

<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
  {!! Form::submit($submitTextButton, array('class' => 'btn btn-primary')) !!}
  </div>
</div>

@section('inscript')
<style type="text/css">
    #error-rpt {
        float:right;
        color: #b94a48;
    }
</style>
<script type="text/javascript">
$(document).ready(function() {
    function validarSenha(form){ 
        senha = $('#senha').val();
        senhaRepetida = $('#repetir_senha').val();
        if (senha != senhaRepetida){
            var aviso = '<span id="rpt">Confirmação deve ser exatamente repetido</span>';
            var destino = $('#error-rpt');
            destino.append(aviso);
            setTimeout(function(){
                $('#rpt').remove();
            },13000);            
            $('repetir_senha').focus();
            console.log(senha + '-' + senhaRepetida);
            return false;
        } else {
            $("#rpt").remove();
        }
    }    
    $("#repetir_senha" ).blur(function() {
      validarSenha();
    });    
    $('#codecf').select2({
        allowClear: true,
        width: 'resolve'
    })<?php echo (isset($model->codecf) ? ".select2('val', $model->codecf);" : ';');?>

    $('#codoperacao').select2({
        allowClear: true,
        width: 'resolve'
    })<?php echo (isset($model->codoperacao) ? ".select2('val', $model->codoperacao);" : ';');?>
    $('#codportador').select2({
        allowClear: true,
        width: 'resolve'
    })<?php echo (isset($model->codportador) ? ".select2('val', $model->codportador);" : ';');?>

    $('#impressoratermica').select2({
        allowClear: true,
        width: 'resolve'
    })<?php echo (isset($model->impressoratermica) ? ".select2('val', '$model->impressoratermica');" : ';');?>    
    
    $('#impressoramatricial').select2({
        allowClear: true,
        width: 'resolve'
    })<?php echo (isset($model->impressoramatricial) ? ".select2('val', '$model->impressoramatricial');" : ';');?>    

    $('#impressoratelanegocio').select2({
        allowClear: true,
        width: 'resolve'
    })<?php echo (isset($model->impressoratelanegocio) ? ".select2('val', '$model->impressoratelanegocio');" : ';');?>    
    
    

    
});
</script>
@endsection
