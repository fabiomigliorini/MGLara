<?php

namespace MGLara\Library\EscPrint;

use MGLara\Library\EscPrint\EscPrint;
use MGLara\Models\ValeCompra;
use MGLara\Models\Pessoa;

/*
 * Condensado: 137 Colunas
 * Normal:      80 Colunas
 * Large:       40 Colunas
 *
 * @property ValeCompra $vale
 */

class EscPrintValeCompra extends EscPrint
{
    private $vale;

    /*
     * @parameter Negocio $vale
     */
    public function __construct(ValeCompra $vale, $impressora = null, $linhas = null)
    {
        $this->vale = $vale;
        parent::__construct($impressora, $linhas);

        $this->adicionaTexto("<Reset><6lpp><Draft><CondensedOn>", "cabecalho");

        //linha divisoria
        $this->adicionaLinha("", "cabecalho", 137, STR_PAD_RIGHT, "=");

        $filial = $vale->Filial;

        // Fantasia e NUMERO do Negocio
        $this->adicionaTexto("<DblStrikeOn>", "cabecalho");
        $this->adicionaTexto($filial->Pessoa->fantasia . " " . $filial->Pessoa->telefone1, "cabecalho", 68);
        $this->adicionaTexto("Vale Compras:      " . formataCodigo($vale->codvalecompra), "cabecalho", 69, STR_PAD_LEFT);
        $this->adicionaTexto("<DblStrikeOff>", "cabecalho");
        $this->adicionaLinha("", "cabecalho");



        // Usuario e Data
        $this->adicionaTexto("Usuario.: " . $vale->UsuarioCriacao->usuario, "cabecalho", 68);
        $this->adicionaTexto("Data...: " . $vale->criacao, "cabecalho", 69, STR_PAD_LEFT);
        $this->adicionaLinha("", "cabecalho");

        //linha divisoria
        $this->adicionaLinha("", "cabecalho", 137, STR_PAD_RIGHT, "=");

        //Rodape
        $this->adicionaTexto("", "rodape", 137, STR_PAD_RIGHT, "=");

        $this->adicionaTexto("<CondensedOff><DblStrikeOn>");
        $this->adicionaLinha('', "documento", 80);
        $this->adicionaTexto("<LargeOn>");

        //Titulo Vale COmpras
        $this->adicionaLinha(
                str_pad('V A L E   C O M P R A S', 40, ' ', STR_PAD_BOTH),
            "documento",
            40
        );

        $this->adicionaTexto("<LargeOff>");
        $this->adicionaLinha('', "documento", 80);

        // Favorecido
        if ($vale->codpessoafavorecido != Pessoa::CONSUMIDOR) {
            $linha = "Em Favor: "
                    . formataCodigo($vale->codpessoafavorecido)
                    . " "
                    . $vale->PessoaFavorecido->fantasia;

            if (!empty($vale->PessoaFavorecido->telefone1)) {
                $linha .= " - " . trim($vale->PessoaFavorecido->telefone1);
            }

            if (!empty($vale->PessoaFavorecido->telefone2)) {
                $linha .= " / " . trim($vale->PessoaFavorecido->telefone2);
            }

            if (!empty($vale->PessoaFavorecido->telefone3)) {
                $linha .= " / " . trim($vale->PessoaFavorecido->telefone3);
            }

            $this->adicionaLinha(
                    $linha,
                "documento",
                80
            );

            $this->adicionaTexto("<DblStrikeOff><CondensedOn>");

            /*
            if ($vale->PessoaFavorecido->fisica)
                $linha = "CPF....: ";
            else
                $linha = "CNPJ...: ";

            $linha .=
                    formataCnpjCpf($vale->PessoaFavorecido->cnpj, $vale->PessoaFavorecido->fisica)
                    . " - "
                    . $vale->PessoaFavorecido->pessoa;

            $this->adicionaLinha(
                    $linha
                    , "documento", 137);

            $endereco =
                "End....: "
                .$vale->PessoaFavorecido->endereco
                .", "
                .$vale->PessoaFavorecido->numero
                ." - ";

            if (!empty($vale->PessoaFavorecido->complemento))
                $endereco .=
                    $vale->PessoaFavorecido->complemento
                    ." - ";

            $endereco .=
                $vale->PessoaFavorecido->bairro
                ." - "
                .$vale->PessoaFavorecido->Cidade->cidade
                ."/"
                .$vale->PessoaFavorecido->Cidade->Estado->sigla
                ." - "
                .formataCep($vale->PessoaFavorecido->cep);

            $this->adicionaLinha($endereco, "documento", 137);
             *
             */
        }

        // Aluno
        $this->adicionaTexto("<CondensedOff><DblStrikeOn>");

        $linha = "Aluno...: "
                . $vale->aluno
                . " / "
                . $vale->turma;
        $this->adicionaLinha(
                $linha,
            "documento",
            80
        );

        //se for quantidade de itens igual tamanho pagina, adiciona linhas em branco pra focar quebra
        $prods = $vale->ValeCompraProdutoBarraS;
        $total_prods = $prods->count();
        if ($total_prods >= 17 and $total_prods <= 19) {
          for ($i = 0; $i < (19-$total_prods); $i++) {
            $this->adicionaLinha($i);
          }
        }

        //Cabecalho produtos
        $this->adicionaTexto("<CondensedOn><DblStrikeOff>");
        $this->adicionaLinha("", "documento", 137, STR_PAD_LEFT, "-");
        $this->adicionaTexto("<DblStrikeOn>");
        $this->adicionaTexto("Codigo", "documento", 20);
        $this->adicionaTexto("Descricao", "documento", 70);
        $this->adicionaTexto("UM", "documento", 7);
        $this->adicionaTexto("Quant", "documento", 10, STR_PAD_LEFT);
        $this->adicionaTexto("Preco", "documento", 15, STR_PAD_LEFT);
        $this->adicionaTexto("Total", "documento", 15, STR_PAD_LEFT);
        $this->adicionaTexto("<DblStrikeOff>");
        $this->adicionaLinha();

        //percorre produtos
        $i_prod = 1;
        foreach ($prods as $prod) {
            $this->adicionaTexto($prod->ProdutoBarra->barras, "documento", 20);
            $this->adicionaTexto($prod->ProdutoBarra->descricao(), "documento", 65);
            $this->adicionaTexto($prod->ProdutoBarra->UnidadeMedida->sigla, "documento", 7, STR_PAD_LEFT);
            $this->adicionaTexto(formataNumero($prod->quantidade), "documento", 15, STR_PAD_LEFT);
            $this->adicionaTexto(formataNumero($prod->preco), "documento", 15, STR_PAD_LEFT);
            $this->adicionaTexto(formataNumero($prod->total), "documento", 15, STR_PAD_LEFT);
            $this->adicionaLinha();
            $i_prod++;
        }

        //linha divisoria
        $this->adicionaLinha("", "documento", 137, STR_PAD_LEFT, "-");

        //linha com totais
        $this->adicionaTexto("<DblStrikeOn>");
        $this->adicionaTexto("Subtotal:");
        $this->adicionaTexto(formataNumero($vale->totalprodutos), "documento", 20, STR_PAD_LEFT);
        $this->adicionaTexto("Desconto:", "documento", 35, STR_PAD_LEFT);
        $this->adicionaTexto(formataNumero($vale->desconto), "documento", 20, STR_PAD_LEFT);
        $this->adicionaTexto("Total...:", "documento", 35, STR_PAD_LEFT);
        $this->adicionaTexto(formataNumero($vale->total), "documento", 18, STR_PAD_LEFT);
        $this->adicionaLinha("<DblStrikeOff>");

        // Observacoes
        if (!empty($vale->observacoes)) {
            $this->adicionaLinha();
            $observacoes = "Observacoes: ";
            $observacoes .= $vale->observacoes;

            $observacoes = str_split($observacoes, 137);

            foreach ($observacoes as $linha) {
                $this->adicionaLinha($linha);
            }

            $this->adicionaLinha();
        }

        $this->adicionaTexto("<DblStrikeOff><CondensedOn>");


        // Duplicatas
        foreach ($vale->ValeCompraFormaPagamentoS as $pag) {
            if (count($pag->TituloS) > 0) {

                // Adiciona Linhas para quebrar Página
                $linhas = 0;
                foreach ($this->_conteudoSecao as $key => $cont) {
                    $linhas += substr_count($cont, "\n");
                }
                $linhas = $this->_linhas - $linhas -2;
                for ($i = 0; $i < $linhas; $i++) {
                    $this->adicionaLinha();
                }

                // Cliente
                $this->adicionaLinha();
                $this->adicionaLinha();
                if ($vale->codpessoa != Pessoa::CONSUMIDOR) {
                    $this->adicionaTexto("<CondensedOff><DblStrikeOn>");

                    $linha = "Cliente.: "
                            . formataCodigo($vale->codpessoa)
                            . " "
                            . $vale->Pessoa->fantasia;

                    if (!empty($vale->Pessoa->telefone1)) {
                        $linha .= " - " . trim($vale->Pessoa->telefone1);
                    }

                    if (!empty($vale->Pessoa->telefone2)) {
                        $linha .= " / " . trim($vale->Pessoa->telefone2);
                    }

                    if (!empty($vale->Pessoa->telefone3)) {
                        $linha .= " / " . trim($vale->Pessoa->telefone3);
                    }

                    $this->adicionaLinha(
                            $linha,
                        "documento",
                        80
                    );

                    $this->adicionaTexto("<DblStrikeOff><CondensedOn>");

                    if ($vale->Pessoa->fisica) {
                        $linha = "CPF....: ";
                    } else {
                        $linha = "CNPJ...: ";
                    }

                    $linha .=
                            formataCnpjCpf($vale->Pessoa->cnpj, $vale->Pessoa->fisica)
                            . " - "
                            . $vale->Pessoa->pessoa;

                    $this->adicionaLinha(
                            $linha,
                        "documento",
                        137
                    );

                    $endereco =
                        "End....: "
                        .$vale->Pessoa->endereco
                        .", "
                        .$vale->Pessoa->numero
                        ." - ";

                    if (!empty($vale->Pessoa->complemento)) {
                        $endereco .=
                            $vale->Pessoa->complemento
                            ." - ";
                    }

                    $endereco .=
                        $vale->Pessoa->bairro
                        ." - "
                        .$vale->Pessoa->Cidade->cidade
                        ."/"
                        .$vale->Pessoa->Cidade->Estado->sigla
                        ." - "
                        .formataCep($vale->Pessoa->cep);

                    $this->adicionaLinha($endereco, "documento", 137);
                }

                //Titulos
                $this->adicionaLinha();
                $this->adicionaLinha();
                $this->adicionaLinha("", "documento", 137, STR_PAD_LEFT, "-");
                $this->adicionaTexto("<DblStrikeOn>");

                $linha = 1;
                $i = 1;
                $vencimentos = array();

                foreach ($pag->Titulos as $titulo) {
                    $vencimentos[$linha][$i] = array(
                        "vencimento" => formataData($titulo->vencimento),
                        "valor" => formataNumero(abs($titulo->debito - $titulo->credito))
                            );

                    $i++;

                    if ($i>6) {
                        $i = 1;
                        $linha++;
                    }
                }

                $labelVencimentos = "Vencimentos | ";

                foreach ($vencimentos as $linha) {
                    $this->adicionaTexto($labelVencimentos);
                    $labelVencimentos = "            | ";

                    $i = 1;
                    foreach ($linha as $coluna) {
                        $this->adicionaTexto($coluna["vencimento"], "documento", 8);
                        $this->adicionaTexto($coluna["valor"], "documento", 10, STR_PAD_LEFT);
                        if ($i <= 5) {
                            $this->adicionaTexto(" | ", "documento", 3);
                        }
                        $i++;
                    }
                    $this->adicionaLinha();

                    $this->adicionaTexto($labelVencimentos);

                    $i = 1;
                    foreach ($linha as $coluna) {
                        $this->adicionaTexto("", "documento", 18, STR_PAD_LEFT);
                        if ($i <= 5) {
                            $this->adicionaTexto(" | ", "documento", 3);
                        }
                        $i++;
                    }
                    $this->adicionaLinha();
                }
                $this->adicionaLinha("", "documento", 137, STR_PAD_LEFT, "-");
                $this->adicionaLinha();
                $this->adicionaLinha();

                // Texto da confissao de divida
                $this->adicionaLinha("Confissao de Divida: Confesso(amos) e me(nos) constituo(imos) devedor(es) do valor de R$ " . formataNumero($vale->total) . ", obrigando-me(nos) a pagar em moeda");
                $this->adicionaTexto("corrente do pais, conforme vencimento(s) acima descrito(s). Declaro(amos) ainda ter(mos) recebido o Vale Compras número " . formataCodigo($vale->codvalecompra) . ", sem");
                $this->adicionaLinha();
                $this->adicionaTexto("nada a reclamar!");
                $this->adicionaLinha();
                $this->adicionaLinha();
                $this->adicionaLinha();
                $this->adicionaLinha();
                $this->adicionaLinha();

                //linha da assinatura e nome pessoa
                $this->adicionaTexto("", "documento", 25);
                $this->adicionaLinha("", "documento", 80, STR_PAD_BOTH, "_");

                $this->adicionaTexto("<DblStrikeOn>");
                $this->adicionaTexto("", "documento", 25);
                $this->adicionaTexto(
                        $vale->codvalecompra
                        ." - "
                        .$vale->Pessoa->pessoa,
                    "documento",
                    80
                );
                $this->adicionaTexto("<DblStrikeOff>");
            }
        }
    }
}
