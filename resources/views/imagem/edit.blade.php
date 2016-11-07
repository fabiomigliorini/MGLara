@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">Enviar imagem</ol>
<hr>TESTE
<?php
$id = 'cod'.strtolower($request->model);

?>
{!! Form::model($model, [
    'method' => 'PATCH', 'class' => 'form-horizontal', 
    'id' => 'form-imagem', 
    'action' => ['ImagemController@update', $model->$id, 'model' => $request->model],
    'files'=>true 
]) !!}
    @include('errors.form_error')
    
<div class="form-group">
    <label for="usuario" class="col-sm-2 control-label">
        {!! Form::label('Imagem: ') !!}
    </label>    
    <div class="col-md-3 col-xs-4">
        <input type="file" name="codimagem" id="codimagem" accept="image/*">
    </div>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
  {!! Form::submit('Enviar', array('class' => 'btn btn-primary')) !!}
  </div>
</div>    
{!! Form::close() !!}   
@stop