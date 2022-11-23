<?php
$mps = $model->MercosProdutoS()->whereNull('inativo')->get();
?>
<!-- <a href="<?php echo url("produto/{$model->codproduto}/mercos");?>">Editar Integração com Mercos <span class="glyphicon glyphicon-pencil"></span></a> | -->
<!-- <a href="#" id="btnMercosSincroniza">Sincronizar com Mercos <span class="glyphicon glyphicon-refresh"></span></a> -->
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
