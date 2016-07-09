<?php

use MGLara\Models\UnidadeMedida;
use MGLara\Models\SecaoProduto;
use MGLara\Models\Tributacao;
use MGLara\Models\TipoProduto;


$tributacoes    = [''=>''] + Tributacao::lists('tributacao', 'codtributacao')->all();
$tipos          = [''=>''] + TipoProduto::lists('tipoproduto', 'codtipoproduto')->all();

?>
<div class='col-md-5'>
    <div class="form-group">
        <label for="codtipoproduto" class="col-sm-3 control-label">{!! Form::label('Tipo:') !!}</label>
        <div class="col-sm-6">{!! Form::select('codtipoproduto', $tipos, $model->codtipoproduto, ['class'=> 'form-control', 'id' => 'codtipoproduto', 'style'=>'width:100%']) !!}</div>
    </div>
    
    <div class="form-group">
        <label for="codmarca" class="col-sm-3 control-label">Marca</label>
        <div class="col-sm-5">{!! Form::select2Marca('codmarca', null, ['class' => 'form-control','id'=>'codmarca', 'style'=>'width:100%']) !!}</div>    
    </div>
    
    <div class="form-group">
        <label for="codsecaoproduto" class="col-sm-3 control-label">Seção</label>
        <div class="col-sm-6">{!! Form::select2SecaoProduto('codsecaoproduto', null, ['required' => true, 'class'=> 'form-control', 'id' => 'codsecaoproduto', 'style'=>'width:100%', 'placeholder' => 'Seção']) !!}</div>

    </div>

    <div class="form-group">
        <label for="codfamiliaproduto" class="col-sm-3 control-label">Família</label>
        <div class="col-sm-6">{!! Form::select2FamiliaProduto('codfamiliaproduto', null, ['class' => 'form-control','id'=>'codfamiliaproduto', 'style'=>'width:100%', 'placeholder' => 'Família']) !!}</div>
    </div>

    <div class="form-group">
        <label for="codgrupoproduto" class="col-sm-3 control-label">Grupo Produto</label>
        <div class="col-sm-6">{!! Form::select2GrupoProduto('codgrupoproduto', null, ['class' => 'form-control','id'=>'codgrupoproduto', 'style'=>'width:100%', 'placeholder' => 'Grupo']) !!}</div>
    </div>

    <div class="form-group">
        <label for="codsubgrupoproduto" class="col-sm-3 control-label">Sub Grupo</label>
        <div class="col-sm-6">{!! Form::select2SubGrupoProduto('codsubgrupoproduto', null, ['class' => 'form-control','id'=>'codsubgrupoproduto', 'style'=>'width:100%', 'placeholder' => 'Sub Grupo']) !!}</div>        
    </div>
    <div class="form-group">
        <label for="ncm" class="col-sm-3 control-label">{!! Form::label('NCM:') !!}</label>
        <div class="col-sm-9">{!! Form::text('codncm', null, ['class'=> 'form-control', 'id'=>'codncm']) !!}</div>
    </div>
    
    <div class="form-group">
        <label for="codtributacao" class="col-sm-3 control-label">{!! Form::label('Tributação:') !!}</label>
        <div class="col-sm-4">{!! Form::select('codtributacao', $tributacoes, $model->codtributacao, ['class'=> 'form-control', 'id'=>'codtributacao', 'style'=>'width:100%'], ['data-off-text' => 'Não', 'data-on-text' => 'Sim']) !!}</div>
    </div>

    <div class="form-group">
        <label for="codcest" class="col-sm-3 control-label">{!! Form::label('CEST:') !!}</label>
        <div class="col-sm-9">{!! Form::text('codcest', null, ['class'=> 'form-control','id'=>'codcest', 'style'=>'width:100%']) !!}</div>
    </div>

