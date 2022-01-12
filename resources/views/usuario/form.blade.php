<?php
$printers = json_decode(file_get_contents(base_path('printers.json')), true);
$printers = array_merge([''=>''], $printers);
?>
<div class="form-group">
    {!! Form::label('usuario', 'Usuário', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-md-2 col-xs-4">
        {!! Form::text('usuario', null, ['class'=> 'form-control', 'id'=>'usuario', 'required'=>'required']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('senha', 'Senha', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-md-2 col-xs-4">
        {!! Form::password('senha', ['class'=> 'form-control', 'id'=>'senha', isset($model) ? null : 'required=required', 'minlength'=>'6' ]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('repetir_senha', 'Confirmação', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-md-2 col-xs-4">
        {!! Form::password('repetir_senha', ['class'=> 'form-control', 'id'=>'repetir_senha', isset($model) ? null : 'required=required']) !!}
        <span id="error-rpt"></span>
    </div>
</div>

<div class="form-group">
    {!! Form::label('codecf', 'ECF', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-md-2 col-xs-4">
        {!! Form::select2Ecf('codecf', null, ['class' => 'form-control', 'id'=>'codecf']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('codfilial', 'Filial', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-md-2 col-xs-4">
        {!! Form::select2Filial('codfilial', null, ['class' => 'form-control', 'id'=>'codfilial']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('codoperacao', 'Operação', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-md-2 col-xs-4">
        {!! Form::select2Operacao('codoperacao', null, ['class' => 'form-control', 'id'=>'codoperacao']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('codpessoa', 'Pessoa', ['class' => 'col-sm-2 control-label']) !!}
  <div class="col-sm-4">
        {!! Form::select2Pessoa('codpessoa', null, ['class' => 'form-control', 'id'=>'codpessoa', 'placeholder' => 'Pessoa', 'ativo' => 1]) !!}
  </div>
</div>

<div class="form-group">
    {!! Form::label('impressoramatricial', 'Impressora Matricial', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-3">
        {!! Form::select2('impressoramatricial', $printers, null, ['class'=> 'form-control', 'id'=>'impressoramatricial', 'placeholder' => 'Impressora Matricial']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('impressoratermica', 'Impressora Térmica', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-3">
        {!! Form::select2('impressoratermica', $printers, null, ['class' => 'form-control', 'id'=>'impressoratermica', 'placeholder' => 'Impressora Termica']) !!}
    </div>
</div>

<!--
<div class="form-group">
    {!! Form::label('impressoratelanegocio', 'Impressora tela negócio', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-3">
        {!! Form::select('impressoratelanegocio', $printers, null, ['class'=> 'form-control', 'id'=>'impressoratelanegocio']) !!}
    </div>
</div>
-->

<div class="form-group">
    {!! Form::label('codportador', 'Portador', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-3">
        {!! Form::select2Portador('codportador', null, ['class' => 'form-control', 'id'=>'codportador']) !!}
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

    $('#form-usuario').on("submit", function(e) {
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
