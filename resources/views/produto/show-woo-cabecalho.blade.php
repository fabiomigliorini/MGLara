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

<!-- FORM -->
<div class="collapse" id="collapse-form-woo" style="margin-top: 20px">
    <div class='well well-sm' style="padding: 20px">

        <form action="#" class="form-horizontal" role="form" id="form-woo"
            style="background-color: #ddddddb4; padding: 15px; border-radius: 5px;">
            <input type="hidden" class="form-control" id="woo_codproduto" name="woo_codproduto"
                class="form-control text-right" value="{{ $model->codproduto }}">
            <input type="hidden" class="form-control" id="woo_codwooproduto" name="woo_codwooproduto"
                class="form-control text-right" value="{{ $model->codwooproduto }}">
            {{-- <h1>{{ $model->codproduto }}</h1> --}}
            {{-- <input type="text" class="form-control" id="woo_codwooproduto" name="woo_codwooproduto" value="{{ $model->codproduto }}"> --}}
            {{-- <div class="form-group">
                <label for="woo_codproduto" class="col-sm-5 control-label">Código do Produto interno</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" id="woo_codproduto" name="woo_codproduto"
                            class="form-control text-right" value="{{ $model->codproduto }}">
                    </div>
            </div> --}}
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
                            'required' => 'required',
                        ],
                    ) !!}
                </div>
            </div>

            <div class="form-group">
                <label for="woo_codprodutovariacao" class="col-sm-5 control-label">Variação</label>
                <div class="col-sm-4">


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
                        'required' => 'required',
                    ]) !!}
                </div>
            </div>

            <div class="form-group">
                <label for="woo_id" class="col-sm-5 control-label">ID Produto</label>
                <div class="col-sm-3">
                    <input type="number" class="form-control text-right" id="woo_id" name="woo_id" min="1"
                        step="1" required>
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
                    <button type="reset" class="btn btn-default"
                        onclick="$('#collapse-form-woo').collapse('hide')">
                        <i class="glyphicon glyphicon-cancel"></i> Cancelar
                    </button>
                    <button type="button" class="btn btn-danger btn-delete-woo" style="display: none;"
                        onclick="deletarWoo()">
                        <i class="glyphicon glyphicon-trash"></i> Deletar
                    </button>
                </div>
                {{-- <div class="col-sm-offset-5 col-sm-7">
                    <button type="submit" class="btn btn-primary">
                        <i class="glyphicon glyphicon-floppy-disk"></i> Salvar
                    </button>
                    <button type="reset" class="btn btn-default">
                        <i class="glyphicon glyphicon-cancel"></i> Cancelar
                    </button>
                </div> --}}
            </div>

        </form>

    </div>
</div>




