<a href="{{ $url_listagem }}{{ urlencode($model->produto) }}" target="_blank">
    Listagem dos Produtos no Woo
</a>
&nbsp
<button type="button" class="btn btn-sm btn-default btnWoo" aria-label="Left Align"
    onclick="exportarWoo({{ $model->codproduto }})">
    <span class="glyphicon glyphicon-cloud-upload" aria-hidden="true"></span> Exportar
</button>
<a class="btn btn-sm btn-default" role="button" onclick="wooNovo()">
    <span class='glyphicon glyphicon-plus'></span> Integração Manual
</a>
&nbsp
<img width="20px" id="lblSincronizandoWoo" src="{{ URL::asset('public/img/carregando.gif') }}" style="display:none">

<!-- FORM -->
<div class="collapse" id="collapse-form-woo" style="margin-top: 20px">
    <div class='well well-sm' style="padding: 20px">

        <form action="#" class="form-horizontal" role="form" id="form-woo">

            <input type="text" name="woo_codwooproduto" id="woo_codwooproduto">

            <div class="form-group">
                <label for="woo_integracao" class="col-sm-5 control-label">Tipo de Integração</label>
                <div class="col-sm-4">
                    {!! Form::select2(
                        'woo_integracao',
                        [
                            'C' => 'Completa',
                            'P' => 'Parcial',
                        ],
                        null,
                        [
                            'style' => 'width:100%',
                            'id' => 'woo_integracao',
                            'placeholder' => 'Principal',
                        ],
                    ) !!}
                </div>
            </div>

            <div class="form-group">
                <label for="woo_codprodutovariacao" class="col-sm-5 control-label">Variação</label>
                <div class="col-sm-4">
                    <input type="hidden" class="form-control" id="woo_codproduto" name="woo_codproduto"
                        value="{{ $model->codproduto }}">

                    {{-- {!! Form::select2ProdutoVariacao('woo_codprodutovariacao', null, [
                        'style' => 'width:100%',
                        'id' => 'woo_codprodutovariacao',
                        'codproduto' => 'woo_codproduto',
                        'placeholder' => 'Principal',
                    ]) !!} --}}
                    {!! Form::select2('woo_codprodutovariacao', $vars, null, [
                        'style' => 'width:100%',
                        'id' => 'woo_codprodutovariacao',
                        'placeholder' => 'Principal',
                    ]) !!}
                </div>
            </div>

            <div class="form-group">
                <label for="woo_id" class="col-sm-5 control-label">ID Produto</label>
                <div class="col-sm-3">
                    <input type="number" class="form-control text-right" id="woo_id" name="woo_id" min="1"
                        step="1">
                </div>
            </div>


            <div class="woo-parcial">
                <hr>

                <div class="form-group ">
                    <label for="woo_idvariation" class="col-sm-5 control-label">ID Variação</label>
                    <div class="col-sm-3">
                        <input type="number" class="form-control text-right" id="woo_idvariation"
                            name="woo_idvariation" min="1" step="1">
                    </div>
                </div>

                <hr>

                <div class="form-group ">
                    <label for="woo_quantidadeembalagem" class="col-sm-5 control-label">Qtd. na Embalagem</label>
                    <div class="col-sm-3">
                        <input type="number" class="form-control text-right" id="woo_quantidadeembalagem"
                            name="woo_quantidadeembalagem" min="0" step="1" placeholder="CX C/100 UN">
                    </div>
                </div>


                <div class="form-group ">
                    <label for="woo_margemunidade" class="col-sm-5 control-label">% Margem da Unidade</label>
                    <div class="col-sm-3">
                        <input type="number" class="form-control text-right" id="woo_margemunidade"
                            name="woo_margemunidade" min="0" max="50" step="0.1"
                            placeholder="Ex: 15,5%">
                    </div>
                </div>

                <div class="form-group ">
                    <label for="woo_barrasunidade" class="col-sm-5 control-label">Barras da Unidade</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control " id="woo_barrasunidade" name="woo_barrasunidade">
                    </div>
                </div>

                <hr>

                <div class="form-group ">
                    <label for="woo_quantidadepacote" class="col-sm-5 control-label">Qtd. no Pacote</label>
                    <div class="col-sm-3">
                        <input type="number" class="form-control text-right" id="woo_quantidadepacote"
                            name="woo_quantidadepacote" min="0" step="1" placeholder="PT C/20 UN">
                    </div>
                </div>

                <div class="form-group ">
                    <label for="woo_margempacote" class="col-sm-5 control-label">% Margem pdo Pacote</label>
                    <div class="col-sm-3">
                        <input type="number" class="form-control text-right" id="woo_margempacote"
                            name="woo_margempacote" min="0" max="50" step="0.1"
                            placeholder="Ex: 7,5%">
                    </div>
                </div>

                <hr>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-5 col-sm-7">
                    <button type="submit" class="btn btn-primary">
                        <i class="glyphicon glyphicon-floppy-disk"></i> Salvar
                    </button>
                    <button type="reset" class="btn btn-default">
                        <i class="glyphicon glyphicon-cancel"></i> Cancelar
                    </button>
                </div>
            </div>

        </form>

    </div>
