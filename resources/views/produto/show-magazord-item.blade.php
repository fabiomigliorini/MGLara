<td class="text-center">
  @if ($mp)
    <b>
        {{ $mp->sku }}
    </b>
    <small class="text-muted">
        <br />
        @if (!empty($mp->precovarejo))
            <abbr title="{{ formataData($mp->precovarejoatualizado, 'd/m/Y H:i:s') }}">
                R$ {{ formataNumero($mp->precovarejo, 2) }}
            </abbr>
        @endif
        @if (!empty($mp->precoatacado))
            |
            <abbr title="{{ formataData($mp->precoatacadoatualizado, 'd/m/Y H:i:s') }}">
                R$ {{ formataNumero($mp->precoatacado, 2) }}
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
    <del class="text-muted ">
      Sem Integração
    </del>
  @endif
</td>
