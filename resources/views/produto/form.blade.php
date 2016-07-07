<?php

use MGLara\Models\UnidadeMedida;
use MGLara\Models\SecaoProduto;
use MGLara\Models\Tributacao;
use MGLara\Models\TipoProduto;

$medidas        = [''=>''] + UnidadeMedida::orderBy('unidademedida')->lists('unidademedida', 'codunidademedida')->all();
$secoes         = [''=>''] + SecaoProduto::lists('secaoproduto', 'codsecaoproduto')->all();
$tributacoes    = [''=>''] + Tributacao::lists('tributacao', 'codtributacao')->all();
$tipos          = [''=>''] + TipoProduto::lists('tipoproduto', 'codtipoproduto')->all();

?>
<div class='col-md-5'>
    <div class="form-group">
        <label for="codtipoproduto" class="col-sm-3 control-label">{!! Form::label('Tipo:') !!}</label>
        <div class="col-sm-9">{!! Form::select('codtipoproduto', $tipos, $model->codtipoproduto, ['class'=> 'form-control', 'id' => 'codtipoproduto', 'style'=>'width:100%']) !!}</div>
    </div>
    
    <div class="form-group">
        <label for="codmarca" class="col-sm-3 control-label">Marca</label>
        <div class="col-sm-9">@include('includes.select2.marca', ['inativo' => '1'])</div>    
    </div>

    <div class="form-group">
        <label for="codsecaoproduto" class="col-sm-3 control-label">Seção</label>
        <div class="col-sm-9">{!! Form::select('codsecaoproduto', $secoes, null, ['class'=> 'form-control', 'id' => 'codsecaoproduto', 'style'=>'width:100%']) !!}</div>
    </div>

    <div class="form-group">
        <label for="codfamiliaproduto" class="col-sm-3 control-label">Família</label>
        <div class="col-sm-9">{!! Form::text('codfamiliaproduto', null, ['class'=> 'form-control', 'id' => 'codfamiliaproduto', 'style'=>'width:100%']) !!}</div>
    </div>

    <div class="form-group">
        <label for="codgrupoproduto" class="col-sm-3 control-label">Grupo Produto</label>
        <div class="col-sm-9">{!! Form::text('codgrupoproduto', null, ['class'=> 'form-control', 'id' => 'codgrupoproduto', 'style'=>'width:100%']) !!}</div>
    </div>

    <div class="form-group">
        <label for="codsubgrupoproduto" class="col-sm-3 control-label">Sub Grupo</label>
        <div class="col-sm-9">{!! Form::text('codsubgrupoproduto', null, ['class'=> 'form-control', 'id' => 'codsubgrupoproduto', 'style'=>'width:100%']) !!}</div>
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
        <div class="col-sm-3">{!! Form::select('codunidademedida', $medidas, $model->codunidademedida, ['class'=> 'form-control', 'id' => 'codunidademedida', 'style'=>'width:100%']) !!}</div>
        
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
</style>
<script type="text/javascript">
$(document).ready(function() {
    function pegaSubgrupos() {
        return $.get(baseUrl + '/sub-grupo-produto/ajax', { codfamiliaproduto: $(this).val() }, 
            function(data) {
                var model = $('#codsubgrupoproduto');
                model.empty();
                $.each(data, function(key, value) {
                    model.append("<option value='"+ value.id +"'>" + value.subgrupoproduto + "</option>");
                });
            });
    }
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

    if($('#codsecaoproduto').val() == '') {
        $('#codsecaoproduto').val({{$model->SubGrupoProduto->GrupoProduto->FamiliaProduto->SecaoProduto->codsecaoproduto or ''}});
    }
    $('#codsecaoproduto').select2({
        placeholder: 'Seção',
        allowClear: true,
        closeOnSelect: true,
    });

    if($('#codfamiliaproduto').val() == '') {
        $('#codfamiliaproduto').val({{ $model->SubGrupoProduto->GrupoProduto->FamiliaProduto->codfamiliaproduto or '' }});
    }

    $('#codfamiliaproduto').select2({
        minimumInputLength:0,
        allowClear:true,
        closeOnSelect:true,
        placeholder:'Família',
        formatResult:function(item) {
            var markup = "<div class='row-fluid'>";
            markup    += item.familiaproduto;
            markup    += "</div>";
            return markup;
        },
        formatSelection:function(item) { 
            return item.familiaproduto; 
        },
        ajax:{
            url:baseUrl+"/familia-produto/ajax",
            dataType:'json',
            quietMillis:500,
            data:function(term, codsecaoproduto, page) { 
                return {
                    q: term,
                    codsecaoproduto: $('#codsecaoproduto').val()
                }; 
            },
            results:function(data,page) {
                var more = (page * 20) < data.total;
                return {results: data.items};
            }
        },
        initSelection:function (element, callback) {
            $.ajax({
                type: "GET",
                url: baseUrl+"/familia-produto/ajax",
                data: "id="+$('#codfamiliaproduto').val(),
                dataType: "json",
                success: function(result) { callback(result); }
            });
        },
        width:'resolve'
    });
    
    if($('#codgrupoproduto').val() == '') {
        $('#codgrupoproduto').val({{ $model->SubGrupoProduto->GrupoProduto->codgrupoproduto or '' }});
    }

    $('#codgrupoproduto').select2({
        minimumInputLength:0,
        allowClear:true,
        closeOnSelect:true,
        placeholder:'Grupo',
        formatResult:function(item) {
            var markup = "<div class='row-fluid'>";
            markup    += item.grupoproduto;
            markup    += "</div>";
            return markup;
        },
        formatSelection:function(item) { 
            return item.grupoproduto; 
        },
        ajax:{
            url:baseUrl+"/grupo-produto/ajax",
            dataType:'json',
            quietMillis:500,
            data:function(term, codfamiliaproduto, page) { 
                return {
                    q: term,
                    codfamiliaproduto: $('#codfamiliaproduto').val()
                }; 
            },
            results:function(data,page) {
                var more = (page * 20) < data.total;
                return {results: data.items};
            }
        },
        initSelection:function (element, callback) {
            $.ajax({
                type: "GET",
                url: baseUrl+"/grupo-produto/ajax",
                data: "id="+$('#codgrupoproduto').val(),
                dataType: "json",
                success: function(result) { callback(result); }
            });
        },
        width:'resolve'
    });

    $('#codsubgrupoproduto').select2({
        minimumInputLength:0,
        allowClear:true,
        closeOnSelect:true,
        placeholder:'Sub Grupo',
        formatResult:function(item) {
            var markup = "<div class='row-fluid'>";
            markup    += item.subgrupoproduto;
            markup    += "</div>";
            return markup;
        },
        formatSelection:function(item) { 
            return item.subgrupoproduto; 
        },
        ajax:{
            url:baseUrl+"/sub-grupo-produto/ajax",
            dataType:'json',
            quietMillis:500,
            data:function(term, codgrupoproduto, page) { 
                return {
                    q: term,
                    codgrupoproduto: $('#codgrupoproduto').val()
                }; 
            },
            results:function(data,page) {
                var more = (page * 20) < data.total;
                return {results: data.items};
            }
        },
        initSelection:function (element, callback) {
            $.ajax({
                type: "GET",
                url: baseUrl+"/sub-grupo-produto/ajax",
                data: "id="+$('#codsubgrupoproduto').val(),
                dataType: "json",
                success: function(result) { callback(result); }
            });
        },
        width:'resolve'
    });

    var limpaSecaoProduto = function(){
        $('#codfamiliaproduto').select2('val', null);
        $('#codgrupoproduto').select2('val', null);
        $('#codsubgrupoproduto').select2('val', null);        
    }
    var limpaFamiliaProduto = function(){
        $('#codgrupoproduto').select2('val', null);
        $('#codsubgrupoproduto').select2('val', null);        
    }

    var limpaGrupoProduto = function () {
        $('#codsubgrupoproduto').select2('val', null);
    }
    var delay = (function(){
        var timer = 0;
        return function(callback, ms){
            clearTimeout (timer);
            timer = setTimeout(callback, ms);
        };
    })();

    $("#codsecaoproduto").on("select2-removed", function(e) {
        limpaSecaoProduto;
    }).change(limpaSecaoProduto);

    $("#codfamiliaproduto").on("select2-removed", function(e) {
        limpaFamiliaProduto
    }).change(limpaFamiliaProduto);

    $('#codgrupoproduto').on("select2-removed", function(e) { 
        limpaGrupoProduto
    }).change(limpaGrupoProduto);  

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
                    $('.popover-content').prepend('<li class="produtos-similares">'+ v.produto +'</li>');
                });
            } else {
                $('.popover-content').prepend('<p>Nenhum produto encontrado</p>');
            }

        }).fail(function(error ) {
            return console.log(error)
        });  

        $("#produto-descricao").popover({
            title: 'Produtos similares', 
            content: '', 
            trigger: 'manual', 
            placement: 'bottom'
        });
        $("#produto-descricao").popover('show');
    }
    
    $('#produto').on('keyup',function() {
        //delay(function(){
            if($(this).val().length > 2) {
                mostraPopoverDescricao($(this).val());
            } else {
                $("#produto-descricao").popover('destroy');
            }
        //}, 1000 );
    });

});
</script>
@endsection