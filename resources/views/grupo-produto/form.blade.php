<div class="form-group">
    <label for="usuario" class="col-sm-2 control-label">
        {!! Form::label('Grupo de Produtos', 'Grupo de Produtos:') !!}
    </label>
    <div class="col-md-4 col-xs-4">
        {!! Form::text('grupoproduto', null, ['class'=> 'form-control', 'id'=>'grupoproduto', 'required'=>'required']) !!}
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
    $('#form-grupo-produto').on("submit", function(e){
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