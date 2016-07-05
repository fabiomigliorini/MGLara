<?php

    use MGLara\Models\UnidadeMedida;
    $medidas        = [''=>''] + UnidadeMedida::orderBy('unidademedida')->lists('unidademedida', 'codunidademedida')->all();

?>

<div class="form-group">
    <label for="codunidademedida" class="col-sm-2 control-label">{!! Form::label('Unidade Medida:') !!}</label>
    <div class="col-sm-2">{!! Form::select('codunidademedida', $medidas, null, ['class'=> 'form-control', 'id' => 'codunidademedida', 'style'=>'width:100%']) !!}</div>
</div>

<div class="form-group">
    <label for="preco" class="col-sm-2 control-label">{!! Form::label('Quantidade:') !!}</label>
    <div class="col-sm-1">{!! Form::text('quantidade', null, ['class'=> 'form-control text-right', 'id'=>'quantidade']) !!}
    </div>
</div>

<div class="form-group">
    <label for="preco" class="col-sm-2 control-label">{!! Form::label('Preço:') !!}</label>
    <div class="col-sm-1">{!! Form::text('preco', null, ['class'=> 'form-control text-right', 'id'=>'preco']) !!}
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
    $('#form-produto-embalagem').on("submit", function(e) {
        var currentForm = this;
        e.preventDefault();
        bootbox.confirm("Tem certeza que deseja salvar?", function(result) {
            if (result) {
                currentForm.submit();
            }
        });
    });
    $('#quantidade, #codunidademedida').prop('required', true);
    $('#preco').autoNumeric('init', {aSep:'.', aDec:',', altDec:'.', mDec:2 });
    $('#quantidade').autoNumeric('init', {aSep:'.', aDec:',', altDec:'.', mDec:3 });
    $('#codunidademedida').select2({
        placeholder: 'Unidade Medida',
        allowClear: true,
        closeOnSelect: true
    });
    
});
</script>
@endsection
