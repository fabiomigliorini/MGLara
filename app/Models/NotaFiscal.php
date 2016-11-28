<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codnotafiscal                      NOT NULL DEFAULT nextval('tblnotafiscal_codnotafiscal_seq'::regclass)
 * @property  bigint                         $codnaturezaoperacao                NOT NULL
 * @property  boolean                        $emitida                            NOT NULL DEFAULT false
 * @property  varchar(100)                   $nfechave                           
 * @property  boolean                        $nfeimpressa                        NOT NULL DEFAULT false
 * @property  integer                        $serie                              NOT NULL
 * @property  integer                        $numero                             NOT NULL
 * @property  timestamp                      $emissao                            NOT NULL
 * @property  timestamp                      $saida                              NOT NULL
 * @property  bigint                         $codfilial                          NOT NULL
 * @property  bigint                         $codpessoa                          NOT NULL
 * @property  varchar(1500)                  $observacoes                        
 * @property  integer                        $volumes                            
 * @property  bigint                         $codoperacao                        
 * @property  varchar(100)                   $nfereciboenvio                     
 * @property  timestamp                      $nfedataenvio                       
 * @property  varchar(100)                   $nfeautorizacao                     
 * @property  timestamp                      $nfedataautorizacao                 
 * @property  numeric(14,2)                  $valorfrete                         
 * @property  numeric(14,2)                  $valorseguro                        
 * @property  numeric(14,2)                  $valordesconto                      
 * @property  numeric(14,2)                  $valoroutras                        
 * @property  varchar(100)                   $nfecancelamento                    
 * @property  timestamp                      $nfedatacancelamento                
 * @property  varchar(100)                   $nfeinutilizacao                    
 * @property  timestamp                      $nfedatainutilizacao                
 * @property  varchar(200)                   $justificativa                      
 * @property  smallint                       $modelo                             
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 * @property  numeric(14,2)                  $valorprodutos                      NOT NULL
 * @property  numeric(14,2)                  $valortotal                         NOT NULL
 * @property  numeric(14,2)                  $icmsbase                           NOT NULL
 * @property  numeric(14,2)                  $icmsvalor                          NOT NULL
 * @property  numeric(14,2)                  $icmsstbase                         NOT NULL
 * @property  numeric(14,2)                  $icmsstvalor                        NOT NULL
 * @property  numeric(14,2)                  $ipibase                            NOT NULL
 * @property  numeric(14,2)                  $ipivalor                           NOT NULL
 * @property  smallint                       $frete                              NOT NULL DEFAULT 9
 * @property  smallint                       $tpemis                             NOT NULL DEFAULT 1
 * @property  bigint                         $codestoquelocal                    NOT NULL
 *
 * Chaves Estrangeiras
 * @property  Filial                         $Filial                        
 * @property  NaturezaOperacao               $NaturezaOperacao              
 * @property  Operacao                       $Operacao                      
 * @property  Pessoa                         $Pessoa                        
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 * @property  EstoqueLocal                   $EstoqueLocal                  
 *
 * Tabelas Filhas
 * @property  NfeTerceiro[]                  $NfeTerceiroS
 * @property  NotaFiscalCartaCorrecao[]      $NotaFiscalCartaCorrecaoS
 * @property  NotaFiscalDuplicatas[]         $NotaFiscalDuplicatasS
 * @property  NotaFiscalProdutoBarra[]       $NotaFiscalProdutoBarraS
 * @property  NotaFiscalReferenciada[]       $NotaFiscalReferenciadaS
 */

class NotaFiscal extends MGModel
{
    const MODELO_NFE = 55;
    const MODELO_NFCE = 65;
    
    protected $table = 'tblnotafiscal';
    protected $primaryKey = 'codnotafiscal';
    protected $fillable = [
        'codnaturezaoperacao',
        'emitida',
        'nfechave',
        'nfeimpressa',
        'serie',
        'numero',
        'emissao',
        'saida',
        'codfilial',
        'codpessoa',
        'observacoes',
        'volumes',
        'codoperacao',
        'nfereciboenvio',
        'nfedataenvio',
        'nfeautorizacao',
        'nfedataautorizacao',
        'valorfrete',
        'valorseguro',
        'valordesconto',
        'valoroutras',
        'nfecancelamento',
        'nfedatacancelamento',
        'nfeinutilizacao',
        'nfedatainutilizacao',
        'justificativa',
        'modelo',
        'valorprodutos',
        'valortotal',
        'icmsbase',
        'icmsvalor',
        'icmsstbase',
        'icmsstvalor',
        'ipibase',
        'ipivalor',
        'frete',
        'tpemis',
        'codestoquelocal',
    ];
    protected $dates = [
        'emissao',
        'saida',
        'nfedataenvio',
        'nfedataautorizacao',
        'nfedatacancelamento',
        'nfedatainutilizacao',
        'alteracao',
        'criacao',
    ];
    
    // Chaves Estrangeiras
    public function Filial()
    {
        return $this->belongsTo(Filial::class, 'codfilial', 'codfilial');
    }

    public function NaturezaOperacao()
    {
        return $this->belongsTo(NaturezaOperacao::class, 'codnaturezaoperacao', 'codnaturezaoperacao');
    }

    public function Operacao()
    {
        return $this->belongsTo(Operacao::class, 'codoperacao', 'codoperacao');
    }

    public function Pessoa()
    {
        return $this->belongsTo(Pessoa::class, 'codpessoa', 'codpessoa');
    }

    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuarioalteracao', 'codusuario');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuariocriacao', 'codusuario');
    }

    public function EstoqueLocal()
    {
        return $this->belongsTo(EstoqueLocal::class, 'codestoquelocal', 'codestoquelocal');
    }

    // Tabelas Filhas
    public function NfeTerceiroS()
    {
        return $this->hasMany(NfeTerceiro::class, 'codnotafiscal', 'codnotafiscal');
    }

    public function NotaFiscalCartaCorrecaoS()
    {
        return $this->hasMany(NotaFiscalCartaCorrecao::class, 'codnotafiscal', 'codnotafiscal');
    }

    public function NotaFiscalDuplicatasS()
    {
        return $this->hasMany(NotaFiscalDuplicatas::class, 'codnotafiscal', 'codnotafiscal');
    }

    public function NotaFiscalProdutoBarraS()
    {
        return $this->hasMany(NotaFiscalProdutoBarra::class, 'codnotafiscal', 'codnotafiscal');
    }

    public function NotaFiscalReferenciadaS()
    {
        return $this->hasMany(NotaFiscalReferenciada::class, 'codnotafiscal', 'codnotafiscal');
    } 
    
    /**
     * retorna se Nota Fiscal está ativa
     * 
     * @return boolean
     */
    public function ativa()
    {
        if ($this->emitida == true 
            && !empty($this->nfeautorizacao) 
            && empty($this->nfecancelamento) 
            && empty($this->nfecancelamento)) {
            return true;
        }
        if ($this->emitida == false) {
            return true;
        }
        return false;
    }
}
