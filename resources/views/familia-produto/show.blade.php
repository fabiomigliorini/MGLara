@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="{{ url("secao-produto/$model->codsecaoproduto") }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>             
            <li><a href="{{ url("familia-produto/create?codsecaoproduto=$model->codsecaoproduto") }}"><span class="glyphicon glyphicon-plus"></span> Novo</a></li>
            <li><a href="{{ url("familia-produto/$model->codfamiliaproduto/edit") }}"><span class="glyphicon glyphicon-pencil"></span> Alterar</a></li> 
            <li>
                @if(empty($model->inativo))
                <a href="" id="inativo-familia-produto">
                    <span class="glyphicon glyphicon-ban-circle"></span> Inativar
                </a>
                @else
                <a href="" id="inativo-familia-produto">
                    <span class="glyphicon glyphicon-ok-sign"></span> Ativar
                </a>
                @endif
            </li> 
            <li>
                {!! Form::open(['method' => 'DELETE', 'id'=>'deleteId', 'route' => ['familia-produto.destroy', $model->codfamiliaproduto]]) !!}
                <span class="glyphicon glyphicon-trash"></span>
                {!! Form::submit('Excluir') !!}
                {!! Form::close() !!}
            </li>
        </ul>
    </div>
</nav>
<div class="pull-right foto-item-unico">
    @if(empty($model->codimagem))
        <a class="btn btn-default carregar" href="{{ url("/imagem/edit?id=$model->codfamiliaproduto&model=FamiliaProduto") }}">
            <i class="glyphicon glyphicon-picture"></i>
             Carregar imagem
        </a>
    @else
    <a href="{{ url("imagem/{$model->Imagem->codimagem}") }}">
        <img class="img-responsive pull-right" src='<?php echo URL::asset('public/imagens/'.$model->Imagem->observacoes);?>'>
    </a>
    <span class="caption simple-caption">
        <a href="{{ url("/imagem/edit?id=$model->codfamiliaproduto&model=FamiliaProduto") }}" class="btn btn-default btn-sm"><i class="glyphicon glyphicon-pencil"></i> Alterar</a>
    </span>        
    @endif
</div>
<h1 class="header">
{!! 
    titulo(
        $model->codfamiliaproduto,
        [
            ['url' => "secao-produto/$model->codsecaoproduto", 'descricao' => $model->SecaoProduto->secaoproduto],
            ['url' => null, 'descricao' => $model->familiaproduto]
        ],
        $model->inativo
    ) 
!!}
</h1>
@include('includes.autor')
<hr>
{!! Form::model(Request::all(), ['method' => 'GET', 'class' => 'form-inline', 'id' => 'grupo-produto-search', 'role' => 'search', 'autocomplete' => 'off'])!!}
    <div class="form-group">
        {!! Form::text('grupoproduto', null, ['class' => 'form-control', 'placeholder' => 'Grupo']) !!}
    </div>
    <div class="form-group">
        <select class="form-control" name="inativo" id="inativo" placeholder="Ativos">
            <option value="0">Todos</option>
            <option value="1" selected="selected">Ativos</option>
            <option value="2">Inativos</option>
        </select>
    </div>      
    <button type="submit" class="btn btn-default"><i class=" glyphicon glyphicon-search"></i> Buscar</button>
    <a class="btn btn-default" href="{{ url("grupo-produto/create?codfamiliaproduto=$model->codfamiliaproduto") }}">
        <i class=" glyphicon glyphicon-plus"></i> Novo Grupo
    </a>
{!! Form::close() !!}
<br>
<div id="registros">
  <div class="list-group group-list-striped group-list-hover" id="items">
    @foreach($grupos as $row)
        <div class="list-group-item @if(!empty($row->inativo)) bg-danger @endif"">
            <div class="row item">
                <div class="col-md-1">
                    <a class="small text-muted" href="{{ url("grupo-produto/$row->codgrupoproduto") }}">{{ formataCodigo($row->codgrupoproduto) }}</a>
                </div>                            
                <div class="col-md-7">
                    <a href="{{ url("grupo-produto/$row->codgrupoproduto") }}">
                        {!! listagemTitulo($row->grupoproduto, $row->inativo) !!}
                    </a>
                </div>
                <div class="col-md-2">
                    {!! inativo($row->inativo) !!}
                </div>
                <div class="col-md-2">
                @if(!empty($row->codimagem))
                    <div class="pull-right foto-item-listagem">
                        <img class="img-responsive pull-right" alt="{{$row->grupoproduto}}" title="{{$row->grupoproduto}}" src='<?php echo URL::asset('public/imagens/'.$row->Imagem->observacoes);?>'>
                    </div>
                @endif                      
                </div>
            </div>
        </div>    
    @endforeach
    @if (count($grupos) === 0)
        <h3>Nenhum Grupo encontrado!</h3>
    @endif    
  </div>
  {!! $grupos->appends(Request::all())->render() !!}
</div>
@section('inscript')
<script type="text/javascript">
$(document).ready(function() {
    $("#grupo-produto-search").on("change", function (event) {
        var $this = $(this);
        var frmValues = $this.serialize();
        $.ajax({
            type: 'GET',
            url: baseUrl + '/familia-produto/'+ {{$model->codfamiliaproduto}},
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
    
    $('#inativo-familia-produto').on("click", function(e) {
        e.preventDefault();
        var codfamiliaproduto = {{ $model->codfamiliaproduto }};
        var token = '{{ csrf_token() }}';
        var inativo = '{{ $model->inativo }}';
        if(inativo.length === 0) {
            acao = 'inativar';
        } else {
            acao = 'ativar';
        }        
        bootbox.confirm("Tem certeza que deseja "+acao+"?", function(result) {
            if(result) {
                $.post(baseUrl + '/familia-produto/inativo', {
                    codfamiliaproduto: codfamiliaproduto,
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