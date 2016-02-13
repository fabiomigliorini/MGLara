@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
  <div class="container-fluid"> 
    <ul class="nav navbar-nav">
      <li>
        <a href="<?php echo url('usuario/create');?>"><span class="glyphicon glyphicon-plus"></span> Novo</a>
      </li> 
    </ul>
  </div>
</nav>
<h1 class="header">Usuarios</h1>
<hr>
<div class="search-bar">
  {!! Form::model(Request::all(), [
    'route' => 'usuario.index', 
    'method' => 'GET', 
    'class' => 'form-inline', 
    'role' => 'search'
  ])!!}
  <div class="form-group">
    {!! Form::text('codusuario', null, ['class' => 'form-control search-cod', 'placeholder' => '#']) !!}
  </div>
  <div class="form-group">
    {!! Form::text('usuario', null, ['class' => 'form-control', 'placeholder' => 'Usuário']) !!}
  </div>
  <div class="form-group">
    {!! Form::text('codpessoa', null, ['class' => 'form-control', 'id' => 'codpessoa', 'placeholder' => 'Pessoa']) !!}
  </div>
  <div class="form-group">
    {!! Form::text('codfilial', null, ['class' => 'form-control', 'id' => 'codfilial' , 'placeholder' => 'Filial']) !!}
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
            <a href="<?php echo url("usuario/$row->codusuario");?>">#{{$row->codusuario}}</a>
          </div>                            
          <div class="col-md-2">
            <a href="<?php echo url("usuario/$row->codusuario");?>">{{$row->usuario}}</a>
          </div>
          <div class="col-md-4">
              <a href="<?php echo url("pessoa/$row->codpessoa");?>">{{ $row->Pessoa['pessoa'] }}</a>
          </div>
          <div class="col-md-4">
              <a href="<?php echo url("filial/$row->codfilial");?>">{{ $row->Filial['filial'] }}</a>
          </div>                    
        </div>
      </div>    
    @endforeach
  </div>
  <?php echo $model->render();?>
</div>
@section('inscript')
<script type="text/javascript">
$(document).ready(function() {
    
    
  $('#codpessoa').select2({
    'minimumInputLength':3,
    'allowClear':true,
    'closeOnSelect':true,
    'placeholder':'Pessoa',
    'formatResult':function(item) {
      var css = "div-combo-pessoa";
      if (item.inativo)
        var css = "text-error";
      var css_titulo = "";
      var css_detalhes = "muted";
      if (item.inativo)
      {
              css_titulo = "text-error";
              css_detalhes = "text-error";
      }

      var nome = item.fantasia;

      //if (item.inclusaoSpc != 0)
      //  nome += "&nbsp<span class=\"label label-warning\">" + item.inclusaoSpc + "</span>";

      var markup = "";
      markup    += "<strong class='" + css_titulo + "'>" + nome + "</strong>";
      //markup    += "<small class='pull-right " + css_detalhes + "'>#" + formataCodigo(item.id) + "</small>";
      //markup    += "<br>";
      //markup    += "<small class='" + css_detalhes + "'>" + item.pessoa + "</small>";
      //markup    += "<small class='pull-right " + css_detalhes + "'>" + formataCnpjCpf(item.cnpj) + "</small>";
      return markup;
    },
    'formatSelection':function(item) { 
      return item.fantasia; 
    },
    'ajax':{
      'url': baseUrl+'/pessoa-ajax',
      'dataType':'json',
      'quietMillis':500,
      'data':function(term,page) { 
        return {
          q: term, 
          //limite: 20, 
          //pagina: page
        }; 
      },
      'results':function(data, page) {
        var more = (page * 20) < data.total;
        return {
          results: data.items, 
          //more: data.mais
        };
      }
    },
    'initSelection':function (element, callback) {
      $.ajax({
        type: "GET",
        url: baseUrl+'/pessoa-ajax',
        data: "id=",
        dataType: "json",
        success: function(result) {
          callback(result); 
        }
      });
    },
    'width':'resolve'
  });    
    
    
});
</script>
@endsection
@stop

