@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="<?php echo url("estoque-movimento/$model->codestoquemovimento/edit");?>"><span class="glyphicon glyphicon-pencil"></span> Alterar</a></li> 
            <li>
                {!! Form::open(['method' => 'DELETE', 'route' => ['estoque-movimento.destroy', $model->codestoquemovimento]]) !!}
                <span class="glyphicon glyphicon-trash"></span>
                {!! Form::submit('Excluir') !!}
                {!! Form::close() !!}
            </li>
        </ul>
    </div>
</nav>
<h1 class="header">Movimento #{{ $model->codestoquemovimento }}</h1>
<hr>
<div class="row">
  <div class="col-lg-12">
      <table class="detail-view table table-striped table-condensed"> 
        <tbody>  
          <tr> 
            <th class="col-md-2">#</th> 
            <td class="col-md-10">{{ formataCodigo($model->codestoquemovimento) }}</td> 
          </tr>
          <tr> 
            <th>Tipo</th> 
            <td>{{ $model->EstoqueMovimentoTipo->descricao or '' }}</td> 
          </tr>
          <tr> 
            <th>Movimento Estoque Origem</th> 
            <td>{{ $model->EstoqueMovimentoOrigem->codestoquemovimentoorigem or '' }}</td> 
          </tr>
          <tr> 
            <th>Data</th> 
            <td><?php if(!empty($model->data)) echo formataData($model->data, 'M');?></td> 
          </tr>
          <tr> 
            <th>Quantidade Entrada</th> 
            <td>{{ $model->entradaquantidade }}</td> 
          </tr>
          <tr> 
            <th>Valor Entrada</th> 
            <td>{{ $model->entradavalor }}</td> 
          </tr>
          <tr> 
            <th>Quantidade Saída</th> 
            <td>{{ $model->saidaquantidade }}</td> 
          </tr> 
          <tr> 
            <th>Valor Saída</th> 
            <td>{{ $model->saidavalor }}</td> 
          </tr>          
        </tbody> 
      </table>
  </div>    
</div>
<hr>
@include('includes.autor')
@section('inscript')
<script type="text/javascript">
$(document).ready(function() {
    $('#Delete').click(function (e) {
        bootbox.confirm("Deseja excluir esse registro?", function(result) {
            if (result)
            {
                $('form').submit();
            }
        }); 
    });
}
</script>
@endsection
@stop