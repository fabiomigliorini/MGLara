@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="<?php echo url('permissao');?>"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>             
            <li><a href="<?php echo url('permissao/create');?>"><span class="glyphicon glyphicon-plus"></span> Novo</a></li>             
            <li><a href="<?php echo url("permissao/$model->codpermissao/edit");?>"><span class="glyphicon glyphicon-pencil"></span> Alterar</a></li> 
            <li>
                {!! Form::open(['method' => 'DELETE', 'route' => ['permissao.destroy', $model->codpermissao]]) !!}
                <span class="glyphicon glyphicon-trash"></span>
                {!! Form::submit('Excluir') !!}
                {!! Form::close() !!}
            </li>
        </ul>
    </div>
</nav>
<h1 class="header">Detalhes Permissão #{{ $model->codpermissao }}</h1>
<hr>
<div class="row">
  <div class="col-lg-12">
      <table class="detail-view table table-striped table-condensed"> 
        <tbody>  
          <tr> 
            <th class="col-md-2">Descrição</th> 
            <td class="col-md-10">{{ $model->observacoes }}</td> 
          </tr>
          <tr> 
            <th class="col-md-2">Permissão</th> 
            <td class="col-md-10">{{ $model->permissao }}</td> 
          </tr>
        </tbody> 
      </table>
  </div>    
</div>
<hr>
@include('includes.autor')
@stop