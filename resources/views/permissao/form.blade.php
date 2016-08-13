<div class="form-group">
  <label for="observacoes" class="col-sm-2 control-label">
    {!! Form::label('Código', 'Código:') !!}
  </label>
  <div class="col-md-2 col-xs-4">
    {!! Form::text('codpermissao', null, ['class'=> 'form-control', 'required'=>'required'], ['id'=>'codpermissao']) !!}
  </div>
</div>

<div class="form-group">
  <label for="permissao" class="col-sm-2 control-label">
  	{!! Form::label('Permissão', 'Permissão:') !!}
  </label>
  <div class="col-md-2 col-xs-4">
  	{!! Form::text('permissao', null, ['class'=> 'form-control', 'required'=>'required'], ['id'=>'permissao']) !!}
  </div>
</div>

<div class="form-group">
  <label for="observacoes" class="col-sm-2 control-label">
  	{!! Form::label('Observações', 'Observações:') !!}
  </label>
  <div class="col-md-2 col-xs-4">
  	{!! Form::text('observacoes', null, ['class'=> 'form-control', 'id'=>'observacoes', 'required'=>'required']) !!}
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
    $('#form-permissao').on("submit", function(e){
        var currentForm = this;
        e.preventDefault();
        bootbox.confirm("Tem certeza que deseja salvar?", function(result) {
            if (result) {
                currentForm.submit();
            }
        });
    });
    $('#observacoes').Setcase();     
});
</script>
@endsection