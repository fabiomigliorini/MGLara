@extends('layouts.default')
@section('content')
<?php
$pes = $model->ProdutoEmbalagemS()->orderBy('quantidade')->get();
$pvs = $model->ProdutoVariacaoS()->orderBy(DB::raw("coalesce(variacao, '')"), 'ASC')->get();
$mps = $model->MagazordProdutoS()->get();

?>
<ol class="breadcrumb header">
{!!
  titulo(
    $model->codproduto,
    [
      url("produto") => 'Produtos',
      url("produto/$model->codproduto") => $model->produto,
      'Magazord',
    ],
    $model->inativo,
    6
  )
!!}
</ol>
<br>
{!! Form::model($model, ['method' => 'PATCH', 'class' => 'form-horizontal', 'id' => 'form-magazord-produto', 'action' => ['ProdutoController@updateMagazord', $model->codproduto] ]) !!}
  @include('errors.form_error')
  <div class="panel panel-default" id="div-variacoes">
    <table class="table table-striped table-hover">
      <thead>
        <tr>
          <th>Variação</th>
          <th class="text-center">
            {{ $model->UnidadeMedida->unidademedida }}
          </th>
          @foreach ($pes as $pe)
            <th class="text-center">
              {{ $pe->UnidadeMedida->unidademedida }}
              C/{{ formataNumero($pe->quantidade, 0) }}
            </th>
          @endforeach
        </tr>
      </thead>
      <tbody>
        @foreach ($pvs as $pv)
          <tr>
            <th scope="row">
              @if (empty($pv->variacao))
                Sem Variação
              @else
                {{$pv->variacao}}
              @endif
            </th>
            <td>
              <?php
              $mp = $mps->first(function($key, $value) use ($pv) {
                  return ($value->codprodutovariacao == $pv->codprodutovariacao)
                    && ($value->codprodutoembalagem == null);
              });
              ?>
              <input type="text" class="form-control text-center" name="sku[{{$pv->codprodutovariacao}}][null]" placeholder="SKU" value="{{ $mp->sku??null }}">
            </td>
            @foreach ($pes as $pe)
              <?php
              $mp = $mps->first(function($key, $value) use ($pv, $pe) {
                  return ($value->codprodutovariacao == $pv->codprodutovariacao)
                    && ($value->codprodutoembalagem == $pe->codprodutoembalagem);
              });
              ?>
              <td>
                <input type="text" class="form-control text-center" name="sku[{{$pv->codprodutovariacao}}][{{$pe->codprodutoembalagem}}]" placeholder="SKU" value="{{ $mp->sku??null }}">
              </td>
            @endforeach
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      {!! Form::submit("Salvar", array('class' => 'btn btn-primary')) !!}
    </div>
  </div>
{!! Form::close() !!}

@section('inscript')
<script type="text/javascript">
  $(document).ready(function() {
    $('#form-magazord-produto').on("submit", function(e) {
      var currentForm = this;
      e.preventDefault();
      bootbox.confirm("Tem certeza que deseja salvar?", function(result) {
        if (result) {
          currentForm.submit();
        }
      });
    });
  });
</script>
@endsection

@stop
