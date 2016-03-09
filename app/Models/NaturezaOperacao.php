<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codnaturezaoperacao                NOT NULL DEFAULT nextval('tblnaturezaoperacao_codnaturezaoperacao_seq'::regclass)
 * @property  varchar(50)                    $naturezaoperacao                   
 * @property  bigint                         $codoperacao                        
 * @property  boolean                        $emitida                            NOT NULL DEFAULT false
 * @property  varchar(500)                   $observacoesnf                      
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 * @property  varchar(300)                   $mensagemprocom                     
 * @property  bigint                         $codnaturezaoperacaodevolucao       
 * @property  bigint                         $codtipotitulo                      NOT NULL
 * @property  bigint                         $codcontacontabil                   NOT NULL
 * @property  smallint                       $finnfe                             NOT NULL DEFAULT 1
 * @property  boolean                        $ibpt                               NOT NULL DEFAULT false
 * @property  bigint                         $codestoquemovimentotipo            NOT NULL
 * @property  boolean                        $estoque                            NOT NULL DEFAULT true - Define se a natureza movimenta o estoque
 *
 * Chaves Estrangeiras
 * @property  ContaContabil                  $ContaContabil                 
 * @property  EstoqueMovimentoTipo           $EstoqueMovimentoTipo          
 * @property  NaturezaOperacao               $NaturezaOperacaoDevolucao              
 * @property  TipoTitulo                     $TipoTitulo                    
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 * @property  NaturezaOperacao[]             $NaturezaOperacaoDevolucaoS
 * @property  Negocio[]                      $NegocioS
 * @property  NfeTerceiro[]                  $NfeTerceiroS
 * @property  NotaFiscal[]                   $NotaFiscalS
 * @property  TributacaoNaturezaOperacao[]   $TributacaoNaturezaOperacaoS
 */

class NaturezaOperacao extends MGModel
{
    protected $table = 'tblnaturezaoperacao';
    protected $primaryKey = 'codnaturezaoperacao';
    protected $fillable = [
        'naturezaoperacao',
        'codoperacao',
        'emitida',
        'observacoesnf',
        'mensagemprocom',
        'codnaturezaoperacaodevolucao',
        'codtipotitulo',
        'codcontacontabil',
        'finnfe',
        'ibpt',
        'codestoquemovimentotipo',
        'estoque',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
    ];


    // Chaves Estrangeiras
    public function ContaContabil()
    {
        return $this->belongsTo(ContaContabil::class, 'codcontacontabil', 'codcontacontabil');
    }

    public function EstoqueMovimentoTipo()
    {
        return $this->belongsTo(EstoqueMovimentoTipo::class, 'codestoquemovimentotipo', 'codestoquemovimentotipo');
    }

    public function NaturezaOperacaoDevolucao()
    {
        return $this->belongsTo(NaturezaOperacao::class, 'codnaturezaoperacaodevolucao', 'codnaturezaoperacao');
    }

    public function TipoTitulo()
    {
        return $this->belongsTo(TipoTitulo::class, 'codtipotitulo', 'codtipotitulo');
    }

    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuarioalteracao', 'codusuario');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuariocriacao', 'codusuario');
    }


    // Tabelas Filhas
    public function NaturezaOperacaoDevolucaoS()
    {
        return $this->hasMany(NaturezaOperacao::class, 'codnaturezaoperacao', 'codnaturezaoperacaodevolucao');
    }

    public function NegocioS()
    {
        return $this->hasMany(Negocio::class, 'codnaturezaoperacao', 'codnaturezaoperacao');
    }

    public function NfeTerceiroS()
    {
        return $this->hasMany(NfeTerceiro::class, 'codnaturezaoperacao', 'codnaturezaoperacao');
    }

    public function NotaFiscalS()
    {
        return $this->hasMany(NotaFiscal::class, 'codnaturezaoperacao', 'codnaturezaoperacao');
    }

    public function TributacaoNaturezaOperacaoS()
    {
        return $this->hasMany(TributacaoNaturezaOperacao::class, 'codnaturezaoperacao', 'codnaturezaoperacao');
    }


}
