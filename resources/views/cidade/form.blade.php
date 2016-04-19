<?php
    //...
?>
<div class="form-group">
    <label for="produto" class="col-sm-2 control-label">{!! Form::label('Cidade:') !!}</label>
    <div class="col-sm-3">{!! Form::text('cidade', null, ['class'=> 'form-control', 'id'=>'cidade']) !!}</div>
</div>
<div class="form-group">
    <label for="produto" class="col-sm-2 control-label">{!! Form::label('Sigla:') !!}</label>
    <div class="col-sm-1">{!! Form::text('sigla', null, ['class'=> 'form-control', 'id'=>'sigla', 'minlength'=> '2', 'maxlength'=> '2']) !!}</div>
</div>
<div class="form-group">
    <label for="produto" class="col-sm-2 control-label">{!! Form::label('Código:') !!}</label>
    <div class="col-sm-2">{!! Form::text('codigooficial', null, ['class'=> 'form-control', 'id'=>'codigooficial']) !!}</div>
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
    $('#form-cidade').on("submit", function(e) {
        var currentForm = this;
        e.preventDefault();
        bootbox.confirm("Tem certeza que deseja salvar?", function(result) {
            if (result) {
                currentForm.submit();
            }
        });
    });
    $('#sigla, #estado, #codigooficial').prop('required', true);
    $('#codigooficial').autoNumeric('init', {mDec:0, aSep:'' });
});
</script>
@endsection