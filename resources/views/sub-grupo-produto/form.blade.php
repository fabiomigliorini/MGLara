<div class="form-group">
    {!! Form::label('subgrupoproduto', 'Sub Grupo de Produtos:', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-md-4 col-xs-4">
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

    $('#subgrupoproduto').Setcase();  
});
</script>
@endsection