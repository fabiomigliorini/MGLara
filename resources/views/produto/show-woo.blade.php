<?php
$url_edit = env('WOO_URL_PRODUTO_EDIT');
$url_listagem = env('WOO_URL_PRODUTO_LISTAGEM');
$wps = $model->WooProdutoS()->whereNull('inativo')->orderBy('criacao')->get();
function integracaoLabel($int)
{
    switch ($int) {
        case 'C':
            return 'Completa';
            break;
        case 'P':
            return 'Parcial';
            break;
        default:
            # code...
            break;
    }
}

// dd($wps);

?>


<a href="{{ $url_listagem }}{{ urlencode($model->produto) }}" target="_blank">
    Listagem dos Produtos no Woo
</a>
&nbsp
<button type="button" class="btn btn-sm btn-default btnWoo" aria-label="Left Align"
    onclick="exportarWoo({{ $model->codproduto }})">
    <span class="glyphicon glyphicon-cloud-upload" aria-hidden="true"></span> Exportar
</button>
&nbsp
<img width="20px" id="lblSincronizandoWoo" src="{{ URL::asset('public/img/carregando.gif') }}" style="display:none">
<br>
<br>
<div class="panel panel-default" id="div-woo">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>Tipo</th>
                <th class="text-right">
                    ID
                </th>
                <th class="text-right">
                    Integração
                </th>
                <th class="text-right">
                    Exportado
                </th>
                <th class="text-right">
                    Qtd Emb
                </th>
                <th class="text-right">
                    % UN
                </th>
                <th class="text-right">
                    Qtd PT
                </th>
                <th class="text-right">
                    % PT
                </th>
                <th class="text-right">
                    Barras UN
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($wps as $wp)
                <tr>
                    <th scope="row">
                        @if (empty($wp->codprodutovariacao))
                            Principal
                        @else
                            {{ $wp->ProdutoVariacao->variacao }}
                        @endif
                    </th>
                    <td class="text-right">
                        @if (empty($wp->codprodutovariacao))
                            <a href="{{ $url_edit }}{{ $wp->id }}" target="_blank">
                                {{ $wp->id }}
                            </a>
                        @else
                            {{ $wp->id }}
                        @endif
                        @if (!empty($wp->idvariation))
                            / {{ $wp->idvariation }}
                        @endif
                    </td>
                    <td class="text-right">
                        {{ integracaoLabel($wp->integracao) }}
                    </td>
                    <td class="text-right">
                        {{ $wp->exportacao }}
                    </td>
                    <td class="text-right">
                        {{ $wp->quantidadeembalagem }}
                    </td>
                    <td class="text-right">
                        {{ $wp->margemunidade }}%
                    </td>
                    <td class="text-right">
                        {{ $wp->quantidadepacote }}
                    </td>
                    <td class="text-right">
                        {{ $wp->margempacote }}%
                    </td>
                    <td class="text-right">
                        @if (!empty($wp->codprodutobarraunidade))
                            {{ $wp->ProdutoBarra->barras }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
