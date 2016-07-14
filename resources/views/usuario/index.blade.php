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
    'id' => 'usuario-search',
    'role' => 'search'
  ])!!}
  <div class="form-group">
    {!! Form::text('codusuario', null, ['class' => 'form-control search-cod', 'placeholder' => '#']) !!}
  </div>
  <div class="form-group">
    {!! Form::text('usuario', null, ['class' => 'form-control', 'placeholder' => 'Usuário']) !!}
  </div>
  <div class="form-group">
    <input type="text" name="codpessoa" id="codpessoa" class="form-control search-pessoa" />
  </div>
  <div class="form-group">
    {!! Form::select('codfilial', $filiais, ['class' => 'search-filial'], ['id' => 'codfilial']) !!}
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
            <a href="<?php echo url("usuario/$row->codusuario");?>">{{formataCodigo($row->codusuario)}}</a>
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
    @if (count($model) === 0)
        <h3>Nenhum registro encontrado!</h3>
    @endif    
  </div>
  <?php echo $model->appends(Request::all())->render();?>
</div>
@section('inscript')
<style type="text/css">
.search-pessoa {
  width: 500px !important;
}  
</style>
<script type="text/javascript">
$(document).ready(function() {
  $('#usuario-search').change(function() {
      this.submit();
  });
  $(document).on('dp.change', '#codfilial, #codpessoa', function() {
      $('#produto-search').submit();
  });  
  $('#codfilial' ).prepend('<option value="" selected=""></option>');
  $('#codfilial').select2({
      placeholder: 'Filial',
      allowClear: true,
      closeOnSelect: true,      
      width:'resolve'
  });  
    
 /* $('#codpessoa').select2({
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
      'url': baseUrl+'/pessoa/listagem-json',
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
        url: baseUrl+'/pessoa/listagem-json',
        data: "codpessoa=",
        dataType: "json",
        success: function(result) {
          callback(result); 
        }
      });
    },
    'width':'resolve'
  });*/
    
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
            var css_detalhes = "text-muted";
            if (item.inativo){
                css_titulo = "text-error";
                css_detalhes = "text-error";
            }

            var nome = item.fantasia;

            //if (item.inclusaoSpc != 0)
            //	nome += "&nbsp<span class=\"label label-warning\">" + item.inclusaoSpc + "</span>";

            var markup = "";
            markup    += "<strong class='" + css_titulo + "'>" + nome + "</strong>";
            markup    += "<small class='pull-right " + css_detalhes + "'>#" + formataCodigo(item.id) + "</small>";
            markup    += "<br>";
            markup    += "<small class='" + css_detalhes + "'>" + item.pessoa + "</small>";
            markup    += "<small class='pull-right " + css_detalhes + "'>" + formataCnpjCpf(item.cnpj) + "</small>";
            return markup;
        },
        'formatSelection':function(item) { 
            return item.fantasia; 
        },
        'ajax':{
            'url':baseUrl+'/pessoa/listagem-json',
            'dataType':'json',
            'quietMillis':500,
            'data':function(term, current_page) { 
                return {
                    q: term, 
                    per_page: 10, 
                    current_page: current_page
                }; 
            },
            'results':function(data,page) {
                //var more = (current_page * 20) < data.total;
                return {
                    results: data.data, 
                    //more: data.mais
                };
            }
        },
        'initSelection':function (element, callback) {
            $.ajax({
                type: "GET",
                url: baseUrl+'/pessoa/listagem-json',
                data: "id=<?php if(isset($_GET['codpessoa'])){echo $_GET['codpessoa'];}?>",
                dataType: "json",
                success: function(result) { 
                    callback(result); 
                }
            });
        },'width':'resolve'
    });      
});
</script>
@endsection
@stop

