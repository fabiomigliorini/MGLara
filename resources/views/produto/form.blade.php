<?php
use MGLara\Models\UnidadeMedida;
use MGLara\Models\SubGrupoProduto;
use MGLara\Models\Tributacao;
use MGLara\Models\TipoProduto;

$medidas        = [''=>''] + UnidadeMedida::lists('unidademedida', 'codunidademedida')->all();
$grupos         = [''=>''] + SubGrupoProduto::lists('subgrupoproduto', 'codsubgrupoproduto')->all();
$tributacoes    = [''=>''] + Tributacao::lists('tributacao', 'codtributacao')->all();
$tipos          = [''=>''] + TipoProduto::lists('tipoproduto', 'codtipoproduto')->all();

?>
<div class="form-group">
    <label for="produto" class="col-sm-2 control-label">{!! Form::label('Descrição:') !!}</label>
    <div class="col-sm-6">{!! Form::text('produto', null, ['class'=> 'form-control'], ['id'=>'produto']) !!}</div>
  </div>

<div class="form-group">
    <label for="referencia" class="col-sm-2 control-label">{!! Form::label('Referência:') !!}</label>
    <div class="col-sm-2">{!! Form::text('referencia', null, ['class'=> 'form-control'], ['id'=>'referencia']) !!}</div>
</div>

<div class="form-group">
    <label for="codunidademedida" class="col-sm-2 control-label">{!! Form::label('Unidade Medida:') !!}</label>
    <div class="col-sm-2">{!! Form::select('codunidademedida', $medidas, ['class'=> 'form-control'], ['id' => 'codunidademedida', 'style'=>'width:100%']) !!}</div>
</div>

<div class="form-group">
    <label for="codsubgrupoproduto" class="col-sm-2 control-label">{!! Form::label('Grupo:') !!}</label>
    <div class="col-sm-3">{!! Form::select('codsubgrupoproduto', $grupos, ['class'=> 'form-control'], ['id'=>'codsubgrupoproduto', 'style'=>'width:100%']) !!}</div>
</div>

<div class="form-group">
    <label for="codmarca" class="col-sm-2 control-label">Marca</label>
    <div class="col-sm-2">
        {!! Form::text('codmarca', null, ['class' => 'form-control','id'=>'codmarca', 'style'=>'width:100%']) !!}
    </div>    
</div>

<div class="form-group">
    <label for="preco" class="col-sm-2 control-label">{!! Form::label('Preço:') !!}</label>
    <div class="col-sm-2">{!! Form::text('preco', null, ['class'=> 'form-control text-right', 'id'=>'preco']) !!}
    </div>
</div>

<div class="form-group">
    <label for="importado" class="col-sm-2 control-label">{!! Form::label('Importado:') !!}</label>
    <div class="col-sm-10" id="wrapper-importado">{!! Form::checkbox('importado', $model->importado, null, [ 'id'=>'importado', 'data-off-text' => 'Nacional', 'data-on-text' => 'Importado']) !!}</div>
</div>

<div class="form-group">
    <label for="ncm" class="col-sm-2 control-label">{!! Form::label('NCM:') !!}</label>
    <div class="col-sm-5">{!! Form::text('codncm', null, ['class'=> 'form-control', 'id'=>'codncm']) !!}</div>
</div>

<div class="form-group">
    <label for="codtributacao" class="col-sm-2 control-label">{!! Form::label('Tributação:') !!}</label>
    <div class="col-sm-2">{!! Form::select('codtributacao', $tributacoes, ['class'=> 'form-control'], ['id'=>'codtributacao', 'style'=>'width:100%'], ['data-off-text' => 'Não', 'data-on-text' => 'Sim']) !!}</div>
</div>

<div class="form-group">
    <label for="codcest" class="col-sm-2 control-label">{!! Form::label('CEST:') !!}</label>
    <div class="col-sm-5">{!! Form::text('codcest', null, ['class'=> 'form-control','id'=>'codcest', 'style'=>'width:100%']) !!}</div>
</div>

<div class="form-group">
    <label for="codtipoproduto" class="col-sm-2 control-label">{!! Form::label('Tipo:') !!}</label>
    <div class="col-sm-3">{!! Form::select('codtipoproduto', $tipos, ['class'=> 'form-control'], ['id' => 'codtipoproduto', 'style'=>'width:100%']) !!}</div>
</div>

<div class="form-group">
    <label for="site" class="col-sm-2 control-label">{!! Form::label('Disponível no Site:') !!}</label>
    <div class="col-sm-10" id="wrapper-site">{!! Form::checkbox('site', $model->site, null, ['id'=>'site', 'data-off-text' => 'Não', 'data-on-text' => 'Sim']) !!}</div>
</div>

<div class="form-group">
    <label for="descricaosite" class="col-sm-2 control-label">{!! Form::label('Descrição Site:') !!}</label>
    <div class="col-sm-6">{!! Form::textarea('descricaosite', null, ['class'=> 'form-control', 'id'=>'descricaosite']) !!}</div>
</div>

<div class="form-group">
    <label for="inativo" class="col-sm-2 control-label">{!! Form::label('Inativo desde:') !!}</label>
    <div class="col-sm-2">{!! Form::text('inativo', null, ['class'=> 'form-control', 'id'=>'inativo']) !!}</div>
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
    $('#importado, #site').bootstrapSwitch();
    $('#codtributacao').select2({
        placeholder: 'Tributação'
    })<?php echo (isset($model->codtributacao) ? ".select2('val', $model->codtributacao);" : ';');?>
    $('#codtipoproduto').select2({
        placeholder: 'Tipo',
        allowClear: true,
        closeOnSelect: true
    })<?php echo (isset($model->codtipoproduto) ? ".select2('val', $model->codtipoproduto);" : ';');?>
    $('#codsubgrupoproduto').select2({
        placeholder: 'Grupo',
        allowClear: true,
        closeOnSelect: true
    })<?php echo (isset($model->codsubgrupoproduto) ? ".select2('val', $model->codsubgrupoproduto);" : ';');?>
    $('#codunidademedida').select2({
        placeholder: 'Unidade Medida',
        allowClear: true,
        closeOnSelect: true
    })<?php echo (isset($model->codunidademedida) ? ".select2('val', $model->codunidademedida);" : ';');?>
    $('#inativo').datetimepicker({
        locale: 'pt-br',
        format: 'DD/MM/YYYY'
    });
    $("#inativo").val("<?php echo formataData($model->inativo, 'L');?>").change();
    $("#produto").Setcase();
    $('#preco').autoNumeric('init', {aSep:'.', aDec:',', altDec:'.', mDec:2 });

    $('#codmarca').select2({
        placeholder:'Marca',
        'minimumInputLength':1,
      'allowClear':true,
      'closeOnSelect':true,
      
      'formatResult':function(item) {
        var markup = "<div class='row-fluid'>";
        markup    += item.marca;
        markup    += "</div>";
        return markup;
      },'formatSelection':function(item) { 
        return item.marca; 
      },
      'ajax':{
        'url':baseUrl + "/marca/ajax",
        'dataType':'json',
        'quietMillis':500,
        'data':function(term,page) { 
          return {q: term}; 
        },
        'results':function(data,page) {
          var more = (page * 20) < data.total;
          return {results: data.items};
        }},
        'initSelection':function (element, callback) {
            $.ajax({
              type: "GET",
              url: baseUrl + "/marca/ajax",
              data: "id=<?php echo $model->codmarca;?>",
              dataType: "json",
              success: function(result) { callback(result); }
              });
        },
        'width':'resolve'
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
                return {results: data.data};
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
});
</script>
@endsection