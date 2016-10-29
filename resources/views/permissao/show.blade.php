@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="<?php echo url('permissao');?>"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>             
            <li><a href="<?php echo url('permissao/create');?>"><span class="glyphicon glyphicon-plus"></span> Novo</a></li>             
            <li><a href="<?php echo url("permissao/$model->codpermissao/edit");?>"><span class="glyphicon glyphicon-pencil"></span> Alterar</a></li> 
            <li>
                <a href="{{ url("permissao/$model->codpermissao") }}" data-excluir data-pergunta="Tem certeza que deseja excluir a permissao'{{ $model->observacoes }}'?" data-after-delete="location.replace(baseUrl + '/permissao');"><i class="glyphicon glyphicon-trash"></i> Excluir</a>
            </li>
        </ul>
    </div>
</nav>
<ol class="breadcrumb header">
    {!! 
        titulo(
            $model->codpermissao,
            $model->observacoes,
            $model->inativo
        ) 
    !!}
</ol>
<hr>
<div class="row">
  <div class="col-lg-12">
      <table class="detail-view table table-striped table-condensed"> 
        <tbody>  
          <tr> 
            <th class="col-md-2">#</th> 
            <td class="col-md-10">{{ formataCodigo($model->codpermissao) }}</td> 
          </tr>
          <tr> 
            <th>Descrição</th> 
            <td>{{ $model->observacoes }}</td> 
          </tr>
          <tr> 
            <th>Permissão</th> 
            <td>{{ $model->permissao }}</td> 
          </tr>
        </tbody> 
      </table>
  </div>    
</div>
<hr>
@include('includes.autor')
@stop