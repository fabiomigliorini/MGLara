<div id="div-notasfiscais" >
    @if (isset($nfpbs))
        <div class="list-group group-list-striped group-list-hover" id="div-notasfiscais-listagem">
            @foreach($nfpbs as $nfpb)
                <?php
                $quantidade = $nfpb->quantidade;
                $valor = $nfpb->valorunitario;
                if (!empty($nfpb->ProdutoBarra->codprodutoembalagem))
                {
                    $quantidade *= $nfpb->ProdutoBarra->ProdutoEmbalagem->quantidade;
                    $valor /= $nfpb->ProdutoBarra->ProdutoEmbalagem->quantidade;
                }
                ?>            
                <div class='list-group-item'>
                    <div class='row item'>
                        <small>
                            <div class='col-sm-2 '>
                                <div>
                                    <a href="{{ url('negocio', ['id'=>$nfpb->codnegocio]) }}">
                                        {{ formataCodigo($nfpb->codnegocio) }}
                                    </a>
                                </div>
                                <div class='text-muted'>
                                    {{ formataData($nfpb->NotaFiscal->saida) }}
                                </div>
                            </div>
                            <div class='col-sm-4'>
                                <div class=''>
                                    <a href='{{ url('pessoa', ['id'=>$nfpb->NotaFiscal->codpessoa]) }}'>
                                        {{ $nfpb->NotaFiscal->Pessoa->fantasia }}
                                    </a>
                                </div>
                                <div class='text-muted'>
                                    <a href='{{ url('natureza-operacao', ['id'=>$nfpb->NotaFiscal->codnaturezaoperacao]) }}'>
                                        {{ $nfpb->NotaFiscal->NaturezaOperacao->naturezaoperacao }}
                                    </a>
                                </div>
                            </div>
                            <div class='col-sm-3'>
                                <div class='text-muted'>
                                    <a href='{{ url('filial', ['id'=>$nfpb->NotaFiscal->codfilial]) }}'>
                                        {{ $nfpb->NotaFiscal->Filial->filial }}
                                    </a>
                                </div>
                                <div>
                                    {{ $nfpb->ProdutoBarra->ProdutoVariacao->variacao }}
                                </div>
                                <div class='text-muted'>
                                    {{ $nfpb->ProdutoBarra->barras }}
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
        $req['_div'] = 'div-notasfiscais';
        ?>
        <div id="nfpb_paginacao" class='hidden'>{!! $nfpbs->appends($req)->render() !!}</div>
        <!--
        <div id="nfpb_paginacao" class=''>{!! $nfpbs->appends($req)->render() !!}</div>
        -->
    @endif
</div>