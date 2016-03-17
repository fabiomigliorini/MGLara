<?php

namespace MGLara\Models\Dominio;

use MGLara\Models\Dominio\Arquivo;
use MGLara\Models\Filial;
use Carbon\Carbon;
use MGLara\Models\EstoqueSaldo;
use MGLara\Models\EstoqueMes;

/**
 * 
 * Geração de arquivos textos com o Estoque para integracao
 * com o Dominio Sistemas
 * 
 * @property Carbon $mes 
 * @property Filial $Filial
 */
class ArquivoEstoque extends Arquivo
{
    var $_mes;
    var $_Filial;
    
    /**
     * 
     * Inicializa Classe
     * 
     * @param \Carbon\Carbon $mes
     * @param \MGLara\Models\Filial $Filial
     */
    function __construct(Carbon $mes, Filial $Filial)
    {
        $this->_mes = $mes;
        $this->_Filial = $Filial;
        $this->_arquivo = $mes->format('Ym') . '-' . str_pad($Filial->empresadominio, 4, '0', STR_PAD_LEFT) . '-Estoque.txt';
    }
    
    function processa()
    {
        $dataSaldo = $this->_mes->modify('last day of this month');
        
        foreach ($this->_Filial->EstoqueLocalS as $local)
        {
            //foreach (EstoqueSaldo::Local($local)->Fiscal(true)->limit(3000)->get() as $saldo)
            foreach (EstoqueSaldo::Local($local)->Fiscal(true)->get() as $saldo)
            {
                $mes = EstoqueMes::Saldo($saldo)->Ultimo($this->_mes)->first();
                if ($mes === null)
                    continue;
                if ($mes->saldoquantidade == 0)
                    continue;

                
                $reg = new RegistroProduto4();          
                $reg->codigoProduto = str_pad($saldo->codproduto, 6, '0', STR_PAD_LEFT);
                $reg->codigoEmpresa = $this->_Filial->empresadominio;

                switch ($saldo->Produto->codtipoproduto)
                {
                    case 8: // Imobilizado
                        $reg->codigoGrupo = 3;
                        break;
                    
                    case 7: // Uso e Consumo
                        $reg->codigoGrupo = 2;
                        break;
                    
                    default: // Imobilizado
                        $reg->codigoGrupo = 1;
                        break;
                }
                
                $reg->codigoNbm = null;
                $reg->descricaoProduto = $saldo->Produto->produto;
                $reg->tipoItem = $saldo->Produto->codtipoproduto;
                $reg->unidadeMedida = $saldo->Produto->UnidadeMedida->sigla;
                $reg->valorUnitario = $saldo->Produto->preco;
                $reg->codigoNcm = $saldo->Produto->Ncm->ncm;
                $reg->dataSaldoFinal = $dataSaldo;
                $reg->valorFinalEstoque = $mes->saldovalor;
                $reg->quantidadeFinalEstoque = $mes->saldoquantidade;
                
                $this->_registros[] = $reg;
                
            }
        }
        
        return parent::processa();
    }
}

