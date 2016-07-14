@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
  <div class="container-fluid"> 
    <ul class="nav navbar-nav">
      <li>
        <a href="<?php echo url('grupo-usuario/create');?>"><span class="glyphicon glyphicon-plus"></span> Novo</a>
      </li> 
    </ul>
  </div>
</nav>
<h1 class="header">Grupos de usuário</h1>
<hr>
<div class="search-bar">
  {!! Form::model(Request::all(), [
    'route' => 'grupo-usuario.index', 
    'method' => 'GET', 
    'class' => 'form-inline',
    'id' => 'grupo-usuario-search',
    'role' => 'search'
  ])!!}
  <div class="form-group">
    {!! Form::text('codgrupousuario', null, ['class' => 'form-control search-cod', 'placeholder' => '#']) !!}
  </div>
  <div class="form-group">
    {!! Form::text('grupousuario', null, ['class' => 'form-control', 'placeholder' => 'Grupo de usuário']) !!}
  </div>
  <button type="submit" class="btn btn-default">Buscar</button>
{!! Form::close() !!}
</div>

<br>
<div id="registros">
  <div class="list-group list-group-striped list-group-hover" id="items">
    @foreach($model as $row)
      <div class="list-group-item">
        <div class="row item">
          <div class="col-md-1">
            <a href="<?php echo url("grupo-usuario/$row->codgrupousuario");?>">{{formataCodigo($row->codgrupousuario)}}</a>
          </div>                            
          <div class="col-md-11">
            <a href="<?php echo url("grupo-usuario/$row->codgrupousuario");?>">{{$row->grupousuario}}</a>
          </div>                  
        </div>
      </div>    
    @endforeach
    @if (count($model) === 0)
        <h3>Nenhum registro encontrado!</h3>
    @endif    
  </div>
  <?php echo $model->appends(Request::all())->render();?>
</div>
@section('inscript')
<script type="text/javascript">
  $(document).ready(function() {
    $('#grupo-usuario-search').change(function() {
        this.submit();
    });       
  });
</script>
@endsection
@stop

