<?php

    use MGLara\Models\EstadoCivil;
    use MGLara\Models\Sexo;
    use MGLara\Models\GrupoCliente;
    use MGLara\Models\FormaPagamento;
    use MGLara\Models\Pessoa;

    $sexos              = [''=>''] + Sexo::lists('sexo', 'codsexo')->all();
    $estadoscivil       = [''=>''] + EstadoCivil::lists('estadocivil', 'codestadocivil')->all();
    $grupos             = [''=>''] + GrupoCliente::lists('grupocliente', 'codgrupocliente')->all();
    $formaspagamento    = [''=>''] + FormaPagamento::lists('formapagamento', 'codformapagamento')->all();
    $notafiscal         = [''=>''] + Pessoa::getNotaFiscalOpcoes();

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
    <label for="fisica" class="col-sm-2 control-label">{!! Form::label('Pessoa física:') !!}</label>
    <div class="col-sm-10">{!! Form::checkbox('fisica', null, null, ['id'=>'fisica', 'data-off-text' => 'Jurídica', 'data-on-text' => 'Física']) !!}</div>
</div>
<div class="form-group">
    <label for="cnpj" class="col-sm-2 control-label">{!! Form::label('CNPJ/CPF:') !!}</label>
    <div class="col-sm-3">{!! Form::text('cnpj', null, ['class'=> 'form-control', 'id'=>'cnpj']) !!}</div>
</div>
<div class="form-group">
    <label for="cnpj" class="col-sm-2 control-label">{!! Form::label('Inscrição Estadual:') !!}</label>
    <div class="col-sm-3">{!! Form::text('ie', null, ['class'=> 'form-control', 'id'=>'ie']) !!}</div>
</div>
<div id="pessoa-fisica" style="display: none">
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
    <div class="col-sm-10">{!! Form::checkbox('cliente', null, null, ['id'=>'cliente', 'data-off-text' => 'Não', 'data-on-text' => 'Sim']) !!}</div>
</div>
<div id="dados-cliente"  style="display: none">
    <h3>Dados Cliente</h3>
    <div class="form-group">
        <label for="codgrupocliente" class="col-sm-2 control-label">{!! Form::label('Grupo de Cliente:') !!}</label>
        <div class="col-sm-3">{!! Form::select('codgrupocliente', $grupos, ['class'=> 'form-control'], ['id' => 'codgrupocliente', 'style'=>'width:100%']) !!}</div>
        
    </div>    
    <div class="form-group">
        <label for="consumidor" class="col-sm-2 control-label">{!! Form::label('Consumidor Final:') !!}</label>
        <div class="col-sm-10">{!! Form::checkbox('consumidor', null, null, ['id'=>'consumidor', 'data-off-text' => 'Não', 'data-on-text' => 'Sim']) !!}</div>
    </div>
    <div class="form-group">
        <label for="codformapagamento" class="col-sm-2 control-label">{!! Form::label('Forma de Pagamento:') !!}</label>
        <div class="col-sm-4">{!! Form::select('codformapagamento', $formaspagamento, ['class'=> 'form-control'], ['id' => 'codformapagamento', 'style'=>'width:100%']) !!}</div>
    </div>
    <div class="form-group">
        <label for="creditobloqueado" class="col-sm-2 control-label">{!! Form::label('Credito Bloqueado:') !!}</label>
        <div class="col-sm-10">
            {!! Form::checkbox('creditobloqueado', null, null, ['id'=>'creditobloqueado', 'data-off-text' => 'Não', 'data-on-text' => 'Sim']) !!}
        </div>
    </div>
    <div class="form-group">
        <label for="credito" class="col-sm-2 control-label">{!! Form::label('Limite de Credito:') !!}</label>
        <div class="col-sm-2">
            <div class="input-group">
                <span class="input-group-addon">R$</span>  
                {!! Form::text('credito', null, ['class'=> 'form-control text-right', 'id'=>'credito']) !!}
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="toleranciaatraso" class="col-sm-2 control-label">{!! Form::label('Tolerância de Atraso:') !!}</label>
        <div class="col-sm-2">
            <div class="input-group">
                {!! Form::text('toleranciaatraso', null, ['class'=> 'form-control text-right', 'id'=>'toleranciaatraso']) !!}
                <span class="input-group-addon">Dias</span>  
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="mensagemvenda" class="col-sm-2 control-label">{!! Form::label('Mensagem de Venda:') !!}</label>
        <div class="col-sm-5">{!! Form::textarea('mensagemvenda', null, ['class'=> 'form-control', 'id'=>'mensagemvenda']) !!}</div>
    </div>
    <div class="form-group">
        <label for="desconto" class="col-sm-2 control-label">{!! Form::label('Desconto:') !!}</label>
        <div class="col-sm-2">
            <div class="input-group">
                {!! Form::text('desconto', null, ['class'=> 'form-control', 'id'=>'desconto']) !!}
                <span class="input-group-addon">%</span>              
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="notafiscal" class="col-sm-2 control-label">{!! Form::label('Nota Fiscal:') !!}</label>
        <div class="col-sm-3">{!! Form::select('notafiscal', $notafiscal, ['class'=> 'form-control'], ['id'=>'notafiscal', 'style'=>'width:100%']) !!}</div>
    </div>
