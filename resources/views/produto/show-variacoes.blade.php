<div class="panel panel-default" id="div-variacoes">
    <ul class="list-group list-group-striped list-group-hover">
        @foreach ($pvs as $pv)
            <li class="list-group-item">
                @if (!empty($pv->codprodutoimagem))
                    <?php $pi = MGLara\Models\ProdutoImagem::findOrFail($pv->codprodutoimagem); ?>
                    <img src="<?php echo URL::asset('public/imagens/'.$pi->Imagem->observacoes);?>" id="{{$pi->Imagem->codimagem}}" style='max-width: 70px;' class="pull-left img-circle">
                @endif
                <strong>
                    @if (!empty($pv->variacao))
                        {{ $pv->variacao }}
                    @else
                        <i class='text-muted'>{ Sem Variação }</i>
                    @endif
                    @if (!empty($pv->codmarca))
                        <a href="{{ url("marca/$pv->codmarca") }}">
                            {{ $pv->Marca->marca }}
                        </a>
                    @endif
                </strong>
                <div class="pull-right">
                    {{ $pv->referencia }}
                    &nbsp;
                    <a href="{{ url("produto-variacao/$pv->codprodutovariacao/edit") }}"><i class="glyphicon glyphicon-pencil"></i></a>
                    <a href="{{ url("produto-variacao/$pv->codprodutovariacao") }}" data-excluir data-pergunta="Tem certeza que deseja excluir a variação '{{ $pv->variacao }}'?" data-after-delete="recarregaDiv('div-variacoes');"><i class="glyphicon glyphicon-trash"></i></a>
                </div>

                <div class="row">
                <?php
                $pbs = $pv->ProdutoBarraS()->leftJoin('tblprodutoembalagem as pe', 'pe.codprodutoembalagem', '=', 'tblprodutobarra.codprodutoembalagem')
                   ->orderBy(DB::raw('coalesce(pe.quantidade, 0)'), 'ASC')
                   ->with('ProdutoEmbalagem')->get();
                ?>
                @foreach ($pbs as $pb)
                    <div class="col-md-5 small">
                        {{ $pb->barras }}
                        <span class='text-muted'>
                            {{ $pb->referencia }}
                            {{ $pb->variacao }}
                        </span>
                        <small class="text-muted pull-right">
                            @if (!empty($pb->codprodutoembalagem))
                                {{ $pb->ProdutoEmbalagem->descricao }}
                            @else
                                {{ $model->UnidadeMedida->sigla }}
                            @endif
                            &nbsp;
                            <a href="{{ url("produto-barra/{$pb->codprodutobarra}/edit") }}"><i class="glyphicon glyphicon-pencil"></i></a>
                            <a href="{{ url("produto-barra/{$pb->codprodutobarra}") }}" data-excluir data-pergunta="Tem certeza que deseja excluir o Código de Barras '{{ $pb->barras }}'?" data-after-delete="recarregaDiv('div-variacoes');"><i class="glyphicon glyphicon-trash"></i></a>
                        </small>
                    </div>
                @endforeach
                </div>
            </li>
        @endforeach
    </ul>
</div>
