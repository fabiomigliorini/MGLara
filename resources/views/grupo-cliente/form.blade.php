<?php
    //...
?>
<div class="form-group">
    <label for="produto" class="col-sm-2 control-label">{!! Form::label('Grupo de cliente:') !!}</label>
    <div class="col-sm-4">{!! Form::text('grupocliente', null, ['class'=> 'form-control', 'id'=>'grupocliente']) !!}</div>
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
    $('#form-grupo-cliente').on("submit", function(e) {
        var currentForm = this;
        e.preventDefault();
        bootbox.confirm("Tem certeza que deseja salvar?", function(result) {
            if (result) {
                currentForm.submit();
            }
        });
    });
    $('#grupocliente').prop('required', true);      
});
</script>
@endsection