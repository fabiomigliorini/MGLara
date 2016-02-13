@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="<?php echo url('usuario');?>"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>             
            <li><a href="<?php echo url('usuario/create');?>"><span class="glyphicon glyphicon-plus"></span> Novo</a></li>             
            <li><a href="<?php echo url("usuario/$model->codusuario/edit");?>"><span class="glyphicon glyphicon-pencil"></span> Alterar</a></li> 
            <li>
                {!! Form::open(['method' => 'DELETE', 'route' => ['usuario.destroy', $model->codusuario]]) !!}
                <span class="glyphicon glyphicon-trash"></span>
                {!! Form::submit('Excluir') !!}
                {!! Form::close() !!}
            </li>
        </ul>
    </div>
</nav>
<h1 class="header">Detalhes Usuario #{{ $model->codusuario }}</h1>
<hr>
<div class="row">
  <div class="col-lg-12">
      <table class="detail-view table table-striped table-condensed"> 
        <tbody>  
          <tr> 
            <th class="col-md-2">Usuário</th> 
            <td class="col-md-10">{{ $model->usuario }}</td> 
          </tr>
          <tr> 
            <th>ECF</th> 
            <td>{!! isset($model->codecf) ? $model->Ecf['ecf'] : isNull('Vazio') !!}</td> 
          </tr>
          <tr> 
            <th>Filial</th> 
            <td>{!! isset($model->codfilial) ? $model->Filial['filial'] : isNull('Vazio') !!}</td> 
          </tr>
          <tr> 
            <th>Operação</th> 
            <td>{!! isset($model->codoperacao) ? $model->Operacao['operacao'] : isNull('Vazio') !!}</td> 
          </tr>
          <tr> 
            <th>Pessoa</th> 
            <td>{!! isset($model->codpessoa) ? linkRel($model->Pessoa['pessoa'], 'pessoa', $model->codpessoa) : isNull('Vazio') !!}</td> 
          </tr>
          <tr> 
            <th>Impressora Matricial</th> 
            <td>{{ $model->impressoramatricial }}</td> 
          </tr> 
          <tr> 
            <th>Impressora Térmica</th> 
            <td>{{ $model->impressoratermica }}</td> 
          </tr>
          <tr> 
            <th>Último acesso</th> 
            <td>{{ dateBRfull($model->ultimoacesso) }}</td> 
          </tr>           
          <tr> 
            <th>Inativo</th> 
            <td>{{ dateBR($model->inativo) }}</td> 
          </tr> 
        </tbody> 
      </table>
  </div>    
</div>
<hr>
@include('includes.autor')
@stop