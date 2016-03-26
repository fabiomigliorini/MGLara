@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li>
                <a href="{{ url('imagem') }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a>
            </li> 
            <li>
                {!! Form::open(['method' => 'DELETE', 'id'=>'deleteId', 'route' => ['imagem.destroy', $model->codimagem]]) !!}
                <span class="glyphicon glyphicon-trash"></span>
                {!! Form::submit('Excluir') !!}
                {!! Form::close() !!}
            </li>             
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
            <a href="{{ url("grupo-produto/{$grupo->codgrupoproduto}") }}">{{ $grupo->grupoproduto }}</a>
        </p>
        @endforeach
        @foreach($model->MarcaS as $marca)
        <p>
            <a href="{{ url("marca/{$marca->codmarca}") }}">{{ $marca->marca }}</a>
        </p>
        @endforeach
        @foreach($model->ProdutoS as $produto)
        <p>
            <a href="{{ url("produto/{$produto->codproduto}") }}">{{ $produto->produto }}</a>
        </p>
        @endforeach
        @foreach($model->SubGrupoProdutoS as $subgrupo)
        <p>
            <a href="{{ url("sub-grupo-produto/{$subgrupo->codsubgrupoproduto}") }}">{{ $subgrupo->subgrupoproduto }}</a>
        </p>
        @endforeach
    </div>
    <div>
        <img src="<?php echo URL::asset('public/imagens/'.$model->observacoes);?>">
    </div>
</div>
@stop