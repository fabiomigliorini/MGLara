<div id="div-embalagens">
    @foreach($model->ProdutoEmbalagemS()->orderBy('quantidade')->get() as $pe)
        <div class="col-md-12"  style="border-top: 1px dashed green">
            <h4 class="produtos-detalhe-preco-menor text-right text-success col-md-7">
                <span class="pull-left text-muted produtos-detalhe-cifrao">R$ &nbsp; </span>
                @if (empty($pe->preco))
                    <i class="text-muted">
                        ({{ formataNumero($model->preco * $pe->quantidade) }})
                    </i>
                @else
                    {{ formataNumero($pe->preco) }}                            
                @endif
            </h4>
            <span class="text-muted col-md-5">
                {{ $pe->UnidadeMedida->unidademedida }} com
                {{ formataNumero($pe->quantidade, 0) }}

                <div class="pull-right">
                    <a href="{{ url("produto-embalagem/$pe->codprodutoembalagem/edit") }}"><i class="glyphicon glyphicon-pencil"></i></a>
                    <a href="{{ url("produto-embalagem/$pe->codprodutoembalagem") }}" data-excluir data-pergunta="Tem certeza que deseja excluir a Embalagem '{{ $pe->UnidadeMedida->unidademedida }} com {{ formataNumero($pe->quantidade, 0) }}'?" data-after-delete="recarregaDiv('div-embalagens')"><i class="glyphicon glyphicon-trash"></i></a>
                </div>
            </span>
        </div>
    @endforeach
</div>
