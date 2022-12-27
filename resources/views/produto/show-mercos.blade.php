<?php
$mps = $model->MercosProdutoS()->whereNull('inativo')->get();
$url_painel_listagem = env('MERCOS_URL_PAINEL_LISTAGEM_PRODUTOS') . '?nome_codigo=' . urlencode(formataCodigo($model->codproduto, 6));
?>

<a href="{{$url_painel_listagem}}" target="_blank">
    Listagem dos Produtos no Mercos
</a>
&nbsp
<button type="button" class="btn btn-sm btn-default btnMercos" aria-label="Left Align" onclick="atualizarTodosMercosProduto({{$model->codproduto}})">
    <span class="glyphicon glyphicon-cloud-upload" aria-hidden="true"></span> Atualizar Todos
</button>
&nbsp
<img width="20px" id="lblSincronizandoMercos" src="{{ URL::asset('public/img/carregando.gif') }}" style="display:none">
<br>
<br>
<div class="panel panel-default" id="div-mercos">
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
          <?php
          $mp = $mps->first(function($key, $value) use ($pv) {
            return ($value->codprodutovariacao == $pv->codprodutovariacao)
            && ($value->codprodutoembalagem == null);
          });
          ?>
          <?php
          $codproduto = $model->codproduto;
          $codprodutovariacao = $pv->codprodutovariacao;
          $codprodutoembalagem = 0;
          ?>
          @include('produto.show-mercos-item')
          @foreach ($pes as $pe)
            <?php
            $codprodutoembalagem = $pe->codprodutoembalagem;
            $mp = $mps->first(function($key, $value) use ($pv, $pe) {
                return ($value->codprodutovariacao == $pv->codprodutovariacao)
                  && ($value->codprodutoembalagem == $pe->codprodutoembalagem);
            });
            ?>
            @include('produto.show-mercos-item')
          @endforeach
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
