<div class="panel panel-default" id="div-variacoes">
    <ul class="list-group list-group-striped list-group-hover">
        @foreach ($pvs as $pv)
            <li class="list-group-item">
                @if (!empty($pv->codprodutoimagem))
                    <?php $pi = MGLara\Models\ProdutoImagem::findOrFail($pv->codprodutoimagem); ?>
                    <img src="<?php echo URL::asset('public/imagens/'.$pi->Imagem->observacoes);?>" id="{{$pi->Imagem->codimagem}}" style='max-width: 60px;' class="pull-left img-circle">
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
                    @if(!empty($pv->descontinuado))
                    <span class='text-danger'>
                  descontinuado desde {{ formataData($pv->descontinuado, 'L') }}
              </span>
                    @endif
                </strong>
                <div class="pull-right">
                    {{ $pv->referencia }}
                    &nbsp;
                    <a href="{{ url("produto-variacao/$pv->codprodutovariacao/edit") }}"><i class="glyphicon glyphicon-pencil"></i></a>
                    <a href="{{ url("produto-variacao/$pv->codprodutovariacao") }}" data-excluir data-pergunta="Tem certeza que deseja excluir a variação '{{ $pv->variacao }}'?" data-after-delete="recarregaDiv('div-variacoes');"><i class="glyphicon glyphicon-trash"></i></a>
                    @if(empty($pv->descontinuado))
            <a href="{{ url('produto-variacao/descontinuar') }}" data-descontinuar data-codigo="{{ $pv->codprodutovariacao }}" data-acao="descontinuar" data-pergunta="Tem certeza que deseja descontinuar a variação {{ $model->produto }} {{ $pv->variacao }}? " data-after-inativar="location.reload()"><span class="glyphicon glyphicon-ban-circle"></span></a>
            @else
            <a href="{{ url('produto-variacao/descontinuar') }}" data-descontinuar data-codigo="{{ $pv->codprodutovariacao }}" data-acao="ativar" data-pergunta="Tem certeza que deseja ativar a variação {{ $model->produto }} {{ $pv->variacao }}? " data-after-inativar="location.reload()"><span class="glyphicon glyphicon-ok-sign"></span></a>
            @endif
                </div>

                <div class="row">
                <?php
                $pbs = $pv->ProdutoBarraS()->leftJoin('tblprodutoembalagem as pe', 'pe.codprodutoembalagem', '=', 'tblprodutobarra.codprodutoembalagem')
                   ->orderBy(DB::raw('coalesce(pe.quantidade, 0)'), 'ASC')
                   ->with('ProdutoEmbalagem')->get();
                ?>
                @foreach ($pbs as $pb)
                    <div class="col-md-5 small">
                        @if (substr($pb->barras, 0, 3) == '234')
                            <b class="text-danger">
                                {{ $pb->barras }}
                            </b>
                        @else
                            <b class="text-success">
                                {{ $pb->barras }}
                            </b>
                        @endif
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
