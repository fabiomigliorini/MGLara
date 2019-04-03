<?php

    use MGLara\Models\UnidadeMedida;
    $medidas        = [''=>''] + UnidadeMedida::orderBy('unidademedida')->lists('unidademedida', 'codunidademedida')->all();

?>

<div class="form-group">
    <label for="codunidademedida" class="col-sm-2 control-label">{!! Form::label('Unidade Medida:') !!}</label>
    <div class="col-sm-1">{!! Form::select2UnidadeMedida('codunidademedida', null, ['class'=> 'form-control', 'required'=>true, 'id' => 'codunidademedida', 'id' => 'codunidademedida']) !!}</div>
</div>

<div class="form-group">
    <label for="preco" class="col-sm-2 control-label">{!! Form::label('Quantidade:') !!}</label>
    <div class="col-sm-1">{!! Form::number('quantidade', null, ['class'=> 'form-control text-right', 'step'=>'0.0000000001', 'required'=>true, 'id'=>'quantidade']) !!}
    </div>
</div>

<div class="form-group">
    <label for="preco" class="col-sm-2 control-label">{!! Form::label('Preço:') !!}</label>
    <div class="col-sm-1">{!! Form::number('preco', null, ['class'=> 'form-control text-right', 'step'=>'0.01', 'id'=>'preco']) !!}
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
});
</script>
@endsection
