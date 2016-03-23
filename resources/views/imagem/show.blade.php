@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li>
                <a href=""><span class="glyphicon glyphicon-list-alt"></span> Listagem</a>
            </li> 
        </ul>
    </div>
</nav>

<h1 class="header">Imagem {{ formataCodigo($model->codimagem) }}</h1>
<hr>
<img src="<?php echo URL::asset('public/imagens/'.$model->observacoes);?>">
@stop