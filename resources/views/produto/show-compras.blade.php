<div id="div-compras">
    <div class="list-group list-group-striped list-group-hover" id="div-notasfiscais-listagem">

        @foreach ($compras as $compra)
            <div class='list-group-item'>
                <div class='row item'>
                    <small>
                        <div class='col-sm-1 text-muted'>
                            <a href="{{ env('MGSIS_URL') }}/index.php?r=nfeTerceiro/view&id={{ $compra->codnfeterceiro }}"
                                target='_blank'>
                                {{ formataData($compra->emissao) }}
                                <br>
                                @if ($compra->entrada)
                                    {{ formataData($compra->entrada) }}
                                @else
                                    Transito
                                @endif
                            </a>
                            <br>
                        </div>
                        <div class='col-sm-5 text-muted'>
                            {{ $compra->cprod }}
                            <br>
                            {{ $compra->xprod }}
                        </div>
                        <div class='col-sm-3 text-muted text-right'>
                            R$ {{ formataNumero($compra->vuncom, 2) }}
                            @if ($compra->voutro > 0)
                                + {{ formataNumero($compra->voutro, 2) }} (Outros)
                            @endif
                            @if ($compra->vfrete > 0)
                                + {{ formataNumero($compra->vfrete, 2) }} (Frete)
                            @endif
                            @if ($compra->vseg > 0)
                                + {{ formataNumero($compra->vseg, 2) }} (Seguro)
                            @endif
                            @if ($compra->vdesc > 0)
                                - {{ formataNumero($compra->vdesc, 2) }} (Desc)
                            @endif
                            @if ($compra->complemento > 0)
                                + {{ formataNumero($compra->complemento, 2) }} (Compl)
                            @endif
                            @if ($compra->ipipipi > 0)
                                + {{ formataNumero($compra->ipipipi, 2) }}% (IPI)
                            @endif

                            @if ($compra->vuncom != $compra->valortotal)
                                <br>
                                R$ {{ formataNumero($compra->valortotal, 2) }} Total
                            @endif
                            <br>

                            {{ formataNumero($compra->qcom, 0) }}
                            {{ $compra->ucom }}
                            @if ($compra->embalagem > 1)
                                C/{{ formataNumero($compra->embalagem, 0) }}
                            @endif
                        </div>
                        <div class='col-sm-2 text-muted text-right'>
                            R$ {{ formataNumero($compra->valortotal / $compra->embalagem, 2) }}
                            <br>
                            {{ formataNumero($compra->quantidadetotal, 0) }} {{ $model->UnidadeMedida->sigla }}
                        </div>
                        <div class='col-sm-1 text-muted text-right'>
                            {{ formataNumero($compra->margem) }}%
                        </div>
                    </small>
                </div>
            </div>
        @endforeach
    </div>
</div>
