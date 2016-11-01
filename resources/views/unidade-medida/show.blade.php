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
            {!! Form::open(['method' => 'DELETE', 'id'=>'deleteId', 'route' => ['unidade-medida.destroy', $model->codunidademedida]]) !!}
            <span class="glyphicon glyphicon-trash"></span>
            {!! Form::submit('Excluir') !!}
            {!! Form::close() !!}            
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