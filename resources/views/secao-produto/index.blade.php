@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li>
                <a href="{{ url("secao-produto/create") }}"><span class="glyphicon glyphicon-plus"></span> Novo</a>
            </li> 
        </ul>
    </div>
</nav>
<h1 class="header">{!! titulo(null, [ ['url' => null, 'descricao' => 'Seções de Produto'] ], null) !!}  </h1>
<hr>
<div class="search-bar">
{!! Form::model(Request::session()->get('secao-produto'), ['route' => 'secao-produto.index', 'method' => 'GET', 'class' => 'form-inline', 'id' => 'secao-produto-search', 'role' => 'search', 'autocomplete' => 'off'])!!}
    <div class="form-group">
        {!! Form::text('codsecaoproduto', null, ['class' => 'form-control search-cod', 'placeholder' => '#']) !!}
    </div>
    <div class="form-group">
        {!! Form::text('secaoproduto', null, ['class' => 'form-control', 'placeholder' => 'Seção']) !!}
    </div>
    <div class="form-group">
        {!! Form::select('inativo', ['0' => 'Todos', '1' => 'Ativos', '2' => 'Inativos'], (Request::session()->get('secao-produto')['inativo'] == '' ? '1' : null), ['class' => 'form-control']) !!}
    </div>      
    <button type="submit" class="btn btn-default">Buscar</button>
{!! Form::close() !!}
</div>

<br>
<div id="registros">
  <div class="list-group group-list-striped group-list-hover" id="items">
    @foreach($model as $row)
      <div class="list-group-item @if(!empty($row->inativo)) bg-danger @endif">
        <div class="row item">
            <div class="col-md-1">
                <a class="small text-muted" href="{{ url("secao-produto/$row->codsecaoproduto") }}">
                {{ formataCodigo($row->codsecaoproduto)}}
                </a>          
            </div>                            
            <div class="col-md-7">
            <a href="{{ url("secao-produto/$row->codsecaoproduto") }}">
                {!! listagemTitulo($row->secaoproduto, $row->inativo) !!}
            </a>
            </div>                            
            <div class="col-md-2">
                {!! inativo($row->inativo) !!}
            </div>
            <div class="col-md-2">
            @if(!empty($row->codimagem))
                <div class="pull-right foto-item-listagem">
                    <img class="img-responsive pull-right" alt="{{$row->secaoproduto}}" title="{{$row->secaoproduto}}" src='<?php echo URL::asset('public/imagens/'.$row->Imagem->observacoes);?>'>
                </div>
            @endif             
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

    $("#secao-produto-search").on("change", function (event) {
        var $this = $(this);
        var frmValues = $this.serialize();
        $.ajax({
            type: 'GET',
            url: baseUrl + '/secao-produto',
            data: frmValues
        })
        .done(function (data) {
            $('#items').html(jQuery(data).find('#items').html()); 
        })
        .fail(function () {
            console.log('Erro no filtro');
        });
        event.preventDefault(); 
    });       
});
</script>
@endsection
@stop