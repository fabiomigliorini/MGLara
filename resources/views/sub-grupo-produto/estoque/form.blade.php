<div class="form-group">
    <label for="usuario" class="col-sm-2 control-label">
        {!! Form::label('Grupo de Produtos', 'Sub Grupo de Produtos:') !!}
    </label>
    <div class="col-md-2 col-xs-4">
        {!! Form::text('subgrupoproduto', null, ['class'=> 'form-control', 'id'=>'subgrupoproduto', 'required'=>'required']) !!}
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
    $('#form-sub-grupo-produto').on("submit", function(e){
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