<?php

    use MGLara\Models\ProdutoEmbalagem;
    $medidas        = ['UN-'=>'UN-'] + ProdutoEmbalagem::unidadesMedida($request->codproduto);

?>

<div class="form-group">
    <label for="preco" class="col-sm-2 control-label">{!! Form::label('Barras:') !!}</label>
    <div class="col-sm-2">{!! Form::text('barras', null, ['class'=> 'form-control text-right', 'id'=>'barras']) !!}
    </div>
</div>
<div class="form-group">
    <label for="codunidademedida" class="col-sm-2 control-label">{!! Form::label('Unidade Medida:') !!}</label>
    <div class="col-sm-2">{!! Form::select('codunidademedida', $medidas, ['class'=> 'form-control'], ['id' => 'codunidademedida', 'style'=>'width:100%']) !!}</div>
</div>
<div class="form-group">
    <label for="variacao" class="col-sm-2 control-label">{!! Form::label('Variaçao:') !!}</label>
    <div class="col-sm-2">{!! Form::text('variacao', null, ['class'=> 'form-control text-right', 'id'=>'variacao']) !!}
    </div>
</div>
<div class="form-group">
    <label for="codmarca" class="col-sm-2 control-label">Marca</label>
    <div class="col-sm-2">{!! Form::text('codmarca', null, ['class' => 'form-control','id'=>'codmarca', 'style'=>'width:100%']) !!}</div>    
</div>
<div class="form-group">
    <label for="referencia" class="col-sm-2 control-label">{!! Form::label('Referencia:') !!}</label>
    <div class="col-sm-2">{!! Form::text('referencia', null, ['class'=> 'form-control text-right', 'id'=>'referencia']) !!}
    </div>
</div>

<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
  {!! Form::submit($submitTextButton, array('class' => 'btn btn-primary')) !!}
  </div>
</div>

@section('inscript')
<script type="text/javascript">
$(document).ready(function() {
    $('#form-produto-barra').on("submit", function(e) {
        var currentForm = this;
        e.preventDefault();
        bootbox.confirm("Tem certeza que deseja salvar?", function(result) {
            if (result) {
                currentForm.submit();
            }
        });
    });
    $('#barras').prop('required', true);
    $('#codunidademedida').select2({
        placeholder: 'Unidade Medida',
        allowClear: true,
        closeOnSelect: true
    })<?php echo (isset($model->codunidademedida) ? ".select2('val', $model->codunidademedida);" : ';');?>    
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
              data: "id="+<?php echo (isset($model->codmarca) ? $model->codmarca : "$('#codmarca').val()");?>,
              dataType: "json",
              success: function(result) { callback(result); }
              });
        },
        width: 'resolve'
    });     
    
});
</script>
@endsection
