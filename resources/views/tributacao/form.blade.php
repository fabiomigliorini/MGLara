<?php
    //...
?>
<div class="form-group">
    <label for="produto" class="col-sm-2 control-label">{!! Form::label('Tributação:') !!}</label>
    <div class="col-sm-2">{!! Form::text('tributacao', null, ['class'=> 'form-control', 'id'=>'tributacao']) !!}</div>
</div>
<div class="form-group">
    <label for="produto" class="col-sm-2 control-label">{!! Form::label('Aliquota ICMS ECF:') !!}</label>
    <div class="col-sm-1">{!! Form::text('aliquotaicmsecf', null, ['class'=> 'form-control', 'id'=>'aliquotaicmsecf']) !!}</div>
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
    $('#form-tributacao').on("submit", function(e) {
        var currentForm = this;
        e.preventDefault();
        bootbox.confirm("Tem certeza que deseja salvar?", function(result) {
            if (result) {
                currentForm.submit();
            }
        });
    });
    $('#tributacao, #aliquotaicmsecf').prop('required', true);
});
</script>
@endsection