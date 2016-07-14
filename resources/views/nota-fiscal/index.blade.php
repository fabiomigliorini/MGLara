@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
  <div class="container-fluid"> 
    <ul class="nav navbar-nav">
    </ul>
  </div>
</nav>
<h1 class="header">Notas Fiscais</h1>
<hr>
<br>
<div id="registros">
  <div class="list-group list-group-striped list-group-hover" id="items">
    @foreach($model as $row)
      <div class="list-group-item">
        <div class="row item">
          <div class="col-md-1">
            <a href="<?php echo url("notafiscal/$row->codnotafiscal");?>">{{formataCodigo($row->codnotafiscal) }}</a>
          </div>
        </div>
      </div>    
    @endforeach
    @if (count($model) === 0)
        <h3>Nenhum registro encontrado!</h3>
    @endif    
  </div>
</div>
@section('inscript')
<script type="text/javascript">
  $(document).ready(function() {
      
  });
</script>
@endsection
@stop

