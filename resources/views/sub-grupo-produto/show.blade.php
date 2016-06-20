@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="{{ url("grupo-produto/$model->codgrupoproduto") }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>             
            <li><a href="{{ url('sub-grupo-produto/create') }}"><span class="glyphicon glyphicon-plus"></span> Novo</a></li>             
            <li><a href="{{ url("sub-grupo-produto/$model->codgrupoproduto/edit") }}"><span class="glyphicon glyphicon-pencil"></span> Alterar</a></li> 
            <li>
                @if(empty($model->inativo))
                <a href="" id="inativo-sub-grupo-produto">
                    <span class="glyphicon glyphicon-ban-circle"></span> Inativar
                </a>
                @else
                <a href="" id="inativo-sub-grupo-produto">
                    <span class="glyphicon glyphicon-ok-sign"></span> Ativar
                </a>
                @endif
            </li> 
            <li>
                {!! Form::open(['method' => 'DELETE', 'id'=>'deleteId', 'route' => ['sub-grupo-produto.destroy', $model->codgrupoproduto]]) !!}
                <span class="glyphicon glyphicon-trash"></span>
                {!! Form::submit('Excluir') !!}
                {!! Form::close() !!}
            </li>
        </ul>
    </div>
</nav>
<div class="pull-right foto-item-unico">
    @if(empty($model->codimagem))
        <a class="btn btn-default carregar" href="{{ url("/imagem/edit?id=$model->codgrupoproduto&model=GrupoProduto") }}">
            <i class="glyphicon glyphicon-picture"></i>
            Carregar imagem
        </a>
    @else
    <a href="{{ url("imagem/{$model->Imagem->codimagem}") }}">
        <img class="img-responsive pull-right" src='<?php echo URL::asset('public/imagens/'.$model->Imagem->observacoes);?>'>
    </a>
    <span class="caption simple-caption">
        <a href="{{ url("/imagem/edit?id=$model->codgrupoproduto&model=GrupoProduto") }}" class="btn btn-default btn-sm"><i class="glyphicon glyphicon-pencil"></i> Alterar</a>
    </span>        
    @endif
</div>

<h1 class="header">
{!! 
    titulo(
        $model->codgrupoproduto,
        [
            [
                'url' => "secao-produto/{$model->GrupoProduto->FamiliaProduto->SecaoProduto->codsecaoproduto}", 
                'descricao' => $model->GrupoProduto->FamiliaProduto->SecaoProduto->secaoproduto
            ],
            [
                'url' => "familia-produto/{$model->GrupoProduto->FamiliaProduto->codfamiliaproduto}", 
                'descricao' => $model->GrupoProduto->FamiliaProduto->familiaproduto
            ],
            ['url' => "grupo-produto/$model->codgrupoproduto", 'descricao' => $model->GrupoProduto->grupoproduto],
            ['url' => null, 'descricao' => $model->subgrupoproduto],
        ],
        $model->inativo
    ) 
!!} 
</h1>
@include('includes.autor')
<hr>
{!! Form::model(Request::all(), ['method' => 'GET', 'class' => 'form-inline', 'id' => 'produto-search', 'role' => 'search', 'autocomplete' => 'off'])!!}
    <div class="form-group">
        <input type="text" name="produto" id="produto" placeholder="Produto" class="form-control">
    </div>
    <div class="form-group">
        <select class="form-control" name="inativo" id="inativo" placeholder="Ativos">
            <option value="0">Todas</option>
            <option value="1" selected="selected">Ativas</option>
            <option value="2">Inativas</option>
        </select>
    </div>      
    <button type="submit" class="btn btn-default"><i class=" glyphicon glyphicon-search"></i> Buscar</button>
    <a class="btn btn-default" href=""><i class=" glyphicon glyphicon-plus"></i> Novo Produto</a>
{!! Form::close() !!}
<br>
<div id="registros">
  <div class="list-group group-list-striped group-list-hover" id="items">
    @foreach($produtos as $row)
      <div class="list-group-item @if(!empty($row->inativo)) bg-danger @endif">
        <div class="row item">
            <div class="col-md-1">
                <!-- <a class="small text-muted" href="{{ url("produto/$row->codproduto") }}">{{ formataCodigo($row->codproduto) }}</a> -->
            </div>                            
            <div class="col-md-4">
                <!-- <a href="{{ url("produto/$row->codproduto") }}">{{ $row->produto }}</a> -->
            </div>
            <div class="col-md-6">

            </div>
        </div>
      </div>    
    @endforeach
    @if (count($produtos) === 0)
        <h3>Nenhum Produto encontrado!</h3>
    @endif    
  </div>
  {!! $produtos->appends(Request::all())->render() !!}
</div>
@section('inscript')
<script type="text/javascript">
$(document).ready(function() {
    $("#produto-search").on("change", function (event) {
        var $this = $(this);
        var frmValues = $this.serialize();
        console.log(frmValues);
        $.ajax({
            type: 'GET',
            url: baseUrl + '/sub-grupo-produto/'+ {{$model->codgrupoproduto}},
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
    
    $('#inativo-sub-grupo-produto').on("click", function(e) {
        e.preventDefault();
        var codgrupoproduto = {{ $model->codgrupoproduto }};
        var token = '{{ csrf_token() }}';
        var inativo = '{{ $model->inativo }}';
        if(inativo.length === 0) {
            acao = 'inativar';
        } else {
            acao = 'ativar';
        }        
        bootbox.confirm("Tem certeza que deseja "+acao+"?", function(result) {
            if(result) {
                $.post(baseUrl + '/sub-grupo-produto/inativo', {
                    codgrupoproduto: codgrupoproduto,
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