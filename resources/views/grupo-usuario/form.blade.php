<div class="form-group">
  <label for="grupousuario" class="col-sm-2 control-label">
  	{!! Form::label('Grupo de usuário', 'Grupo de usuário:') !!}
  </label>
  <div class="col-md-2 col-xs-4">
  	{!! Form::text('grupousuario', null, ['class'=> 'form-control', 'required'=>'required'], ['id'=>'grupousuario']) !!}
  </div>
</div>

<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
  {!! Form::submit($submitTextButton, array('class' => 'btn btn-primary')) !!}
  </div>
</div>