</div>
<div class="form-group">
    <label for="cep" class="col-sm-2 control-label">{!! Form::label('cep', 'CEP') !!}</label>
    <div class="col-sm-2">{!! Form::text('cep', null, ['class'=> 'form-control', 'id'=>'cep']) !!}</div>
</div>  
<div class="form-group">
    <label for="codcidade" class="col-sm-2 control-label">{!! Form::label('Cidade:') !!}</label>
    <div class="col-sm-3">{!! Form::text('codcidade', null, ['class'=> 'form-control', 'id'=>'codcidade']) !!}</div>
</div>
<div class="form-group">
    <label for="endereco" class="col-sm-2 control-label">{!! Form::label('Endereço:') !!}</label>
    <div class="col-sm-3">{!! Form::text('endereco', null, ['class'=> 'form-control', 'id'=>'endereco']) !!}</div>
</div>
<div class="form-group">
    <label for="numero" class="col-sm-2 control-label">{!! Form::label('Número:') !!}</label>
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
<div class="form-group">
    <label for="cobrancanomesmoendereco" class="col-sm-2 control-label">{!! Form::label('Cobrança no Mesmo Endereço:') !!}</label>
    <div class="col-sm-10">{!! Form::checkbox('cobrancanomesmoendereco', null, null, ['id'=>'cobrancanomesmoendereco', 'data-off-text' => 'Não', 'data-on-text' => 'Sim']) !!}</div>
</div>
<div id="endereco-cobranca">
    <h3>Endereço de Cobrança</h3>
    <div class="form-group">
        <label for="cepcobranca" class="col-sm-2 control-label">{!! Form::label('CEP:') !!}</label>
        <div class="col-sm-2">{!! Form::text('cepcobranca', null, ['class'=> 'form-control', 'id'=>'cepcobranca']) !!}</div>
    </div>
    <div class="form-group">
        <label for="enderecocobranca" class="col-sm-2 control-label">{!! Form::label('Endereço:') !!}</label>
        <div class="col-sm-3">{!! Form::text('enderecocobranca', null, ['class'=> 'form-control', 'id'=>'enderecocobranca']) !!}</div>
    </div>
    <div class="form-group">
        <label for="numerocobranca" class="col-sm-2 control-label">{!! Form::label('Número:') !!}</label>
        <div class="col-sm-1">{!! Form::text('numerocobranca', null, ['class'=> 'form-control', 'id'=>'numerocobranca']) !!}</div>
    </div>
    <div class="form-group">
        <label for="complementocobranca" class="col-sm-2 control-label">{!! Form::label('Complemento:') !!}</label>
        <div class="col-sm-2">{!! Form::text('complementocobranca', null, ['class'=> 'form-control', 'id'=>'complementocobranca']) !!}</div>
    </div>
    <div class="form-group">
        <label for="bairrocobranca" class="col-sm-2 control-label">{!! Form::label('Bairro:') !!}</label>
        <div class="col-sm-2">{!! Form::text('bairrocobranca', null, ['class'=> 'form-control', 'id'=>'bairrocobranca']) !!}</div>
    </div>
    <div class="form-group">
        <label for="codcidadecobranca" class="col-sm-2 control-label">{!! Form::label('Cidade:') !!}</label>
        <div class="col-sm-3">{!! Form::text('codcidadecobranca', null, ['class'=> 'form-control', 'id'=>'codcidadecobranca']) !!}</div>
    </div>    
