<?php
$url_edit = env('WOO_URL_PRODUTO_EDIT');
$url_listagem = env('WOO_URL_PRODUTO_LISTAGEM');
$wps = $model->WooProdutoS()->orderBy('criacao')->get();
foreach ($wps as $wp) {
    // Carrega barrasunidade para exibição
    if (!empty($wp->codprodutobarraunidade)) {
        $wp->barrasunidade = $wp->ProdutoBarraUnidade->barras;
    } else {
        $wp->barrasunidade = null;
    }
}
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
                <th class="text-left">
                    ID
                </th>
                <th class="text-left">
                    Integração
                </th>
                <th class="text-left">
                    Exportado
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($wps as $wp)
                <?php
                // dd($wp);
                $inativo = !empty($wp->inativo);
                $exportado = !empty($wp->exportacao);
                ?>
                <tr class="{{ $inativo ? 'bg-danger' : ($exportado ? 'bg-success' : '') }}">
                    <th scope="row" rowspan="{{ ($wp->integracao == 'P') ? 2 : 1 }}">
                        @if (empty($wp->codprodutovariacao))
                            Produto Principal
                        @else
                            {{ $wp->ProdutoVariacao->variacao }}
                        @endif
                    </th>
                    <td class="text-left">
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
                    <td class="text-left">
                        {{ integracaoLabel($wp->integracao) }}
                    </td>
                    <td class="text-left">
                        {{ formataData($wp->exportacao, 'L') }}
                    </td>

                    <td class="text-right" rowspan="{{ ($wp->integracao == 'P') ? 2 : 1 }}">
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
                @if ($wp->integracao == 'P')
                    <tr class="{{ $inativo ? 'bg-danger' : ($exportado ? 'bg-success' : '') }}">
                        <td class="text-left" colspan="3">
                            @if (!empty($wp->codprodutobarraunidade))
                                Barras da Unidade {{ $wp->ProdutoBarraUnidade->barras }} |
                            @endif
                            Pacote C/{{ $wp->quantidadepacote }}
                            ({{ $wp->margempacote }}% no pacote)
                            | Emgalagem C/{{ $wp->quantidadeembalagem }}
                            ({{ $wp->margemunidade }}% na unidade)
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>
