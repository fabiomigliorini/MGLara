@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
  <div class="container-fluid"> 
    <ul class="nav navbar-nav">
      <!--
      <li>
        <a href="<?php echo url('permissao/create');?>"><span class="glyphicon glyphicon-plus"></span> Novo</a>
      </li>
      -->
    </ul>
  </div>
</nav>
<h1 class="header">Estoque mês</h1>
<hr>
<br>
<div id="registros">
  <div class="list-group" id="items">
    @foreach($model as $row)
      <div class="list-group-item">
        <div class="row item">
          <div class="col-md-1">
            <a href="<?php echo url("estoquemes/$row->codestoquemes");?>">{{formataCodigo($row->codestoquemes) }}</a>
          </div>
          <div class="col-md-5">
            <a href="<?php echo url("estoquemes/$row->codpermissao");?>">{{$row->mes}}</a>
          </div>
          <div class="col-md-6">
            <a href="<?php echo url("estoquemes/$row->codpermissao");?>">{{$row->saldovalor}}</a>
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