</div>
<div class="form-group">
    <label for="telefone1" class="col-sm-2 control-label">{!! Form::label('Telefone 1:') !!}</label>
    <div class="col-sm-2">
        <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>
            {!! Form::text('telefone1', null, ['class'=> 'form-control', 'id'=>'telefone1']) !!}
        </div>
    </div>
</div>  
<div class="form-group">
    <label for="telefone2" class="col-sm-2 control-label">{!! Form::label('Telefone 2:') !!}</label>
    <div class="col-sm-2">
        <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>
            {!! Form::text('telefone2', null, ['class'=> 'form-control', 'id'=>'telefone2']) !!}
        </div>
    </div>
</div>  
<div class="form-group">
    <label for="telefone3" class="col-sm-2 control-label">{!! Form::label('Telefone 3:') !!}</label>
    <div class="col-sm-2">
        <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>
            {!! Form::text('telefone3', null, ['class'=> 'form-control', 'id'=>'telefone3']) !!}
        </div>
    </div>
</div>  
<div class="form-group">
    <label for="email" class="col-sm-2 control-label">{!! Form::label('Email:') !!}</label>
    <div class="col-sm-3">
        <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
            {!! Form::text('email', null, ['class'=> 'form-control', 'id'=>'email']) !!}
        </div>
    </div>
</div>  
<div class="form-group">
    <label for="emailnfe" class="col-sm-2 control-label">{!! Form::label('Email para NFe:') !!}</label>
    <div class="col-sm-3">
        <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>  
            {!! Form::text('emailnfe', null, ['class'=> 'form-control', 'id'=>'emailnfe']) !!}
        </div>
    </div>
</div>  
<div class="form-group">
    <label for="emailcobranca" class="col-sm-2 control-label">{!! Form::label('Email para Cobrança:') !!}</label>
    <div class="col-sm-3">
        <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>        
            {!! Form::text('emailcobranca', null, ['class'=> 'form-control', 'id'=>'emailcobranca']) !!}
        </div>    
    </div>
</div>  
<div class="form-group">
    <label for="observacoes" class="col-sm-2 control-label">{!! Form::label('Observações:') !!}</label>
    <div class="col-sm-5">{!! Form::textarea('observacoes', null, ['class'=> 'form-control', 'id'=>'observacoes']) !!}</div>
</div>  
<div class="form-group">
    <label for="fornecedor" class="col-sm-2 control-label">{!! Form::label('Fornecedor:') !!}</label>
    <div class="col-sm-10">{!! Form::checkbox('fornecedor', null, null, ['id'=>'fornecedor', 'data-off-text' => 'Não', 'data-on-text' => 'Sim']) !!}</div>
</div>  
<div class="form-group">
    <label for="vendedor" class="col-sm-2 control-label">{!! Form::label('Vendedor:') !!}</label>
    <div class="col-sm-10">{!! Form::checkbox('vendedor', null, null, ['id'=>'vendedor', 'data-off-text' => 'Não', 'data-on-text' => 'Sim']) !!}</div>
