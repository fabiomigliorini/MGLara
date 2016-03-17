<div class="form-group">
    <label for="usuario" class="col-sm-2 control-label">
        {!! Form::label('Marca', 'Marca:') !!}
    </label>    
    <div class="col-md-3 col-xs-4">
        {!! Form::text('marca', null, ['class'=> 'form-control', 'id'=>'marca', 'required'=>'required']) !!}
    </div>
</div>
<div class="form-group">
    <label for="usuario" class="col-sm-2 control-label">
        {!! Form::label('Disponível no Site', 'Disponível no Site:') !!}
    </label>
    <div class="col-md-1 col-xs-4">
        {!! Form::checkbox('site', null, ['class'=> 'form-control'], ['id'=>'site']) !!}
    </div>
</div>
<div class="form-group">
    <label for="usuario" class="col-sm-2 control-label">
        {!! Form::label('Descrição Site', 'Descrição Site:') !!}
    </label>
    <div class="col-md-4 col-xs-4">
        {!! Form::textarea('descricaosite', null, ['class'=> 'form-control', 'id'=>'descricaosite', 'required'=>'required']) !!}
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
    $('#form-marca').on("submit", function(e){
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
