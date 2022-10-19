<?php
$mps = $model->MagazordProdutoS()->get();
?>
<a href="<?php echo url("produto/{$model->codproduto}/magazord");?>">Editar Integração com Magazord <span class="glyphicon glyphicon-pencil"></span></a>
<br>
<br>
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
          <?php
          $mp = $mps->first(function($key, $value) use ($pv) {
            return ($value->codprodutovariacao == $pv->codprodutovariacao)
            && ($value->codprodutoembalagem == null);
          });
          ?>
          @include('produto.show-magazord-item')
          @foreach ($pes as $pe)
            <?php
            $mp = $mps->first(function($key, $value) use ($pv, $pe) {
                return ($value->codprodutovariacao == $pv->codprodutovariacao)
                  && ($value->codprodutoembalagem == $pe->codprodutoembalagem);
            });
            ?>
            @include('produto.show-magazord-item')
          @endforeach
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