</div>  
<div class="form-group">
    <label for="inativo" class="col-sm-2 control-label">{!! Form::label('Inativo desde:') !!}</label>
    <div class="col-sm-3">
        <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
            {!! Form::text('inativo', null, ['class'=> 'form-control text-center', 'id'=>'inativo']) !!}
        </div>
    </div> 
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
    //$('#toleranciaatraso, #numero, #email, #codcidade, #endereco, #bairro, #cep, #codcidadecobranca, #enderecocobranca, #numerocobranca, #bairrocobranca, #cepcobranca, #pessoa, #fantasia, #notafiscal, #telefone1').prop('required', true);
    $('#codcidade, #codcidadecobranca').select2({
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
            url: baseUrl+'/cidade/listagem-json',
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
                url: baseUrl+'/cidade/listagem-json',
                data: "id=<?php if(isset($_GET['cidade'])){echo $_GET['cidade'];}?>",
                dataType: "json",
                success: function(result) { 
                    callback(result); 
                }
            });
        },
        width:'resolve'
    });      
    
    $("#cnpj").mask("99.999.999/9999-99");
    $('#fisica').bootstrapSwitch('state', <?php echo ($model->fisica == 1 ? 'true' : 'false'); ?>);
    $('input[name="fisica"]').on('switchChange.bootstrapSwitch', function(event, state) {
        if (state === true) {
            $("#pessoa-fisica").slideDown( "slow" );
            $("#cnpj").mask("999.999.999-99");
        } else {
            $("#pessoa-fisica").slideUp( "slow" );
            $("#cnpj").mask("99.999.999/9999-99");
        }
    });
    
    $('#cliente').bootstrapSwitch('state', <?php echo ($model->cliente == 1 ? 'true' : 'false'); ?>);
    $('input[name="cliente"]').on('switchChange.bootstrapSwitch', function(event, state) {
        if (state === true) {
            $('#dados-cliente').slideDown('slow');
        } else {
            $('#dados-cliente').slideUp('slow');
        }
    });
    
    $('#cobrancanomesmoendereco').bootstrapSwitch('state', <?php echo ($model->enderecocobranca == 1 ? 'true' : 'false'); ?>);
    $('input[name="cobrancanomesmoendereco"]').on('switchChange.bootstrapSwitch', function(event, state) {
        if (state === true) {
            $('#endereco-cobranca').slideUp('slow');
        } else {
            $('#endereco-cobranca').slideDown('slow');
            //$('#cepcobranca, #enderecocobranca, #numerocobranca, #codcidadecobranca, #bairrocobranca').prop('required', true);
        }
    });
    
    $('#vendedor').bootstrapSwitch('state', <?php echo ($model->vendedor == 1 ? 'true' : 'false'); ?>);
    $('#fornecedor').bootstrapSwitch('state', <?php echo ($model->fornecedor == 1 ? 'true' : 'false'); ?>);
    $('#consumidor').bootstrapSwitch('state', <?php echo ($model->consumidor == 1 ? 'true' : 'false'); ?>);
    $('#creditobloqueado').bootstrapSwitch('state', <?php echo ($model->creditobloqueado == 1 ? 'true' : 'false'); ?>);
    $('#inativo').datetimepicker({
        locale: 'pt-br',
        format: 'DD/MM/YYYY HH:mm:ss'
    });    
    
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
    
    $('#codgrupocliente').select2({
        placeholder: 'Grupo Cliente',
        allowClear: true,
        closeOnSelect: true
    })<?php echo (isset($model->codgrupocliente) ? ".select2('val', $model->codgrupocliente);" : ';');?>
    
    $('#codformapagamento').select2({
        placeholder: 'Forma de Pagamento',
        allowClear: true,
        closeOnSelect: true
    })<?php echo (isset($model->codformapagamento) ? ".select2('val', $model->codformapagamento);" : ';');?>
    
    $('#notafiscal').select2({
        placeholder: 'Nota Fiscal',
        allowClear: true,
        closeOnSelect: true
    })<?php echo (isset($model->notafiscal) ? ".select2('val', $model->notafiscal);" : ';');?>
    
    $("#fantasia").Setcase();
    $("#pessoa").Setcase();
    $("#contato").Setcase();
    $("#conjuge").Setcase();

    $("#endereco").Setcase();
    $("#numero").Setcase();
    $("#complemento").Setcase();
    $("#bairro").Setcase();

    $("#enderecocobranca").Setcase();
    $("#numerocobranca").Setcase();
    $("#complementocobranca").Setcase();
    $("#bairrocobranca").Setcase();

    $('#credito').autoNumeric('init', {aSep:'.', aDec:',', altDec:'.', mDec:2 });
    $('#toleranciaatraso').autoNumeric('init', {aSep:'', aDec:',', altDec:'.', mDec:0 });
    $('#desconto').autoNumeric('init', {aSep:'.', aDec:',', altDec:'.' });    
    
    $("#cep, #cepcobranca").mask("99.999-999");
});
</script>
@endsection