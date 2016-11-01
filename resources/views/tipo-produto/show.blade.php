@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
    {!! 
        titulo(
            $model->codtipoproduto,
            [
                url("tipo-produto") => 'Tipos de Produto',
                $model->tipoproduto
            ],
            $model->inativo
        ) 
    !!}    
    <li class='active'>
        <small>
            <a title="Novo Tipo" href="{{ url('tipo-produto/create') }}"><span class="glyphicon glyphicon-plus"></span></a>
            &nbsp;
            <a title="Alterar" href="{{ url("tipo-produto/$model->codtipoproduto/edit") }}"><span class="glyphicon glyphicon-pencil"></span></a>
            {!! Form::open(['method' => 'DELETE', 'id'=>'deleteId', 'route' => ['tipo-produto.destroy', $model->codtipoproduto]]) !!}
            <i class="glyphicon glyphicon-trash"></i>
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
            <td class="col-md-10">{{ formataCodigo($model->codtipoproduto) }}</td> 
          </tr>
          <tr> 
            <th>Tipo produto</th> 
            <td>{{ $model->tipoproduto }}</td> 
          </tr>
        </tbody> 
      </table>
  </div>    
</div>
<hr>
@include('includes.autor')
@stop