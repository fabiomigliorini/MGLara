@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
    {!! 
        titulo(
            $model->codunidademedida,
            [
                url("unidade-medida") => 'Unidades de Medida',
                $model->unidademedida
            ],
            $model->inativo
        ) 
    !!}    
    <li class='active'>
        <small>
            <a title="Nova Unidade" href="{{ url('unidade-medida/create') }}"><span class="glyphicon glyphicon-plus"></span></a>
            &nbsp;
            <a title="Alterar" href="{{ url("unidade-medida/$model->codunidademedida/edit") }}"><span class="glyphicon glyphicon-pencil"></span></a>
            &nbsp;
            <a href="{{ url("unidade-medida/$model->codunidademedida") }}" data-excluir data-pergunta="Tem certeza que deseja excluir a unidade de medida '{{ $model->unidademedida }}'?" data-after-delete="location.replace(baseUrl + '/unidade-medida');"><i class="glyphicon glyphicon-trash"></i></a>           
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
            <td class="col-md-10">{{ formataCodigo($model->codunidademedida) }}</td> 
          </tr>
          <tr> 
            <th>Descrição</th> 
            <td>{{ $model->unidademedida }}</td> 
          </tr>
          <tr> 
            <th>Sigla</th> 
            <td>{{ $model->sigla }}</td> 
          </tr>
        </tbody> 
      </table>
  </div>    
</div>
<hr>
@include('includes.autor')
@stop