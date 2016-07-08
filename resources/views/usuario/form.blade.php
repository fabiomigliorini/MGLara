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
  	{!! Form::select('codfilial', $filiais, ['class'=> 'form-control'], ['style'=>'width:100%', 'id'=>'codfilial']) !!}
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
  	{!! Form::label('Pessoa', 'Pessoa:') !!}
  </label>
  <div class="col-sm-4">
  	{!! Form::text('codpessoa', null, ['class'=> 'form-control', 'id'=>'codpessoa']) !!}
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
  <label for="inativo" class="col-sm-2 control-label">
  	{!! Form::label('Inativo', 'Inativo:') !!}
  </label>
  <div class="col-sm-2">
  	{!! Form::text('inativo', null, ['class'=> 'form-control', 'id'=>'inativo']) !!}
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
    $('#codfilial').select2({
        allowClear: true,
        width: 'resolve'
    })<?php echo (isset($model->codfilial) ? ".select2('val', $model->codfilial);" : ';');?>
    $('#codoperacao').select2({
        allowClear: true,
        width: 'resolve'
    })<?php echo (isset($model->codoperacao) ? ".select2('val', $model->codoperacao);" : ';');?>
    $('#codportador').select2({
        allowClear: true,
        width: 'resolve'
    })<?php echo (isset($model->codportador) ? ".select2('val', $model->codportador);" : ';');?>
    $('#inativo').datepicker({
        format: 'dd/mm/yyyy'
    });  
    
    $('#codpessoa').select2({
        minimumInputLength: 3,
        allowClear: true,
        closeOnSelect: true,
        placeholder: 'Pessoa',
        formatResult: function(item) {
            var css = "div-combo-pessoa";
            if (item.inativo)
                var css = "text-error";

            var css_titulo = "";
            var css_detalhes = "text-muted";
            if (item.inativo){
                css_titulo = "text-error";
                css_detalhes = "text-error";
            }

            var nome = item.fantasia;

            //if (item.inclusaoSpc != 0)
            //	nome += "&nbsp<span class=\"label label-warning\">" + item.inclusaoSpc + "</span>";

            var markup = "";
            markup    += "<strong class='" + css_titulo + "'>" + nome + "</strong>";
            markup    += "<small class='pull-right " + css_detalhes + "'>#" + formataCodigo(item.id) + "</small>";
            markup    += "<br>";
            markup    += "<small class='" + css_detalhes + "'>" + item.pessoa + "</small>";
            markup    += "<small class='pull-right " + css_detalhes + "'>" + formataCnpjCpf(item.cnpj) + "</small>";
            return markup;
        },
        formatSelection: function(item) { 
            return item.fantasia; 
        },
        ajax: {
            url: baseUrl+'/pessoa/listagem-json',
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
                    results: data.data, 
                    //more: data.mais
                };
            }
        },
        initSelection: function (element, callback) {
            $.ajax({
                type: "GET",
                url: baseUrl+'/pessoa/listagem-json',
                data: "id=<?php if(isset($model))echo $model->codpessoa;?>",
                dataType: "json",
                success: function(result) { 
                    callback(result); 
                }
            });
        },
        width:'resolve'
    });
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
