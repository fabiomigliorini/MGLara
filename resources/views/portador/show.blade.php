@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="{{ url('portador') }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>             
            <li><a href="{{ url('portador/create') }}"><span class="glyphicon glyphicon-plus"></span> Novo</a></li>             
            <li><a href="{{ url("portador/$model->codportador/edit") }}"><span class="glyphicon glyphicon-pencil"></span> Alterar</a></li> 
            <li>
                {!! Form::open(['method' => 'DELETE', 'id'=>'deleteId', 'route' => ['portador.destroy', $model->codportador]]) !!}
                <span class="glyphicon glyphicon-trash"></span>
                {!! Form::submit('Excluir') !!}
                {!! Form::close() !!}
            </li>
        </ul>
    </div>
</nav>
<ol class="breadcrumb header">{{ $model->portador }}</ol>
<hr>
<div class="row">
  <div class="col-lg-12">
      <table class="detail-view table table-striped table-condensed"> 
        <tbody>  
          <tr> 
            <th class="col-md-2">#</th> 
            <td class="col-md-10">{{ formataCodigo($model->codportador) }}</td> 
          </tr>
          <tr> 
            <th>Portador</th> 
            <td>{{ $model->portador }}</td> 
          </tr>
          <tr> 
            <th>Banco</th> 
            <td>
                @if($model->codbanco)
                    <a href="{{ url("banco/$model->codbanco") }}">{{ $model->Banco->banco }}</a>
                @endif
            </td> 
          </tr>
          <tr> 
            <th>Agência</th> 
            <td>{{ $model->agencia }}</td> 
          </tr>
          <tr> 
            <th>Digito</th> 
            <td>{{ $model->agenciadigito }}</td> 
          </tr>
          <tr> 
            <th>Conta</th> 
            <td>{{ $model->conta }}</td> 
          </tr>
          <tr> 
            <th>Digito</th> 
            <td>{{ $model->contadigito }}</td> 
          </tr>
          <tr> 
            <th>Emitir boleto</th> 
            <td>
                {{ !empty($model->emiteboleto) ? 'Sim' : 'Não' }}
            </td> 
          </tr>
          <tr> 
            <th>Filial</th> 
            <td>
                @if($model->codfilial)
                    <a href="{{ url("filial/$model->codfilial") }}">{{ $model->Filial->filial }}</a>
                @endif
            </td> 
          </tr>
          <tr> 
            <th>Convênio</th> 
            <td>{{ $model->convenio }}</td> 
          </tr>
          <tr> 
            <th>Diretório Remessa</th> 
            <td>{{ $model->diretorioremessa }}</td> 
          </tr>
          <tr> 
            <th>Diretório Retorno</th> 
            <td>{{ $model->diretorioretorno }}</td> 
          </tr>
          <tr> 
            <th>Carteira</th> 
            <td>{{ $model->carteira }}</td> 
          </tr>
        </tbody> 
      </table>
  </div>    
</div>
<hr>
@include('includes.autor')
@stop