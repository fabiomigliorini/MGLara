<div class="row subregistro">
    <b class="col-md-2 text-right">
        {{ formataNumero(empty($pe->preco) ? $row->preco * $pe->quantidade : $pe->preco) }}
    </b>
    <small class="col-md-2">
        {{ $pe->UnidadeMedida->sigla . " " . $pe->descricao }}
    </small>
    @foreach ($row->ProdutoBarras()->where('codprodutoembalagem', $pe->codprodutoembalagem)->get() as $pb)
        <small class="col-md-8 pull-right text-muted"> 
            <div class="col-md-3">
                {{ $pb->barras }}
            </div>
            <div class="col-md-5">
                {{ $pb->variacao }}
            </div>
            <div class="col-md-4">
                <b>{{ $pb->Marca->marca or '' }}</b>
                {{ $pb->referencia }}
            </div>
        </small>
    @endforeach
</div>