@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="{{ url('secao-produto') }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>             
            <li><a href="{{ url('secao-produto/create') }}"><span class="glyphicon glyphicon-plus"></span> Novo</a></li>             
            <li><a href="{{ url("secao-produto/$model->codsecaoproduto/edit") }}"><span class="glyphicon glyphicon-pencil"></span> Alterar</a></li> 
            <li>
                @if(empty($model->inativo))
                <a href="" id="inativo-secao-produto">
                    <span class="glyphicon glyphicon-ban-circle"></span> Inativar
                </a>
                @else
                <a href="" id="inativo-secao-produto">
                    <span class="glyphicon glyphicon-ok-sign"></span> Ativar
                </a>
                @endif
            </li> 
            <li>
                {!! Form::open(['method' => 'DELETE', 'id'=>'deleteId', 'route' => ['secao-produto.destroy', $model->codsecaoproduto]]) !!}
                <span class="glyphicon glyphicon-trash"></span>
                {!! Form::submit('Excluir') !!}
                {!! Form::close() !!}
            </li>
        </ul>
    </div>
</nav>
@if(!empty($model->inativo))
    <br>
    <div class="alert alert-danger" role="alert">Inativado em {{formataData($model->inativo, 'L')}}</div>
@endif
<div class="row">
    <div class="col-md-6">
        <h1 class="header">{{ $model->secaoproduto }}</h1>
    </div>
    <div class="pull-right foto-item-unico">
        @if(empty($model->codimagem))
            <a class="btn btn-default carregar" href="{{ url("/imagem/edit?id=$model->codsecaoproduto&model=SecaoProduto") }}">
                <i class="glyphicon glyphicon-picture"></i>
                 Carregar imagem
            </a>
        @else
        <a href="{{ url("imagem/{$model->Imagem->codimagem}") }}">
            <img class="img-responsive pull-right" src='<?php echo URL::asset('public/imagens/'.$model->Imagem->observacoes);?>'>
        </a>
        <span class="caption simple-caption">
            <a href="{{ url("/imagem/edit?id=$model->codsecaoproduto&model=SecaoProduto") }}" class="btn btn-default btn-sm"><i class="glyphicon glyphicon-pencil"></i> Alterar</a>
        </span>        
        @endif
    </div>
</div>
<hr>
<div class="row">
  <div class="col-lg-12">
      <table class="detail-view table table-striped table-condensed"> 
        <tbody>  
          <tr> 
            <th class="col-md-2">#</th> 
            <td class="col-md-10">{{ formataCodigo($model->codsecaoproduto) }}</td> 
          </tr>
          <tr> 
            <th>Seção</th> 
            <td>{{ $model->secaoproduto }}</td> 
          </tr>
        </tbody> 
      </table>
  </div>    
</div>
<hr>
@include('includes.autor')
<hr>
<h2>Famílias de produto  <span class="titulo-btn-novo"><!-- <a class="btn btn-default" href="{{ url("familia-produto/create?codsecaoproduto=$model->codsecaoproduto") }}"><i class=" glyphicon glyphicon-plus"></i> Novo</a> --><a class="btn btn-default" href=""><i class=" glyphicon glyphicon-plus"></i> Novo</a></span></h2>
<hr>
<div class="search-bar">
{!! Form::model(Request::all(), ['method' => 'GET', 'class' => 'form-inline', 'id' => 'familia-produto-search', 'role' => 'search', 'autocomplete' => 'off'])!!}
    <div class="form-group">
        {!! Form::text('codfamiliaproduto', null, ['class' => 'form-control search-cod', 'placeholder' => '#']) !!}
    </div>
    <div class="form-group">
        {!! Form::text('familiaproduto', null, ['class' => 'form-control', 'placeholder' => 'Família']) !!}
    </div>
    <div class="form-group">
        <select class="form-control" name="inativo" id="inativo" placeholder="Ativos">
            <option value=""></option>
            <option value="0">Todos</option>
            <option value="1" selected="selected">Ativos</option>
            <option value="2">Inativos</option>
        </select>
    </div>      
    <button type="submit" class="btn btn-default">Buscar</button>
{!! Form::close() !!}
</div>
<br>
<div id="registros">
  <div class="list-group" id="items">
    @foreach($familias as $row)
      <div class="list-group-item">
        <div class="row item">
            <div class="col-md-2">
                <!-- <a href="{{ url("familia-produto/$row->codfamiliaproduto") }}">{{ formataCodigo($row->codfamiliaproduto) }}</a> -->
                <a href="">{{ formataCodigo($row->codfamiliaproduto) }}</a>
            </div>                            
            <div class="col-md-4">
                <!-- <a href="{{ url("familia-produto/$row->codfamiliaproduto") }}">{{ $row->familiaproduto }}</a> -->
                <a href="">{{ $row->familiaproduto }}</a>
            </div>
            <div class="col-md-3">

            </div>
            <div class="col-md-3">
                
            </div>
        </div>
      </div>    
    @endforeach
    @if (count($familias) === 0)
        <h3>Nenhum registro encontrado!</h3>
    @endif    
  </div>
  {!! $familias->appends(Request::all())->render() !!}
</div>
@section('inscript')
<script type="text/javascript">
$(document).ready(function() {
    $("#familia-produto-search").on("change", function (event) {
        var $this = $(this);
        var frmValues = $this.serialize();
        $.ajax({
            type: 'GET',
            url: baseUrl + '/secao-produto/'+ {{$model->codsecaoproduto}},
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
    
    $('#inativo-secao-produto').on("click", function(e) {
        e.preventDefault();
        var codsecaoproduto = {{ $model->codsecaoproduto }};
        var token = '{{ csrf_token() }}';
        var inativo = '{{ $model->inativo }}';
        if(inativo.length === 0) {
            acao = 'inativar';
        } else {
            acao = 'ativar';
        }        
        bootbox.confirm("Tem certeza que deseja "+acao+"?", function(result) {
            if(result) {
                $.post(baseUrl + '/secao-produto/inativo', {
                    codsecaoproduto: codsecaoproduto,
                    acao: acao,
                    _token: token
                }).done(function (data) {
                    location.reload();
                }).fail(function (error){
                  location.reload();          
              });
            }  
        });
    });

});
</script>
@endsection
@stop