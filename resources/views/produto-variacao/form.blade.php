<?php

    use MGLara\Models\ProdutoEmbalagem;

?>

<div class="form-group">
    <label for="variacao" class="col-sm-2 control-label">{!! Form::label('Variaçao:') !!}</label>
    <div class="col-sm-2">{!! Form::text('variacao', null, ['class'=> 'form-control', 'id'=>'variacao']) !!}
    </div>
</div>
<div class="form-group">
    <label for="codmarca" class="col-sm-2 control-label">Marca</label>
    <div class="col-sm-2">{!! Form::select2Marca('codmarca', null, ['class' => 'form-control','id'=>'codmarca', 'style'=>'width:100%']) !!}</div>
</div>
<div class="form-group">
    <label for="referencia" class="col-sm-2 control-label">{!! Form::label('Referencia:') !!}</label>
    <div class="col-sm-2">{!! Form::text('referencia', null, ['class'=> 'form-control', 'id'=>'referencia']) !!}
    </div>
</div>
<div class="form-group">
    <label for="codprodutoimagem" class="col-sm-2 control-label">{!! Form::label('Imagem:') !!}</label>
    <div class="col-sm-6">
          <label>
            <input type="radio" name="codprodutoimagem" id="optionsRadiosNull" value="" <?php echo empty($model->codprodutoimagem)?'checked':'' ?>>
	    <img src="<?php echo URL::asset('public/imagens/semimagem.jpg') ?>" style="max-width:130px" class="img-thumbnail">
          </label>
          @foreach ($model->Produto->ProdutoImagemS()->orderBy('ordem')->get() as $pi)
              <label>
                <input type="radio" name="codprodutoimagem" id="optionsRadios{{$pi->codprodutoimagem}}" value="{{$pi->codprodutoimagem}}" <?php echo ($model->codprodutoimagem == $pi->codprodutoimagem)?'checked':'' ?>>
                <!-- {{$pi->codprodutoimagem}}/{{$pi->codimagem}} -->
                <img src="<?php echo URL::asset('public/imagens/'.$pi->Imagem->observacoes);?>" id="{{$pi->Imagem->codimagem}}" style='max-width: 130px;' class="img-thumbnail">
              </label>
          @endforeach
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
    $("#variacao").Setcase();

    $('#form-produto-variacao').on("submit", function(e) {
        var currentForm = this;
        e.preventDefault();
        bootbox.confirm("Tem certeza que deseja salvar?", function(result) {
            if (result) {
                currentForm.submit();
            }
        });
    });
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
            url: baseUrl + "/marca/listagem-json",
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
              url: baseUrl + "/marca/listagem-json",
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
