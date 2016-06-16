@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li>
                <a href="{{ url('imagem') }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a>
            </li>
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
            
            @if($model->inativo)
            <li>
                {!! Form::open(['method' => 'DELETE', 'id'=>'deleteId', 'route' => ['imagem.destroy', $model->codimagem]]) !!}
                <span class="glyphicon glyphicon-trash"></span>
                {!! Form::submit('Excluir') !!}
                {!! Form::close() !!}
            </li>
            @endif
        </ul>
    </div>
</nav>

<h1 class="header">Imagem {{ formataCodigo($model->codimagem) }}</h1>
<hr>
<div>
    <div class="col-xs-6">
        <h3>Relacionamentos</h3>  
        @foreach($model->GrupoProdutoS as $grupo)
        <p>
            <strong>Grupo:</strong> <a href="{{ url("grupo-produto/{$grupo->codgrupoproduto}") }}">{{ $grupo->grupoproduto }}</a>
        </p>
        @endforeach
        
        @foreach($model->MarcaS as $marca)
        <p>
            <strong>Marca:</strong> <a href="{{ url("marca/{$marca->codmarca}") }}">{{ $marca->marca }}</a>
        </p>
        @endforeach

        @foreach($model->SecaoProdutoS as $secao)
        <p>
            <strong>Seçao Produto:</strong> <a href="{{ url("secao-produto/{$secao->codsecaoproduto}") }}">{{ $secao->secaoproduto }}</a>
        </p>
        @endforeach
       
        @foreach($model->SubGrupoProdutoS as $subgrupo)
        <p>
            <strong>Sub Grupo:</strong> <a href="{{ url("sub-grupo-produto/{$subgrupo->codsubgrupoproduto}") }}">{{ $subgrupo->subgrupoproduto }}</a>
        </p>
        @endforeach
    </div>
    <div class="col-xs-6">
        <img class="img-responsive" src="<?php echo URL::asset('public/imagens/'.$model->observacoes);?>">
    </div>
</div>
@stop