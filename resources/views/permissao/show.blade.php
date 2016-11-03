@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
    {!! 
        titulo(
            $model->codpermissao,
            [
                url("permissao") => 'Permissões',
                $model->observacoes,
            ],
            $model->inativo
        ) 
    !!}
    <li class='active'>
        <small>
            <a title="Novo" href="{{ url('permissao/create') }}"><i class="glyphicon glyphicon-plus"></i></a>
            &nbsp;
            <a title="Alterar" href="{{ url("permissao/$model->codpermissao/edit") }}"><i class="glyphicon glyphicon-pencil"></i></a>
            &nbsp;
            <a title="Excluir" href="{{ url("permissao/$model->codpermissao") }}" data-excluir data-pergunta="Tem certeza que deseja excluir a permissao'{{ $model->observacoes }}'?" data-after-delete="location.replace(baseUrl + '/permissao');"><i class="glyphicon glyphicon-trash"></i></a>
        </small>
    </li>   
    
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