<script type="text/javascript">
    // Sua variável wps (convertida de Collection para JSON, reindexada por codwooproduto)
    var wps = <?php echo json_encode($wps->keyBy('codwooproduto')); ?>;
    var urlMGspaApi = "{{ env('MGSPA_API_URL') }}";

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
        bootbox.confirm("Tem certeza que deseja exportar para o Woo?", function(result) {
                if (result) {
                    $.ajax({
                            type: 'POST',
                            url: urlMGspaApi + 'woo/produto/' + codproduto + '/exportar',
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
                                try {
                                    // var Error = JSON.parse(data.responseJSON.message);
                                    // bootbox.alert(Error.message)
                                    bootbox.alert(data.responseJSON.message)
                                } catch (erro) {
                                    bootbox.alert(
                                        'Falha na exportação! Consulte o Log do Console para mais detalhes!');
                                }


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
        // $('#woo_barrasunidade').val(produto.codprodutobarraunidade);
        $('#woo_barrasunidade').val(produto.barrasunidade);
        $('#woo_quantidadepacote').val(produto.quantidadepacote);
        $('#woo_margempacote').val(produto.margempacote);
        // $('#woo_codproduto').val(produto.codproduto);


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
        // $('#woo_codproduto').val(null);
    }

    function wooSerializarFormulario() {
        var dados = {};
        // O método serializeArray do jQuery pega todos os campos do form
        $('#form-woo').serializeArray().forEach(function(item) {
            var chave = item.name.replace('woo_', '');
            var valor = item.value;

            // Converte valores vazios/nulos para null para o backend PHP/Laravel
            if (valor === "") {
                valor = null;
            }

            // Atribui o valor ao objeto, usando a chave sem o prefixo 'woo_'
            dados[chave] = valor;
        });

        // Remove a chave 'id', pois ela não é enviada na API (usamos codwooproduto na URL)
        // delete dados.id;

        // Remove 'codwooproduto' do corpo, pois ele vai na URL
        delete dados.codwooproduto;

        return dados;
    }

    function wooPostPut() {
        var codwooproduto = $('#woo_codwooproduto').val();
        var method = codwooproduto ? 'PUT' : 'POST'; // PUT se houver codwooproduto, POST se for novo
        var endpoint = urlMGspaApi + 'woo/produto/' + (codwooproduto || ''); // Adiciona o ID se for PUT

        var dadosParaEnvio = wooSerializarFormulario();
        // console.log()
        console.log(dadosParaEnvio);
        // return;

        // Se for POST (Criação), o codproduto deve ser incluído no corpo
        // if (method === 'POST') {
        //     dadosParaEnvio.codproduto = $('#woo_codproduto').val();
        // }

        $.ajax({
                type: method,
                url: endpoint,
                data: dadosParaEnvio,
                dataType: 'json',
                headers: {
                    'Accept': 'application/json'
                },
                beforeSend: function(xhr) {
                    $('button[type="submit"]').prop('disabled', true);
                    $('#lblSincronizandoWoo').show();
                }
            })
            .done(function(data) {

                $('button[type="submit"]').prop('disabled', false);
                $('#lblSincronizandoWoo').hide();

                // console.log(data);

                // =======================================================
                // 💡 ATUALIZAÇÃO DA VARIÁVEL WPS & CONCLUSÃO
                // =======================================================
                if (data.data && data.data.codwooproduto) {
                    var chave = data.data.codwooproduto;

                    wps[chave] = data.data;
                    console.log('Variável wps atualizada com sucesso para codwooproduto: ' + chave);

                    recarregaDiv('div-woo-listagem');

                    var msg = (method === 'POST') ? 'Criação realizada com sucesso!' :
                        'Edição realizada com sucesso!';
                    bootbox.alert(msg);

                    // --- NOVO BLOCO DE FECHAMENTO/RESET ---
                    // 1. Oculta o formulário
                    $('#collapse-form-woo').collapse('hide');

                    // 2. Reseta/limpa os campos para o próximo uso
                    wooNovo();

                } else {
                    recarregaDiv('div-woo-listagem');
                    bootbox.alert('Operação concluída, mas sem ID de retorno. Recarregue a página se necessário.');
                }

            })
            .fail(function(jqXHR) {
                $('button[type="submit"]').prop('disabled', false);
                $('#lblSincronizandoWoo').hide();

                var mensagemFinal = 'Falha na operação!';
                var mensagensDetalhes = [];

                // 1. Verifica se é um erro de validação (status 422) e se há o objeto 'errors'
                if (jqXHR.status === 422 && jqXHR.responseJSON && jqXHR.responseJSON.errors) {

                    var errors = jqXHR.responseJSON.errors;

                    // Itera sobre o objeto 'errors' (onde a chave é o nome do campo)
                    for (var campo in errors) {
                        if (errors.hasOwnProperty(campo)) {
                            // O 'errors' pode ter um array de mensagens por campo
                            var msgs = errors[campo];

                            // Formata a mensagem: Campo: Mensagem 1, Mensagem 2, etc.
                            mensagensDetalhes.push('<strong>' + campo.toUpperCase() + '</strong>: ' + msgs.join(
                                '; '));
                        }
                    }

                    if (mensagensDetalhes.length > 0) {
                        mensagemFinal = jqXHR.responseJSON.message ||
                            mensagemFinal; // Usa a mensagem principal (ex: "The given data was invalid.")
                        mensagemFinal += '<br><br>Detalhes:<br>' + mensagensDetalhes.join('<br>');
                    }

                } else {
                    // Se não for 422 ou não tiver o campo 'errors'
                    // Tenta pegar a mensagem geral ou usa um erro genérico
                    mensagemFinal += '<br>Detalhes: ' + (jqXHR.responseJSON ? (jqXHR.responseJSON.message ||
                        'Erro de servidor desconhecido.') : 'Erro de conexão.');
                    console.log(jqXHR);
                }

                // Exibe a mensagem formatada
                bootbox.alert(mensagemFinal);
            });
    }

    /**
     * Inativa (marca como inativo) o produto/integração.
     * Usa POST para criar o registro de inatividade.
     * @param {number} codwooproduto O código da integração Woo.
     */
    function wooInativar(codwooproduto) {
        bootbox.confirm("Tem certeza que deseja **INATIVAR** esta integração no Woo?", function(result) {
            if (result) {
                $.ajax({
                        type: 'POST',
                        url: urlMGspaApi + 'woo/produto/' + codwooproduto + '/inativo',
                        headers: {
                            'Accept': 'application/json'
                        },
                        beforeSend: function(xhr) {
                            // Aqui você pode desabilitar os botões de ação na listagem
                        }
                    })
                    .done(function(data) {
                        // Atualiza o wps e a listagem após o sucesso
                        if (data && data.data && data.data.codwooproduto) {
                            wps[data.data.codwooproduto] = data.data;
                        }
                        recarregaDiv('div-woo-listagem');
                        bootbox.alert('Produto **INATIVADO** com sucesso!');
                    })
                    .fail(function(jqXHR) {
                        bootbox.alert('Falha ao inativar! Consulte o Log do Console.');
                        console.log(jqXHR);
                    });
            }
        });
    }

    /**
     * Ativa (remove o status de inativo) o produto/integração.
     * Usa DELETE para remover o registro de inatividade.
     * @param {number} codwooproduto O código da integração Woo.
     */
    function wooAtivar(codwooproduto) {
        bootbox.confirm("Tem certeza que deseja **ATIVAR** esta integração no Woo?", function(result) {
            if (result) {
                $.ajax({
                        type: 'DELETE',
                        url: urlMGspaApi + 'woo/produto/' + codwooproduto + '/inativo',
                        headers: {
                            'Accept': 'application/json'
                        },
                        beforeSend: function(xhr) {
                            // Aqui você pode desabilitar os botões de ação na listagem
                        }
                    })
                    .done(function(data) {
                        // Atualiza o wps e a listagem após o sucesso
                        if (data && data.data && data.data.codwooproduto) {
                            wps[data.data.codwooproduto] = data.data;
                        }
                        recarregaDiv('div-woo-listagem');
                        bootbox.alert('Produto **ATIVADO** com sucesso!');
                    })
                    .fail(function(jqXHR) {
                        bootbox.alert('Falha ao ativar! Consulte o Log do Console.');
                        console.log(jqXHR);
                    });
            }
        });
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

        $('#form-woo').submit(function(e) {
            e.preventDefault(); // Impede o envio padrão
            wooPostPut();
        });
    });
</script>