</div>




<script type="text/javascript">
    // Sua variável wps (convertida de Collection para JSON, reindexada por codwooproduto)
    var wps = <?php echo json_encode($wps->keyBy('codwooproduto')); ?>;

    /**
     * Gerencia a visibilidade dos campos com base no Tipo de Integração.
     * Mostra os campos da classe .woo-parcial se a integração for 'P' (Parcial).
     */
    function gerenciarIntegracaoWoo() {
        var integracaoSelecionada = $('#woo_integracao').val();

        // Se a integração for 'P' (Parcial), mostra os campos
        if (integracaoSelecionada === 'P') {
            $('.woo-parcial').slideDown(300); // Exibe com slideDown
        } else {
            // Se for 'C' (Completa) ou nulo, esconde os campos
            $('.woo-parcial').slideUp(300); // Esconde com slideUp
        }
    }

    /**
     * Função de exportação para o Woo (mantida a original)
     */
    function exportarWoo(codproduto) {
        urlApi = "{{ env('MGSPA_API_URL') }}";
        bootbox.confirm("Tem certeza que deseja exportar para o Woo?", function(result) {
            if (result) {
                $.ajax({
                        type: 'POST',
                        url: urlApi + 'woo/produto/' + codproduto + '/exportar',
                        headers: {
                            'Accept': 'application/json'
                        },
                        beforeSend: function(xhr) {
                            $('.btnWoo').prop('disabled', true);
                            $('#lblSincronizandoWoo').show();
                        }
                    })
                    .done(function(data) {
                        $('.btnWoo').prop('disabled', false);
                        $('#lblSincronizandoWoo').hide();
                        recarregaDiv('div-woo-listagem')
                        bootbox.alert('Exportação Realizada!');
                    })
                    .fail(function(data) {
                        console.log(data);
                        $('.btnWoo').prop('disabled', false);
                        $('#lblSincronizandoWoo').hide();
                        recarregaDiv('div-woo-listagem')
                        bootbox.alert('Falha na exportação! Consulte o Log do Console para mais detalhes!');
                    });
            }
        });
    }

    /**
     * Função wooEditar (mantida a original)
     */
    function wooEditar(codwooproduto) {
        // 1. Acessa o objeto do produto
        var produto = wps[codwooproduto];

        // Verifica se o produto foi encontrado
        if (!produto) {
            console.error('Produto não encontrado para o codwooproduto:', codwooproduto);
            return;
        }

        // 2. Expande o formulário se estiver recolhido (Melhora a UX)
        if (!$('#collapse-form-woo').hasClass('in')) {
            $('#collapse-form-woo').collapse('show');
        }

        $('#woo_integracao').select2('val', produto.integracao);
        gerenciarIntegracaoWoo();
        $('#woo_codprodutovariacao').select2('val', produto.codprodutovariacao);
        $('#woo_codwooproduto').val(produto.codwooproduto);
        $('#woo_id').val(produto.id);
        $('#woo_idvariation').val(produto.idvariation);
        $('#woo_quantidadeembalagem').val(produto.quantidadeembalagem);
        $('#woo_margemunidade').val(produto.margemunidade);
        $('#woo_barrasunidade').val(produto.codprodutobarraunidade);
        $('#woo_quantidadepacote').val(produto.quantidadepacote);
        $('#woo_margempacote').val(produto.margempacote);
        $('#woo_codproduto').val(produto.codproduto);


    }

    function wooNovo() {
        // 2. Expande o formulário se estiver recolhido (Melhora a UX)
        if (!$('#collapse-form-woo').hasClass('in')) {
            $('#collapse-form-woo').collapse('show');
        }
        $('#woo_integracao').select2('val', 'C');
        gerenciarIntegracaoWoo();
        $('#woo_codprodutovariacao').select2('val', '');
        $('#woo_codwooproduto').val(null);
        $('#woo_id').val(null);
        $('#woo_idvariation').val(null);
        $('#woo_quantidadeembalagem').val(null);
        $('#woo_margemunidade').val(null);
        $('#woo_barrasunidade').val(null);
        $('#woo_quantidadepacote').val(null);
        $('#woo_margempacote').val(null);
        $('#woo_codproduto').val(null);
    }

    // Inicialização principal do jQuery
    $(document).ready(function() {
        // 1. Anexa o listener de 'change' ao campo Select2.
        // Isso garante que ele rode APÓS a inicialização do Select2
        $('#woo_integracao').on('change', function() {
            gerenciarIntegracaoWoo();
        });

        // 2. Chama a função na carga inicial da página.
        // Isso garante o estado correto dos campos se já houver um valor selecionado.
        gerenciarIntegracaoWoo();
    });
</script>
