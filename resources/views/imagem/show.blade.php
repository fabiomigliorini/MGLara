@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li>
                <a href="{{ url('imagem') }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a>
            </li> 
        </ul>
    </div>
</nav>

<h1 class="header">Imagem {{ formataCodigo($model->codimagem) }}</h1>
<hr>
<div>
    <div class="col-xs-6">
        <h3>Relacionamentos</h3>
        <ul>
            <li>...</li>
            <li>...</li>
        </ul>
    </div>
    <div>
        <img src="<?php echo URL::asset('public/imagens/'.$model->observacoes);?>">
    </div>
</div>
@stop