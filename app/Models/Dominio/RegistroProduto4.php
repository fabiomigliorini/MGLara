<?php

namespace MGLara\Models\Dominio;

use MGLara\Models\Dominio\Registro;

class RegistroProduto4 extends Registro
{
    function __construct()
    {
        $this->_campos = [
            'identificador' => [
                'tamanho' => 1,
                'tipo' => 'char'
            ],
            'codigoProduto' => [
                'tamanho' => 14,
                'tipo' => 'char'
            ],
            'codigoEmpresa' => [
                'tamanho' => 7,
                'tipo' => 'numeric'
            ],
            'codigoGrupo' => [
                'tamanho' => 7,
                'tipo' => 'numeric'
            ],
            'codigoNbm' => [
                'tamanho' => 10,
                'tipo' => 'char'
            ],
            'descricaoProduto' => [
                'tamanho' => 40,
                'tipo' => 'char'
            ],
            'unidadeMedida' => [
                'tamanho' => 6,
                'tipo' => 'char'
            ],
            'valorUnitario' => [
                'tamanho' => 13,
                'tipo' => 'decimal',
                'casas' => 3
            ],
            'quantidadeInicialINATIVO' => [
                'tamanho' => 11,
                'tipo' => 'decimal',
                'casas' => 4
            ],
            'quantidadeFinalINATIVO' => [
                'tamanho' => 11,
                'tipo' => 'decimal',
                'casas' => 4
            ],
            'valorInicialEstoque' => [
                'tamanho' => 13,
                'tipo' => 'decimal',
                'casas' => 3
            ],
            'valorFinalEstoque' => [
                'tamanho' => 13,
                'tipo' => 'decimal',
                'casas' => 3
            ],
            'aliquotaIpi' => [
                'tamanho' => 5,
                'tipo' => 'decimal',
                'casas' => 2
            ],
            'observacao' => [
                'tamanho' => 40,
                'tipo' => 'char'
            ],
            'codigoNcm' => [
                'tamanho' => 8,
                'tipo' => 'char'
            ],
            'brancos1' => [
                'tamanho' => 5,
                'tipo' => 'char'
            ],
            'especie' => [
                'tamanho' => 2,
                'tipo' => 'numeric'
            ],
            'brancos2' => [
                'tamanho' => 5,
                'tipo' => 'char'
            ],
            'unidadePadrao' => [
                'tamanho' => 2,
                'tipo' => 'numeric'
            ],
            'fatorConversao' => [
                'tamanho' => 14,
                'tipo' => 'decimal',
                'casas' => 3
            ],
            'exportaDnf' => [
                'tamanho' => 1,
                'tipo' => 'char',
            ],
            'codigoSituacaoTributaria' => [
                'tamanho' => 7,
                'tipo' => 'numeric',
            ],
            'branco1' => [
                'tamanho' => 17,
                'tipo' => 'char',
            ],
            'branco2' => [
                'tamanho' => 1,
                'tipo' => 'char',
            ],
            'codigoEan' => [
                'tamanho' => 14,
                'tipo' => 'char',
            ],
            'codigoProdutoRelevante' => [
                'tamanho' => 7,
                'tipo' => 'numeric',
            ],
            'dataSaldoFinal' => [
                'tamanho' => 10,
                'tipo' => 'date',
                'formato' => 'd/m/Y'
            ],
            'codigoProdutoAnexoIouII' => [
                'tamanho' => 7,
                'tipo' => 'numeric',
            ],
            'capacidadeVolumetrica' => [
                'tamanho' => 7,
                'tipo' => 'numeric',
            ],
            'incentivoFiscal' => [
                'tamanho' => 1,
                'tipo' => 'char',
            ],
            'geraInformacaoGrfCbt' => [
                'tamanho' => 1,
                'tipo' => 'char',
            ],
            'codigoProdutoGrfCbt' => [
                'tamanho' => 7,
                'tipo' => 'numeric',
            ],
            'geraInformacaoScanc' => [
                'tamanho' => 1,
                'tipo' => 'char',
            ],
            'codigoProdutoScanc' => [
                'tamanho' => 7,
                'tipo' => 'numeric',
            ],
            'produtoGasolinaA' => [
                'tamanho' => 1,
                'tipo' => 'char',
            ],
            'unidade' => [
                'tamanho' => 2,
                'tipo' => 'char',
            ],
            'tipoServicoProduto' => [
                'tamanho' => 1,
                'tipo' => 'numeric',
            ],
            'geraInformacoesRegistro88stSintegra' => [
                'tamanho' => 1,
                'tipo' => 'char',
            ],
            'codigoProdutoTabelaSefaz' => [
                'tamanho' => 7,
                'tipo' => 'numeric',
            ],
            'aliquotaIcms' => [
                'tamanho' => 5,
                'tipo' => 'decimal',
                'casas' => 2
            ],
            'tipo' => [
                'tamanho' => 1,
                'tipo' => 'char',
            ],
            'tipoArma' => [
                'tamanho' => 1,
                'tipo' => 'numeric',
            ],
            'descricaoArma' => [
                'tamanho' => 255,
                'tipo' => 'char',
            ],
            'genero' => [
                'tamanho' => 7,
                'tipo' => 'numeric',
            ],
            'codigoServico' => [
                'tamanho' => 7,
                'tipo' => 'numeric',
            ],
            'classificacao' => [
                'tamanho' => 7,
                'tipo' => 'numeric',
            ],
            'tipoItem' => [
                'tamanho' => 2,
                'tipo' => 'numeric',
            ],
            'NcmExterior' => [
                'tamanho' => 2,
                'tipo' => 'numeric',
            ],
            'codigoImpostoImportacao' => [
                'tamanho' => 2,
                'tipo' => 'numeric',
            ],
            'produtoComposto' => [
                'tamanho' => 1,
                'tipo' => 'char',
            ],
            'informacoesComplementaresIpmPdi' => [
                'tamanho' => 1,
                'tipo' => 'char',
            ],
            'codigoProdutoServicoIpmPdi' => [
                'tamanho' => 2,
                'tipo' => 'numeric',
            ],
            'cestaBasica' => [
                'tamanho' => 1,
                'tipo' => 'char',
            ],
            'codigoProdutoDam' => [
                'tamanho' => 7,
                'tipo' => 'numeric',
            ],
            'codigoBarras' => [
                'tamanho' => 16,
                'tipo' => 'char',
                'casas' => ''
            ],
            'tipoMedicamento' => [
                'tamanho' => 1,
                'tipo' => 'numeric',
            ],
            'produtoIncluidoCampoSubstituicaoTributaria' => [
                'tamanho' => 1,
                'tipo' => 'char',
            ],
            'dataInicioSubstituicaoTributaria' => [
                'tamanho' => 10,
                'tipo' => 'date',
                'formato' => 'd/m/Y'
            ],
            'produtoPrecoTabelado' => [
                'tamanho' => 1,
                'tipo' => 'char',
            ],
            'valorUnitarioSubstituicaoTributaria' => [
                'tamanho' => 13,
                'tipo' => 'decimal',
                'casas' => 2
            ],
            'mvaSubstituicaoTributaria' => [
                'tamanho' => 7,
                'tipo' => 'decimal',
                'casas' => 2
            ],
            'grupoSubstituicaoTributaria' => [
                'tamanho' => 1,
                'tipo' => 'numeric',
            ],
            'equipamentoEcf' => [
                'tamanho' => 1,
                'tipo' => 'char',
            ],
            'servicoTributadoIssqn' => [
                'tamanho' => 1,
                'tipo' => 'char',
            ],
            'exTipi' => [
                'tamanho' => 3,
                'tipo' => 'char',
            ],
            'periodicidadeIpi' => [
                'tamanho' => 1,
                'tipo' => 'char',
            ],
            'classificacaoItens' => [
                'tamanho' => 7,
                'tipo' => 'numeric',
            ],
            'quantidadeInicialEstoque' => [
                'tamanho' => 16,
                'tipo' => 'decimal',
                'casas' => '5'
            ],
            'quantidadeFinalEstoque' => [
                'tamanho' => 16,
                'tipo' => 'decimal',
                'casas' => '5'
            ],
            'contaContabil' => [
                'tamanho' => 7,
                'tipo' => 'numeric',
            ],
            'contaContabilTerceiros' => [
                'tamanho' => 7,
                'tipo' => 'numeric',
            ],
            'contaContabilInformante' => [
                'tamanho' => 7,
                'tipo' => 'numeric',
            ],
            'unidadeInventariadaDiferente' => [
                'tamanho' => 1,
                'tipo' => 'char',
            ],
            'reservado' => [
                'tamanho' => 46,
                'tipo' => 'char',
            ],

        ];
        
        $this->identificador = 4;
        $this->quantidadeInicialEstoque = 0;
        $this->quantidadeFinalEstoque = 0;
        $this->valorInicialEstoque = 0;
        $this->valorFinalEstoque = 0;
        $this->observacao = "Exportacao MGsis @ " . date('d/m/Y H:i:s');
        $this->exportaDnf = 'N';
        $this->incentivoFiscal = 'N';
        $this->geraInformacaoGrfCbt = 'N';
        $this->geraInformacaoScanc = 'N';
        $this->produtoGasolinaA = 'N';
        $this->unidade = 'UN';
        $this->tipoServicoProduto = 1;
        $this->geraInformacoesRegistro88stSintegra = 'N';
        $this->tipo = 'O';
        $this->tipoArma = 0;
        $this->produtoComposto = 'N';
        $this->informacoesComplementaresIpmPdi = 'N';
        $this->cestaBasica = 'N';
        $this->tipoMedicamento = '0';
        $this->produtoIncluidoCampoSubstituicaoTributaria = 'N';
        $this->servicoTributadoIssqn = 'N';
        $this->periodicidadeIpi = 'M';
        $this->contaContabil = 55;
        $this->unidadeInventariadaDiferente = 'N';        
    }
}
