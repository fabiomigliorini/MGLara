<div class="form-group">
    {!! Form::label('familiaproduto', 'Família de produto:', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-3">{!! Form::text('familiaproduto', null, ['required' => true, 'class'=> 'form-control', 'id'=>'familiaproduto']) !!}</div>
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
    $('#form-familia-produto').on("submit", function(e) {
        var currentForm = this;
        e.preventDefault();
        bootbox.confirm("Tem certeza que deseja salvar?", function(result) {
            if (result) {
                currentForm.submit();
            }
        });
    });
    $("#familiaproduto").Setcase();
});
</script>
@endsection