<?php
    //...
?>
<div class="form-group">
    {!! Form::label('tipoproduto', 'Tipo de produto:', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-4">{!! Form::text('tipoproduto', null, ['class'=> 'form-control', 'id'=>'tipoproduto', 'required'=>'required']) !!}</div>
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
    $('#form-tipo-produto').on("submit", function(e) {
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