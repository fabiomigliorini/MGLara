<?php
    //...
?>
<div class="form-group">
    {!! Form::label('unidademedida', 'Descrição :', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-4">{!! Form::text('unidademedida', null, ['class'=> 'form-control', 'id'=>'unidademedida', 'required'=>'required']) !!}</div>
</div>
<div class="form-group">
    {!! Form::label('sigla', 'Sigla:', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-1">{!! Form::text('sigla', null, ['class'=> 'form-control', 'id'=>'sigla', 'required'=>'required']) !!}</div>
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
    $('#form-unidade-medida').on("submit", function(e) {
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