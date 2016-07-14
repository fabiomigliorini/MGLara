<div id="div-negocios" >
    @if (isset($npbs))
        <div class="list-group list-group-striped list-group-hover" id="div-negocios-listagem">
            @foreach($npbs as $npb)
                <?php
                $quantidade = $npb->quantidade;
                $valor = $npb->valorunitario;
                if (!empty($npb->ProdutoBarra->codprodutoembalagem))
                {
                    $quantidade *= $npb->ProdutoBarra->ProdutoEmbalagem->quantidade;
                    $valor /= $npb->ProdutoBarra->ProdutoEmbalagem->quantidade;
                }
                ?>
                <div class='list-group-item'>
                    <div class='row item'>
                        <small>
                            <div class='col-sm-2 '>
                                <div>
                                    <a href="{{ url('negocio', ['id'=>$npb->codnegocio]) }}">
                                        {{ formataCodigo($npb->codnegocio) }}
                                    </a>
                                </div>
                                <div class='text-muted'>
                                    {{ formataData($npb->Negocio->lancamento) }}
                                </div>
                            </div>
                            <div class='col-sm-4'>
                                <div class=''>
                                    <a href='{{ url('pessoa', ['id'=>$npb->Negocio->codpessoa]) }}'>
                                        {{ $npb->Negocio->Pessoa->fantasia }}
                                    </a>
                                </div>
                                <div class='text-muted'>
                                    <a href='{{ url('natureza-operacao', ['id'=>$npb->Negocio->codnaturezaoperacao]) }}'>
                                        {{ $npb->Negocio->NaturezaOperacao->naturezaoperacao }}
                                    </a>
                                </div>
                            </div>
                            <div class='col-sm-3'>
                                <div class='text-muted'>
                                    <a href='{{ url('filial', ['id'=>$npb->Negocio->codfilial]) }}'>
                                        {{ $npb->Negocio->Filial->filial }}
                                    </a>
                                </div>
                                <div>
                                    {{ $npb->ProdutoBarra->ProdutoVariacao->variacao }}
                                </div>
                                <div class='text-muted'>
                                    {{ $npb->ProdutoBarra->barras }}
                                </div>
                            </div>
                            <div class='col-sm-3'>
                                <div class='text-right'>
                                    <small class='pull-left'>R$</small> {{ formataNumero($valor, 2) }} 
                                </div>
                                <div class='text-right text-muted'>
                                    <small class='pull-left'>{{ $model->UnidadeMedida->sigla }}</small> {{ formataNumero($quantidade, 3) }} 
                                </div>
                            </div>
                        </small>
                    </div>
                </div>
            @endforeach
        </div>
        <?php
        $req = Request::all();
        $req['_div'] = 'div-negocios';
        ?>
        <div id="npb_paginacao" class='hidden'>{!! $npbs->appends($req)->render() !!}</div>
        <!--
        <div id="npb_paginacao" class=''>{!! $npbs->appends($req)->render() !!}</div>
        -->
    @endif
</div>