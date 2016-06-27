<?php

use MGLara\Models\UnidadeMedida;
use MGLara\Models\FamiliaProduto;
use MGLara\Models\SubGrupoProduto;
use MGLara\Models\Tributacao;
use MGLara\Models\TipoProduto;

$medidas        = [''=>''] + UnidadeMedida::lists('unidademedida', 'codunidademedida')->all();
//$grupos         = [''=>''] + SubGrupoProduto::select2();
$secoes         = [''=>''] + FamiliaProduto::select2();
$tributacoes    = [''=>''] + Tributacao::lists('tributacao', 'codtributacao')->all();
$tipos          = [''=>''] + TipoProduto::lists('tipoproduto', 'codtipoproduto')->all();

?>
<div class="form-group">
    <label for="codmarca" class="col-sm-2 control-label">Marca</label>
    <div class="col-sm-2">{!! Form::text('codmarca', null, ['class' => 'form-control','id'=>'codmarca', 'style'=>'width:100%']) !!}</div>    
</div>

<div class="form-group">
    <label for="codfamiliaproduto" class="col-sm-2 control-label">{!! Form::label('Seção:') !!}</label>
    <div class="col-sm-4">{!! Form::select('codfamiliaproduto', $secoes, null, ['class'=> 'form-control', 'id' => 'codfamiliaproduto', 'style'=>'width:100%']) !!}</div>
</div>

<div class="form-group">
    <label for="codsubgrupoproduto" class="col-sm-2 control-label">{!! Form::label('Grupo:') !!}</label>
    <div class="col-sm-5">
    <select id="codsubgrupoproduto" name="codsubgrupoproduto" class="form-control">
        <option>Selecione</option>
    </select>   
    </div> 
</div>

<div class="form-group">
    <label for="produto" class="col-sm-2 control-label">{!! Form::label('Descrição:') !!}</label>
    <div class="col-sm-6">{!! Form::text('produto', null, ['class'=> 'form-control', 'id'=>'produto']) !!}</div>
</div>

<div class="form-group">
    <label for="referencia" class="col-sm-2 control-label">{!! Form::label('Referência:') !!}</label>
    <div class="col-sm-2">{!! Form::text('referencia', null, ['class'=> 'form-control'], ['id'=>'referencia']) !!}</div>
</div>

<div class="form-group">
    <label for="codunidademedida" class="col-sm-2 control-label">{!! Form::label('Unidade Medida:') !!}</label>
    <div class="col-sm-2">{!! Form::select('codunidademedida', $medidas, $model->codunidademedida, ['class'=> 'form-control', 'id' => 'codunidademedida', 'style'=>'width:100%']) !!}</div>
</div>

<div class="form-group">
    <label for="preco" class="col-sm-2 control-label">{!! Form::label('Preço:') !!}</label>
    <div class="col-sm-2">{!! Form::text('preco', null, ['class'=> 'form-control text-right', 'id'=>'preco']) !!}
    </div>
</div>

<div class="form-group">
    <label for="importado" class="col-sm-2 control-label">{!! Form::label('Importado:') !!}</label>
    <div class="col-sm-10" id="wrapper-importado">{!! Form::checkbox('importado', null, false,[ 'id'=>'importado', 'data-off-text' => 'Nacional', 'data-on-text' => 'Importado']) !!}</div>
</div>

<div class="form-group">
    <label for="ncm" class="col-sm-2 control-label">{!! Form::label('NCM:') !!}</label>
    <div class="col-sm-5">{!! Form::text('codncm', null, ['class'=> 'form-control', 'id'=>'codncm']) !!}</div>
</div>

<div class="form-group">
    <label for="codtributacao" class="col-sm-2 control-label">{!! Form::label('Tributação:') !!}</label>
    <div class="col-sm-2">{!! Form::select('codtributacao', $tributacoes, $model->codtributacao, ['class'=> 'form-control', 'id'=>'codtributacao', 'style'=>'width:100%'], ['data-off-text' => 'Não', 'data-on-text' => 'Sim']) !!}</div>
</div>

