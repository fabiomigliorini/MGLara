@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default" id="submenu">
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
<ol class="breadcrumb header">Estoque mês</ol>
<hr>
<br>
<div id="registros">
  <div class="list-group list-group-striped list-group-hover" id="items">
    @foreach($model as $row)
      <div class="list-group-item">
        <div class="row item">
          <div class="col-md-1">
            <a href="<?php echo url("estoque-mes/$row->codestoquemes");?>">{{formataCodigo($row->codestoquemes) }}</a>
          </div>
          <div class="col-md-5">
            <a href="<?php echo url("estoque-mes/$row->codpermissao");?>">{{$row->mes}}</a>
          </div>
          <div class="col-md-6">
            <a href="<?php echo url("estoque-mes/$row->codpermissao");?>">{{$row->saldovalor}}</a>
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
function scroll()
{
    var loading_options = {
        finishedMsg: "<div class='end-msg'>Fim dos registros</div>",
        msgText: "<div class='center'>Carregando mais itens...</div>",
        img: baseUrl + '/public/img/ajax-loader.gif'
    };

    $('#items').infinitescroll({
        loading : loading_options,
        navSelector : "#registros .pagination",
        nextSelector : "#registros .pagination li.active + li a",
        itemSelector : "#items div.list-group-item",
        debug:true
    });    
}
  $(document).ready(function() {
      scroll();
  });
</script>
@endsection
@stop

