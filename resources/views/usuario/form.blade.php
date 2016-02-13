<div class="form-group">
  <label for="usuario" class="col-sm-2 control-label">
  	{!! Form::label('Usuário', 'Usuário:') !!}
  </label>
  <div class="col-md-2 col-xs-4">
  	{!! Form::text('usuario', null, ['class'=> 'form-control'], ['id'=>'usuario']) !!}
  </div>
</div>

<div class="form-group">
  <label for="senha" class="col-sm-2 control-label">
  	{!! Form::label('Senha', 'Senha:') !!}
  </label>
  <div class="col-md-2 col-xs-4">
  	{!! Form::password('senha', null, ['class'=> 'form-control'], ['id'=>'senha']) !!}
  </div>
</div>

<div class="form-group">
  <label for="codecf" class="col-sm-2 control-label">
  	{!! Form::label('ECF', 'ECF:') !!}
  </label>
  <div class="col-md-2 col-xs-4">
  	{!! Form::text('codecf', null, ['class'=> 'form-control'], ['id'=>'codecf']) !!}
  </div>
</div>

<div class="form-group">
  <label for="codfilial" class="col-sm-2 control-label">
  	{!! Form::label('Filial', 'Filial:') !!}
  </label>
  <div class="col-md-2 col-xs-4">
  	{!! Form::text('codfilial', null, ['class'=> 'form-control'], ['id'=>'codfilial']) !!}
  </div>
</div>

<div class="form-group">
  <label for="codoperacao" class="col-sm-2 control-label">
  	{!! Form::label('Operação', 'Operação:') !!}
  </label>
  <div class="col-md-2 col-xs-4">
  	{!! Form::text('codoperacao', null, ['class'=> 'form-control'], ['id'=>'codoperacao']) !!}
  </div>
</div>

<div class="form-group">
  <label for="codpessoa" class="col-sm-2 control-label">
  	{!! Form::label('Pessoa', 'Pessoa:') !!}
  </label>
  <div class="col-sm-4">
  	{!! Form::text('codpessoa', null, ['class'=> 'form-control'], ['id'=>'codpessoa']) !!}
  </div>
</div>

<div class="form-group">
  <label for="impressoramatricial" class="col-sm-2 control-label">
  	{!! Form::label('Impressora Matricial', 'Impressora Matricial:') !!}
  </label>
  <div class="col-sm-3">
  	{!! Form::text('impressoramatricial', null, ['class'=> 'form-control'], ['id'=>'impressoramatricial']) !!}
  </div>
</div>

<div class="form-group">
  <label for="impressoratermica" class="col-sm-2 control-label">
  	{!! Form::label('Impressora Térmica', 'Impressora Térmica:') !!}
  </label>
  <div class="col-sm-3">
  	{!! Form::text('impressoratermica', null, ['class'=> 'form-control'], ['id'=>'impressoratermica']) !!}
  </div>
</div>

<div class="form-group">
  <label for="codportador" class="col-sm-2 control-label">
  	{!! Form::label('Portador', 'Portador:') !!}
  </label>
  <div class="col-sm-2">
  	{!! Form::text('codportador', null, ['class'=> 'form-control'], ['id'=>'codportador']) !!}
  </div>
</div>

<div class="form-group">
  <label for="inativo" class="col-sm-2 control-label">
  	{!! Form::label('Inativo', 'Inativo:') !!}
  </label>
  <div class="col-sm-2">
  	{!! Form::text('inativo', null, ['class'=> 'form-control'], ['id'=>'inativo']) !!}
  </div>
</div>

<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
  {!! Form::submit($submitTextButton, array('class' => 'btn btn-primary')) !!}
  </div>
</div>
