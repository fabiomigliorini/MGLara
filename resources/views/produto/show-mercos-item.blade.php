<td class="text-center">
    <button type="button" class="btn btn-sm btn-default btnMercos" aria-label="Left Align" onclick="criarMercosProduto({{$codproduto}}, {{$codprodutovariacao}}, {{$codprodutoembalagem}})">
        <span class="glyphicon glyphicon-cloud-upload" aria-hidden="true"></span>
    </button>
  @if ($mp)
      <?php
      $url_painel = env('MERCOS_URL_PAINEL') . '/produtos/' . $mp->produtoid . '/alterar/';
      $url_b2b = env('MERCOS_URL_B2B') . '/produtos/' . $mp->produtoid;
      ?>
      <!-- <b>
          {{ $mp->produtoid }}
      </b> -->
      <a href="{{$url_painel}}" target="_blank">
          Painel
      </a>
      <a href="{{$url_b2b}}" target="_blank">
          B2B
      </a>
    <small class="text-muted">
        <br />
        @if (!empty($mp->preco))
            <abbr title="{{ formataData($mp->precoatualizado, 'd/m/Y H:i:s') }}">
                R$ {{ formataNumero($mp->preco, 2) }}
            </abbr>
        @endif
        @if (!empty($mp->saldoquantidade))
            |
            <abbr title="{{ formataData($mp->saldoquantidadeatualizado, 'd/m/Y H:i:s') }}">
                {{ formataNumero($mp->saldoquantidade, 0) }}
            </abbr>
        @endif
    </small>
  @else
      <!-- <b>
          Sem Integração
      </b> -->
  @endif
</td>
