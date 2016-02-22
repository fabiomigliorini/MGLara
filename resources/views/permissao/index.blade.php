@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
  <div class="container-fluid"> 
    <ul class="nav navbar-nav">
      <li>
        <a href="<?php echo url('permissao/create');?>"><span class="glyphicon glyphicon-plus"></span> Novo</a>
      </li> 
    </ul>
  </div>
</nav>
<h1 class="header">Permissões</h1>
<hr>
<div class="search-bar">
  {!! Form::model(Request::all(), [
    'route' => 'permissao.index', 
    'method' => 'GET', 
    'class' => 'form-inline',
    'id' => 'permissao-search',
    'role' => 'search'
  ])!!}
  <div class="form-group">
    {!! Form::text('codpermissao', null, ['class' => 'form-control search-cod', 'placeholder' => '#']) !!}
  </div>
  <div class="form-group">
    {!! Form::text('permissao', null, ['class' => 'form-control', 'placeholder' => 'Permissão']) !!}
  </div>
  <button type="submit" class="btn btn-default">Buscar</button>
{!! Form::close() !!}
</div>

<br>
<div id="registros">
  <div class="list-group" id="items">
    @foreach($model as $row)
      <div class="list-group-item">
        <div class="row item">
          <div class="col-md-1">
            <a href="<?php echo url("permissao/$row->codpermissao");?>">{{formataCodigo($row->codpermissao) }}</a>
          </div>
          <div class="col-md-5">
            <a href="<?php echo url("permissao/$row->codpermissao");?>">{{$row->observacoes}}</a>
          </div>
          <div class="col-md-6">
            <a href="<?php echo url("permissao/$row->codpermissao");?>">{{$row->permissao}}</a>
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
      
  });
</script>
@endsection
@stop

