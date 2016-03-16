<?php

namespace MGLara\Models\Dominio;

use MGLara\Models\Dominio\Arquivo;
use MGLara\Models\Filial;
use Carbon\Carbon;
use MGLara\Models\EstoqueSaldo;
use MGLara\Models\EstoqueMes;

/**
 * @property Carbon $mes 
 * @property Filial $Filial
 */
class ArquivoEstoque extends Arquivo
{
    var $_mes;
    var $_Filial;
    
    function __construct(Carbon $mes, Filial $Filial)
    {
        $this->_mes = $mes;
        $this->_Filial = $Filial;
        
    }
    
    /**
     * @var EstoqueMes $mes
     */
    function processa()
    {
        foreach ($this->_Filial->EstoqueLocalS as $local)
        {
            foreach (EstoqueSaldo::Local($local)->Fiscal(true)->limit(100)->get() as $saldo)
            {
                $mes = EstoqueMes::Saldo($saldo)->Ultimo($this->_mes)->first();
                if ($mes === null)
                    continue;
                if ($mes->saldoquantidade == 0)
                    continue;

                
                $reg = new RegistroProduto4();              
                $reg->codigoProduto = $saldo->codproduto;
                $reg->codigoEmpresa = $this->_Filial->empresadominio;

                switch ($saldo->Produto->codtipoproduto)
                {
                    case 8: // Imobilizado
                        $reg->codigoGrupo = 3;
                        break;
                    
                    case 7: // Uso e Consumo
                        $reg->codigoGrupo = 3;
                        break;
                    
                    default: // Imobilizado
                        $reg->codigoGrupo = 3;
                        break;
                }
                
                $reg->codigoNbm = null;
                $reg->descricaoProduto = $saldo->Produto->produto;
                $reg->tipoItem = $saldo->Produto->codtipoproduto;
                $reg->unidadeMedida = $saldo->Produto->UnidadeMedida->sigla;
                $reg->valorUnitario = $saldo->Produto->preco;
                $reg->codigoNcm = $saldo->Produto->Ncm->ncm;
                $reg->dataSaldoFinal = $mes->mes->modify('last day of this month');
                $reg->valorFinal = $mes->saldovalor;
                $reg->quantidadeFinal = $mes->saldoquantidade;
                
                $this->_registros[] = $reg;
                
            }
        }
    }
}
