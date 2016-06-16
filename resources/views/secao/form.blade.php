<?php
    //...
?>
<div class="form-group">
    <label for="produto" class="col-sm-2 control-label">{!! Form::label('País:') !!}</label>
    <div class="col-sm-3">{!! Form::text('pais', null, ['class'=> 'form-control', 'id'=>'pais']) !!}</div>
</div>
<div class="form-group">
    <label for="produto" class="col-sm-2 control-label">{!! Form::label('Sigla:') !!}</label>
    <div class="col-sm-1">{!! Form::text('sigla', null, ['class'=> 'form-control', 'id'=>'sigla', 'minlength'=> '2', 'maxlength'=> '2']) !!}</div>
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
    $('#form-pais').on("submit", function(e) {
        var currentForm = this;
        e.preventDefault();
        bootbox.confirm("Tem certeza que deseja salvar?", function(result) {
            if (result) {
                currentForm.submit();
            }
        });
    });
    $('#sigla, #pais').prop('required', true);
});
</script>
@endsection