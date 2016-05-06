<?php

    use MGLara\Models\EstadoCivil;
    use MGLara\Models\Sexo;

    $sexos        = [''=>''] + Sexo::lists('sexo', 'codsexo')->all();
    $estadoscivil = [''=>''] + EstadoCivil::lists('estadocivil', 'codestadocivil')->all();

?>
<div class="form-group">
    <label for="fantasia" class="col-sm-2 control-label">{!! Form::label('Nome Fantasia:') !!}</label>
    <div class="col-sm-3">{!! Form::text('fantasia', null, ['class'=> 'form-control', 'id'=>'fantasia']) !!}</div>
</div>
<div class="form-group">
    <label for="pessoa" class="col-sm-2 control-label">{!! Form::label('Razão Social:') !!}</label>
    <div class="col-sm-6">{!! Form::text('pessoa', null, ['class'=> 'form-control', 'id'=>'pessoa']) !!}</div>
</div>
<div class="form-group">
    <label for="contato" class="col-sm-2 control-label">{!! Form::label('Contato:') !!}</label>
    <div class="col-sm-3">{!! Form::text('contato', null, ['class'=> 'form-control', 'id'=>'contato']) !!}</div>
</div>
<div class="form-group">
    <label for="codcidade" class="col-sm-2 control-label">{!! Form::label('Cidade:') !!}</label>
    <div class="col-sm-3">{!! Form::text('codcidade', null, ['class'=> 'form-control', 'id'=>'codcidade']) !!}</div>
</div>
<div class="form-group">
    <label for="fisica" class="col-sm-2 control-label">{!! Form::label('Pessoa física:') !!}</label>
    <div class="col-sm-10" id="wrapper-site">{!! Form::checkbox('fisica', null, null, ['id'=>'fisica', 'data-off-text' => 'Jurídica', 'data-on-text' => 'Física']) !!}</div>
</div>
<div class="form-group">
    <label for="cnpj" class="col-sm-2 control-label">{!! Form::label('CNPJ/CPF:') !!}</label>
    <div class="col-sm-3">{!! Form::text('cnpj', null, ['class'=> 'form-control', 'id'=>'cnpj']) !!}</div>
</div>
<div class="form-group">
    <label for="cnpj" class="col-sm-2 control-label">{!! Form::label('Inscrição Estadual:') !!}</label>
    <div class="col-sm-3">{!! Form::text('ie', null, ['class'=> 'form-control', 'id'=>'ie']) !!}</div>
</div>

<div id="fisica">
    <h3>Dados Pessoa Física</h3>
    <div class="form-group">
        <label for="rg" class="col-sm-2 control-label">{!! Form::label('RG:') !!}</label>
        <div class="col-sm-2">{!! Form::text('rg', null, ['class'=> 'form-control', 'id'=>'rg']) !!}</div>
    </div>
    <div class="form-group">
        <label for="codsexo" class="col-sm-2 control-label">{!! Form::label('Sexo:') !!}</label>
        <div class="col-sm-2">{!! Form::select('codsexo', $sexos, ['class'=> 'form-control'], ['id' => 'codsexo', 'style'=>'width:100%']) !!}</div>
    </div>
    <div class="form-group">
        <label for="codestadocivil" class="col-sm-2 control-label">{!! Form::label('Estado Civil:') !!}</label>
        <div class="col-sm-2">{!! Form::select('codestadocivil', $estadoscivil, ['class'=> 'form-control'], ['id' => 'codestadocivil', 'style'=>'width:100%']) !!}</div>
    </div>
    <div class="form-group">
        <label for="conjuge" class="col-sm-2 control-label">{!! Form::label('Conjuge:') !!}</label>
        <div class="col-sm-3">{!! Form::text('conjuge', null, ['class'=> 'form-control', 'id'=>'conjuge']) !!}</div>
    </div>    
</div>
<div class="form-group">
    <label for="cliente" class="col-sm-2 control-label">{!! Form::label('Cliente:') !!}</label>
    <div class="col-sm-10" id="wrapper-site">{!! Form::checkbox('cliente', null, null, ['id'=>'cliente', 'data-off-text' => 'Não', 'data-on-text' => 'Sim']) !!}</div>
</div>
    <div class="form-group">
        <label for="cep" class="col-sm-2 control-label">{!! Form::label('CEP:') !!}</label>
        <div class="col-sm-2">{!! Form::text('cep', null, ['class'=> 'form-control', 'id'=>'cep']) !!}</div>
    </div>  
    <div class="form-group">
        <label for="endereco" class="col-sm-2 control-label">{!! Form::label('Endereço:') !!}</label>
        <div class="col-sm-3">{!! Form::text('endereco', null, ['class'=> 'form-control', 'id'=>'endereco']) !!}</div>
    </div>  
    <div class="form-group">
        <label for="numero" class="col-sm-2 control-label">{!! Form::label('Nº:') !!}</label>
        <div class="col-sm-1">{!! Form::text('numero', null, ['class'=> 'form-control', 'id'=>'numero']) !!}</div>
    </div>  
    <div class="form-group">
        <label for="complemento" class="col-sm-2 control-label">{!! Form::label('Complemento:') !!}</label>
        <div class="col-sm-2">{!! Form::text('complemento', null, ['class'=> 'form-control', 'id'=>'complemento']) !!}</div>
    </div>  
    <div class="form-group">
        <label for="bairro" class="col-sm-2 control-label">{!! Form::label('Bairro:') !!}</label>
        <div class="col-sm-2">{!! Form::text('bairro', null, ['class'=> 'form-control', 'id'=>'bairro']) !!}</div>
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
    $('#form-pessoa').on("submit", function(e) {
        var currentForm = this;
        e.preventDefault();
        bootbox.confirm("Tem certeza que deseja salvar?", function(result) {
            if (result) {
                currentForm.submit();
            }
        });
    });
    $('#codcidade').select2({
        minimumInputLength: 3,
        allowClear: true,
        closeOnSelect: true,
        placeholder:'Cidade',
        formatResult: function(item) {
            var markup = "";
            markup    += item.cidade + "<span class='pull-right'>" + item.uf + "</span>";
            return markup;
        },
        formatSelection: function(item) { 
            return item.cidade; 
        },
        ajax:{
            url: baseUrl+'/cidade/ajax',
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
                url: baseUrl+'/cidade/ajax',
                data: "id=<?php if(isset($_GET['cidade'])){echo $_GET['cidade'];}?>",
                dataType: "json",
                success: function(result) { 
                    callback(result); 
                }
            });
        },
        width:'resolve'
    });      
    $('#fisica').bootstrapSwitch('state', <?php echo ($model->fisica == 1 ? 'true' : 'false'); ?>);
    $('#cliente').bootstrapSwitch('state', <?php echo ($model->cliente == 1 ? 'true' : 'false'); ?>);
    
    $('#codsexo').select2({
        placeholder: 'Sexo',
        allowClear: true,
        closeOnSelect: true
    })<?php echo (isset($model->codsexo) ? ".select2('val', $model->codsexo);" : ';');?>
    
    $('#codestadocivil').select2({
        placeholder: 'Estado Civil',
        allowClear: true,
        closeOnSelect: true
    })<?php echo (isset($model->codestadocivil) ? ".select2('val', $model->codestadocivil);" : ';');?>
    
    
    
});
</script>
@endsection