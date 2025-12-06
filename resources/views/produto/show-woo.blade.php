<?php
$url_edit = env('WOO_URL_PRODUTO_EDIT');
$url_listagem = env('WOO_URL_PRODUTO_LISTAGEM');
$wps = $model->WooProdutoS()->orderBy('criacao')->get();
$vars = $model
    ->ProdutoVariacaoS()
    ->orderBy('variacao')
    ->select(['codprodutovariacao', 'variacao'])
    ->get()
    ->pluck('variacao', 'codprodutovariacao');
$vars[''] = 'Produto Principal';
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
?>

@include('produto.show-woo-cabecalho')


<div class="panel panel-default" id="div-woo-listagem" style="margin-top: 20px">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Variação</th>
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
                <?php
                // dd($wp);
                $inativo = !empty($wp->inativo);
                ?>
                <tr class="{{ $inativo ? 'bg-danger' : '' }}">
                    <th scope="row">
                        @if (empty($wp->codprodutovariacao))
                            Produto Principal
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
                    <td class="text-right">
                        <div class="btn-group" role="group" aria-label="...">
                            <button class="btn btn-sm btn-default" onclick="wooEditar({{ $wp->codwooproduto }})">
                                <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                            </button>
                            @if ($inativo)
                                <button class="btn btn-sm btn-default" onclick="wooAtivar({{ $wp->codwooproduto }})">
                                    <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                                </button>
                            @else
                                <button class="btn btn-sm btn-default" onclick="wooInativar({{ $wp->codwooproduto }})">
                                    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
