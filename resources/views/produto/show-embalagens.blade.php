<ul class='list-group col-md-5 text-right' id="div-embalagens">
    <li class="list-group-item">
        <small class="pull-left text-muted">
            R$
        </small>
        <b style="font-size: xx-large">
        {{ formataNumero($model->preco) }}
        </b>
        <span class="text-muted">
            {{ $model->UnidadeMedida->unidademedida }}
            <a href="<?php echo url("produto-embalagem/create?codproduto={$model->codproduto}");?>">
                <i class="glyphicon glyphicon-plus"></i>
            </a>
        </span>
    </li>
    @foreach($pes as $pe)
        <li class="list-group-item">
            <small class="pull-left text-muted">
                R$
            </small>
            <b style="font-size: large">
                @if (empty($pe->preco))
                    <i class="text-muted">
                        <small>
                        ({{ formataNumero($model->preco * $pe->quantidade) }})
                        </small>
                    </i>
                @else
                    {{ formataNumero($pe->preco) }}
                @endif
            </b>
            <small class="text-muted">
                {{ $pe->UnidadeMedida->unidademedida }} com
                {{ formataNumero($pe->quantidade, 0) }}

                <a href="{{ url("produto-embalagem/$pe->codprodutoembalagem/edit") }}"><i class="glyphicon glyphicon-pencil"></i></a>
                <a href="{{ url("produto-embalagem/$pe->codprodutoembalagem") }}" data-excluir data-pergunta="Tem certeza que deseja excluir a Embalagem '{{ $pe->UnidadeMedida->unidademedida }} com {{ formataNumero($pe->quantidade, 0) }}'?" data-after-delete="recarregaDiv('div-embalagens')"><i class="glyphicon glyphicon-trash"></i></a>
            </small>
        </li>
    @endforeach
    @if(isset($pe->codprodutoembalagem))
        <li class="list-group-item">
            <small class="">
                <a href="<?php echo url("produto/{$model->codproduto}/converter-embalagem");?>">
                    Converter Embalagem em Unidade
                    <span class="glyphicon glyphicon-resize-small"></span>
                </a>
            </small>
        </li>
    @endif
</ul>
