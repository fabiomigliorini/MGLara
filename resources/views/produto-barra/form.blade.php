<?php

    use MGLara\Models\ProdutoEmbalagem;
    
    $embalagens[0] = $produto->UnidadeMedida->sigla;
    
    foreach ($produto->ProdutoEmbalagemS as $pe)
        $embalagens[$pe->codprodutoembalagem] = $pe->descricao;
    
    $variacoes = $produto->ProdutoVariacaoS()->orderBy('variacao', 'ASC NULLS FIRST')->lists('variacao', 'codprodutovariacao')->all();

    foreach($variacoes as $cod => $descr)
        if (empty($descr))
            $variacoes[$cod] = '{Sem Variação}';
    
    $variacoes = ['' => ''] + $variacoes;
    
?>
<div class="form-group">
    <label for="codprodutovariacao" class="col-sm-2 control-label">{!! Form::label('Variação:') !!}</label>
    <div class="col-sm-2">{!! Form::select('codprodutovariacao', $variacoes, null, ['class'=> 'form-control', 'id' => 'codprodutovariacao', 'style'=>'width:100%']) !!}</div>
</div>
<div class="form-group">
    <label for="codprodutoembalagem" class="col-sm-2 control-label">{!! Form::label('Unidade Medida:') !!}</label>
    <div class="col-sm-2">{!! Form::select('codprodutoembalagem', $embalagens, null, ['class'=> 'form-control', 'id' => 'codprodutoembalagem', 'style'=>'width:100%']) !!}</div>
</div>
<div class="form-group">
    <label for="barras" class="col-sm-2 control-label">{!! Form::label('Barras:') !!}</label>
    <div class="col-sm-2">{!! Form::text('barras', null, ['class'=> 'form-control text-right', 'id'=>'barras']) !!}
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
    $('#codprodutoembalagem').select2({
        placeholder: 'Unidade Medida',
        allowClear: true,
        closeOnSelect: true
    });    
    $('#codprodutovariacao').select2({
        placeholder: 'Unidade Medida',
        allowClear: true,
        closeOnSelect: true
    });    
    
});
</script>
@endsection
