<?php

namespace MGLara\Models;
use Carbon\Carbon;

/**
 * Campos
 * @property  bigint                         $codtitulo                          NOT NULL DEFAULT nextval('tbltitulo_codtitulo_seq'::regclass)
 * @property  bigint                         $codtipotitulo                      NOT NULL
 * @property  bigint                         $codfilial                          NOT NULL
 * @property  bigint                         $codportador                        
 * @property  bigint                         $codpessoa                          NOT NULL
 * @property  bigint                         $codcontacontabil                   NOT NULL
 * @property  varchar(20)                    $numero                             NOT NULL
 * @property  varchar(50)                    $fatura                             
 * @property  date                           $transacao                          NOT NULL
 * @property  timestamp                      $sistema                            NOT NULL
 * @property  date                           $emissao                            NOT NULL
 * @property  date                           $vencimento                         NOT NULL
 * @property  date                           $vencimentooriginal                 NOT NULL
 * @property  numeric(14,2)                  $debito                             
 * @property  numeric(14,2)                  $credito                            
 * @property  boolean                        $gerencial                          NOT NULL DEFAULT false
 * @property  varchar(255)                   $observacao                         
 * @property  boolean                        $boleto                             NOT NULL DEFAULT false
 * @property  varchar(20)                    $nossonumero                        
 * @property  numeric(14,2)                  $debitototal                        
 * @property  numeric(14,2)                  $creditototal                       
 * @property  numeric(14,2)                  $saldo                              
 * @property  numeric(14,2)                  $debitosaldo                        
 * @property  numeric(14,2)                  $creditosaldo                       
 * @property  date                           $transacaoliquidacao                
 * @property  bigint                         $codnegocioformapagamento           
 * @property  bigint                         $codtituloagrupamento               
 * @property  bigint                         $remessa                            
 * @property  timestamp                      $estornado                          
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 * @property  bigint                         $codvalecompraformapagamento        
 *
 * Chaves Estrangeiras
 * @property  ContaContabil                  $ContaContabil                 
 * @property  Filial                         $Filial                        
 * @property  NegocioFormaPagamento          $NegocioFormaPagamento         
 * @property  Pessoa                         $Pessoa                        
 * @property  Portador                       $Portador                      
 * @property  TipoTitulo                     $TipoTitulo                    
 * @property  TituloAgrupamento              $TituloAgrupamento             
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 * @property  ValeCompraFormaPagamento       $ValeCompraFormaPagamento      
 *
 * Tabelas Filhas
 * @property  ValeCompra[]                   $ValeCompraS
 * @property  BoletoRetorno[]                $BoletoRetornoS
 * @property  Cobranca[]                     $CobrancaS
 * @property  CobrancaHistoricoTitulo[]      $CobrancaHistoricoTituloS
 * @property  MovimentoTitulo[]              $MovimentoTituloS
 * @property  MovimentoTitulo[]              $MovimentoTituloRelacionadoS
 * @property  Nfeterceiroduplicata[]         $NfeTerceiroDuplicataS
 * @property  Cheque[]                       $ChequeS
 */

class Titulo extends MGModel
{
    protected $table = 'tbltitulo';
    protected $primaryKey = 'codtitulo';
    protected $fillable = [
        'codtipotitulo',
        'codfilial',
        'codportador',
        'codpessoa',
        'codcontacontabil',
        'numero',
        'fatura',
        'transacao',
        'sistema',
        'emissao',
        'vencimento',
        'vencimentooriginal',
        'debito',
        'credito',
        'gerencial',
        'observacao',
        'boleto',
        'nossonumero',
        'debitototal',
        'creditototal',
        'saldo',
        'debitosaldo',
        'creditosaldo',
        'transacaoliquidacao',
        'codnegocioformapagamento',
        'codtituloagrupamento',
        'remessa',
        'estornado',
        'codvalecompraformapagamento',
    ];
    protected $dates = [
        'transacao',
        'sistema',
        'emissao',
        'vencimento',
        'vencimentooriginal',
        'transacaoliquidacao',
        'estornado',
        'alteracao',
        'criacao',
    ];


    // Chaves Estrangeiras
    public function ContaContabil()
    {
        return $this->belongsTo(ContaContabil::class, 'codcontacontabil', 'codcontacontabil');
    }

    public function Filial()
    {
        return $this->belongsTo(Filial::class, 'codfilial', 'codfilial');
    }

    public function NegocioFormaPagamento()
    {
        return $this->belongsTo(NegocioFormaPagamento::class, 'codnegocioformapagamento', 'codnegocioformapagamento');
    }

    public function Pessoa()
    {
        return $this->belongsTo(Pessoa::class, 'codpessoa', 'codpessoa');
    }

    public function Portador()
    {
        return $this->belongsTo(Portador::class, 'codportador', 'codportador');
    }

    public function TipoTitulo()
    {
        return $this->belongsTo(TipoTitulo::class, 'codtipotitulo', 'codtipotitulo');
    }

    public function TituloAgrupamento()
    {
        return $this->belongsTo(TituloAgrupamento::class, 'codtituloagrupamento', 'codtituloagrupamento');
    }

    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuarioalteracao', 'codusuario');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuariocriacao', 'codusuario');
    }

    public function ValeCompraFormaPagamento()
    {
        return $this->belongsTo(ValeCompraFormaPagamento::class, 'codvalecompraformapagamento', 'codvalecompraformapagamento');
    }


    // Tabelas Filhas
    public function ValeCompraS()
    {
        return $this->hasMany(ValeCompra::class, 'codtitulo', 'codtitulo');
    }

    public function BoletoRetornoS()
    {
        return $this->hasMany(BoletoRetorno::class, 'codtitulo', 'codtitulo');
    }

    public function CobrancaS()
    {
        return $this->hasMany(Cobranca::class, 'codtitulo', 'codtitulo');
    }

    public function CobrancaHistoricoTituloS()
    {
        return $this->hasMany(CobrancaHistoricoTitulo::class, 'codtitulo', 'codtitulo');
    }

    public function MovimentoTituloS()
    {
        return $this->hasMany(MovimentoTitulo::class, 'codtitulo', 'codtitulo');
    }

    public function MovimentoTituloRelacionadoS()
    {
        return $this->hasMany(MovimentoTitulo::class, 'codtitulo', 'codtitulorelacionado');
    }

    public function NfeTerceiroDuplicataS()
    {
        return $this->hasMany(NfeTerceiroDuplicata::class, 'codtitulo', 'codtitulo');
    }

    public function ChequeS()
    {
        return $this->hasMany(Cheque::class, 'codtitulo', 'codtitulo');
    }
    
    public function estornar()
    {
        if ($this->saldo != ($this->debito - $this->credito)) {
            return false;
        }
        $this->saldo = 0;
        $this->estornado = Carbon::now();
        return $this->save();
    }


}