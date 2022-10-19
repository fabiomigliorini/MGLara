<td class="text-center">
  @if ($mp)
    <span class="label label-primary">
      {{ $mp->sku }}
    </span>
    @if (!empty($mp->precovarejo))
      <span class="label label-warning">
        R$ {{ formataNumero($mp->precovarejo, 2) }}
        @ {{ $mp->precovarejoatualizado }}
      </span>
    @endif
    @if (!empty($mp->precoatacado))
      <span class="label label-info">
        R$ {{ formataNumero($mp->precoatacado, 2) }}
        @ {{ $mp->precoatacadoatualizado }}
      </span>
    @endif
    @if (!empty($mp->saldoquantidade))
      <span class="label label-success">
        {{ formataNumero($mp->saldoquantidade, 0) }}
        @ {{ $mp->saldoquantidadeatualizado }}
      </span>
    @endif
  @else
    <del class="text-muted ">
      Sem Integração
    </del>
  @endif
</td>