<div class="form-group">
    <label for="codcest" class="col-sm-2 control-label">{!! Form::label('CEST:') !!}</label>
    <div class="col-sm-5">{!! Form::text('codcest', null, ['class'=> 'form-control','id'=>'codcest', 'style'=>'width:100%']) !!}</div>
</div>

<div class="form-group">
    <label for="codtipoproduto" class="col-sm-2 control-label">{!! Form::label('Tipo:') !!}</label>
    <div class="col-sm-3">{!! Form::select('codtipoproduto', $tipos, $model->codtipoproduto, ['class'=> 'form-control', 'id' => 'codtipoproduto', 'style'=>'width:100%']) !!}</div>
</div>

<div class="form-group">
    <label for="site" class="col-sm-2 control-label">{!! Form::label('Disponível no Site:') !!}</label>
    <div class="col-sm-10" id="wrapper-site">{!! Form::checkbox('site', null, null, ['id'=>'site', 'data-off-text' => 'Não', 'data-on-text' => 'Sim']) !!}</div>
</div>

<div class="form-group">
    <label for="descricaosite" class="col-sm-2 control-label">{!! Form::label('Descrição Site:') !!}</label>
    <div class="col-sm-6">{!! Form::textarea('descricaosite', null, ['class'=> 'form-control', 'id'=>'descricaosite']) !!}</div>
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
    $('#form-produto').on("submit", function(e) {
        var currentForm = this;
        e.preventDefault();
        bootbox.confirm("Tem certeza que deseja salvar?", function(result) {
            if (result) {
                currentForm.submit();
            }
        });
    });
    $('#produto, #codunidademedida, #codsubgrupoproduto, #codmarca, #preco, #codtributacao, #codtipoproduto, #codncm').prop('required', true);
    $('#importado').bootstrapSwitch('state', <?php echo ($model->importado == 1 ? 'true' : 'false'); ?>);
    $('#site').bootstrapSwitch('state', <?php echo ($model->site == 1 ? 'true' : 'false'); ?>);
    $('input[name="site"]').on('switchChange.bootstrapSwitch', function(event, state) {
        var valor;
        if (state === true) {
          valor = 1;
        } else {
          valor = 0;
        }
        $('#site').val(valor);
    });
    $('input[name="importado"]').on('switchChange.bootstrapSwitch', function(event, state) {
        var valor;
        if (state === true) {
          valor = 1;
        } else {
          valor = 0;
        }
        $('#importado').val(valor);
    });
    $('#inativo').datetimepicker({
        locale: 'pt-br',
        format: 'DD/MM/YYYY'
    });
    <?php if($model->inativo):?>$('#inativo').val({{ formatadata($model->inativo)}}).change();<?php endif;?>
    $("#produto").Setcase();
    $('#preco').autoNumeric('init', {aSep:'.', aDec:',', altDec:'.', mDec:2 });

    $('#codmarca').select2({
        placeholder:'Marca',
        minimumInputLength: 1,
        allowClear: true,
        closeOnSelect: true,
      
        formatResult: function(item) {
            var markup = "<div class='row-fluid'>";
            markup    += item.marca;
            markup    += "</div>";
            return markup;
        },
        formatSelection: function(item) { 
            return item.marca; 
        },
        ajax:{
            url: baseUrl + "/marca/ajax",
            dataType: 'json',
            quietMillis: 500,
            data: function(term,page) { 
            return {q: term}; 
        },
        results: function(data,page) {
            var more = (page * 20) < data.total;
            return {results: data.items};
        }},
        initSelection: function (element, callback) {
            $.ajax({
              type: "GET",
              url: baseUrl + "/marca/ajax",
              data: "id="+$('#codmarca').val(),
              dataType: "json",
              success: function(result) { callback(result); }
              });
        },
        width: 'resolve'
    }); 
    
    $('#codncm').select2({
        minimumInputLength:1,
        allowClear:true,
        closeOnSelect:true,
        placeholder:'NCM',
        formatResult:function(item) {
            var markup = "";
            markup    += "<b>" + item.ncm + "</b>&nbsp;";
            markup    += "<span>" + item.descricao + "</span>";
            return markup;
        },
        formatSelection:function(item) { 
            return item.ncm + "&nbsp;" + item.descricao; 
        },
        ajax:{
            url:baseUrl+"/ncm/ajax",
            dataType:'json',
            quietMillis:500,
            data:function(term, page) { 
                return {q: term}; 
            },
            results:function(data, page) {
                var more = (page * 20) < data.total;
                return {results: data.data};
            }
        },
        initSelection:function (element, callback) {
            $.ajax({
                type: "GET",
                url: baseUrl+"/ncm/ajax",
                data: "id="+$('#codncm').val(),
                dataType: "json",
                success: function(result) { callback(result); }
            });
        },
        width:'resolve'
    });
    
    $('#codcest').select2({
        minimumInputLength:0,
        allowClear:true,
        closeOnSelect:true,
        placeholder:'CEST',
        formatResult: function(item) {
            var markup = "";
            markup    += "<b>" + item.ncm + "</b>/";
            markup    += "<b>" + item.cest + "</b>&nbsp;";
            markup    += "<span>" + item.descricao + "</span>";
            return markup;
        },
        formatSelection: function(item) { 
                return item.ncm + "/" + item.cest + "&nbsp;" + item.descricao; 
        },
        ajax:{
            url:baseUrl+"/cest/ajax",
            dataType:'json',
            quietMillis:500,
            data:function(codncm, page) { 
                return {codncm: $('#codncm').val()}; 
            },
            results:function(data, page) {
                var more = (page * 20) < data.total;
                return {results: data};
            }
        },
        initSelection:function (element, callback) {
            $.ajax({
                type: "GET",
                url: baseUrl+"/cest/ajax",
                data: "id="+$('#codcest').val(),
                dataType: "json",
                success: function(result) { callback(result); }
            });
        },
        width:'resolve'
    });      
    
    $('#codunidademedida').select2({
        placeholder: 'Unidade Medida',
        allowClear: true,
        closeOnSelect: true
    });
    $('#codtributacao').select2({
        placeholder: 'Tributação',
        allowClear: true,
        closeOnSelect: true
    });

    $('#codtipoproduto').select2({
        placeholder: 'Tipo',
        allowClear: true,
        closeOnSelect: true
    });

    $('#codfamiliaproduto').select2({
        placeholder: 'Seção',
        allowClear: true,
        closeOnSelect: true
    });

    $('#codsubgrupoproduto').select2({
        placeholder: 'Grupo',
        allowClear: true,
        closeOnSelect: true
    })<?php echo (isset($model->codsubgrupoproduto) ? ".select2('val', $model->SubGrupoProduto->GrupoProduto->FamiliaProduto->codfamiliaproduto);" : ';');?>
    
    $('#codfamiliaproduto').change(function(){
        $.get(baseUrl + '/sub-grupo-produto/ajax', 
            { 
                codfamiliaproduto: $(this).val() 
            }, 
            function(data) {
                var model = $('#codsubgrupoproduto');
                model.empty();
                //console.log(data);
                $.each(data, function(key, value) {
                    model.append("<option value='"+ value.id +"'>" + value.subgrupoproduto + "</option>");
                });
            });
    });



    /*
    $('#codsubgrupoproduto').select2({
        minimumInputLength:0,
        allowClear:true,
        closeOnSelect:true,
        placeholder:'Grupo/Sub Grupo',
        formatResult: function(item) {
            var markup = "";
            markup    += "<span>" + item.subgrupoproduto + "</span>";
            return markup;
        },
        formatSelection: function(item) { 
            return item.subgrupoproduto;
        },
        ajax:{
            url:baseUrl+"/sub-grupo-produto/ajax",
            dataType:'json',
            quietMillis:500,
            data:function(term, codfamiliaproduto) { 
                return {
                    q:term,
                    codfamiliaproduto: $('#codfamiliaproduto').val()
                }; 
            },
            results:function(data) {
                return {results: data.items};
            }
        },
        initSelection:function (element, callback) {
            $.ajax({
                type: "GET",
                url: baseUrl+"/sub-grupo-produto/ajax",
                data: "id=<?php if(isset($model)) echo $model->codsubgrupoproduto;?>",
                dataType: "json",
                success: function(result) { callback(result); }
            });
        },
        width:'resolve'
    });      
    */

    
});
</script>
@endsection