</div>
<div class='col-md-7'>
    <div class="form-group">
        <label for="produto" class="col-sm-3 control-label">{!! Form::label('Descrição:') !!}</label>
        <div class="col-sm-9" id="produto-descricao">{!! Form::text('produto', null, ['class'=> 'form-control', 'id'=>'produto']) !!}</div>
    </div>

    <div class="form-group">
        <label for="referencia" class="col-sm-3 control-label">{!! Form::label('Referência:') !!}</label>
        <div class="col-sm-5">{!! Form::text('referencia', null, ['class'=> 'form-control'], ['id'=>'referencia']) !!}</div>
    </div>

    <div class="form-group">
        <label for="preco" class="col-sm-3 control-label">{!! Form::label('Preço:') !!}</label>
        <div class="col-sm-2">{!! Form::text('preco', null, ['class'=> 'form-control text-right', 'id'=>'preco']) !!}</div>
        <div class="col-sm-3">{!! Form::select2UnidadeMedida('codunidademedida', null, ['required' => true,  'class'=> 'form-control', 'campo' => 'unidademedida', 'id' => 'codunidademedida', 'style'=>'width:100%']) !!}</div>
        
    </div>

    <div class="form-group">
        <label for="importado" class="col-sm-3 control-label">{!! Form::label('Importado:') !!}</label>
        <div class="col-sm-9" id="wrapper-importado">{!! Form::checkbox('importado', null, false,[ 'id'=>'importado', 'data-off-text' => 'Nacional', 'data-on-text' => 'Importado']) !!}</div>
    </div>


    <div class="form-group">
        <label for="site" class="col-sm-3 control-label">{!! Form::label('Disponível no Site:') !!}</label>
        <div class="col-sm-9" id="wrapper-site">{!! Form::checkbox('site', null, null, ['id'=>'site', 'data-off-text' => 'Não', 'data-on-text' => 'Sim']) !!}</div>
    </div>

    <div class="form-group">
        <label for="descricaosite" class="col-sm-3 control-label">{!! Form::label('Descrição Site:') !!}</label>
        <div class="col-sm-9">{!! Form::textarea('descricaosite', null, ['class'=> 'form-control', 'id'=>'descricaosite']) !!}</div>
    </div>
</div>
<hr>
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {!! Form::submit($submitTextButton, array('class' => 'btn btn-primary')) !!}
    </div>
</div>

@section('inscript')
<style type="text/css">
.popover {
    max-width: 100%;
    width: 70% !important;
}
.produtos-similares {
    list-style: none;
    padding: 5px 0;
}
.popover-title {
    display: none;
}   
</style>
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
    $('#produto, #codmarca, #preco, #codtributacao, #codtipoproduto, #codncm').prop('required', true);
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
            url:baseUrl+"/ncm/listagem-json",
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
                url: baseUrl+"/ncm/listagem-json",
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
            url:baseUrl+"/cest/listagem-json",
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
                url: baseUrl+"/cest/listagem-json",
                data: "id="+$('#codcest').val(),
                dataType: "json",
                success: function(result) { callback(result); }
            });
        },
        width:'resolve'
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

    if($('#codsecaoproduto').val() == '') {
        $('#codsecaoproduto').val({{$model->SubGrupoProduto->GrupoProduto->FamiliaProduto->SecaoProduto->codsecaoproduto or ''}});
    }
    

    if($('#codfamiliaproduto').val() == '') {
        $('#codfamiliaproduto').val({{ $model->SubGrupoProduto->GrupoProduto->FamiliaProduto->codfamiliaproduto or '' }});
    }

    
    if($('#codgrupoproduto').val() == '') {
        $('#codgrupoproduto').val({{ $model->SubGrupoProduto->GrupoProduto->codgrupoproduto or '' }});
    }

    function mostraPopoverDescricao(produto)
    {
        $.get(baseUrl + "/produto/descricao", 
            { 
                codsubgrupoproduto: $('#codsubgrupoproduto').val(), 
                q: produto 
            } 
        ).done(function( data ) {
            if(data.data.length > 0){
                $.each(data.data, function(k, v) {
                    $('.popover-content').prepend('<li class="produtos-similares small">'+ v.produto +'</li>');
                });
            } else {
                $('.popover-content').prepend('<p>Nenhum produto encontrado</p>');
            }

        }).fail(function(error ) {
            return console.log(error)
        });  

        $("#produto-descricao").popover({
            title: ' ', 
            content: '', 
            trigger: 'manual', 
            placement: 'bottom'
        });
        $("#produto-descricao").popover('show');
    }
    
    $('#produto').on('keyup',function() {
        if($(this).val().length > 2) {
            mostraPopoverDescricao( $(this).val() );
        } else {
            $("#produto-descricao").popover('destroy');
        }
    });

    $('#produto').on('blur',function() {
        $("#produto-descricao").popover('destroy');
    });


});
</script>
@endsection