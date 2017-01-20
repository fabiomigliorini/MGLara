<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codcheque                          NOT NULL DEFAULT nextval('tblcheque_codcheque_seq'::regclass)
 * @property  varchar(50)                    $cmc7                               
 * @property  bigint                         $codbanco                           NOT NULL
 * @property  varchar(10)                    $agencia                            NOT NULL
 * @property  varchar(15)                    $contacorrente                      NOT NULL
 * @property  varchar(100)                   $emitente                           NOT NULL
 * @property  varchar(15)                    $numero                             NOT NULL
 * @property  date                           $emissao                            NOT NULL
 * @property  date                           $vencimento                         NOT NULL
 * @property  date                           $repasse                            
 * @property  varchar(50)                    $destino                            
 * @property  date                           $devolucao                          
 * @property  varchar(50)                    $motivodevolucao                    
 * @property  varchar(200)                   $observacao                         
 * @property  timestamp                      $lancamento                         NOT NULL
 * @property  timestamp                      $alteracao                          
 * @property  timestamp                      $cancelamento                       
 * @property  numeric(14,2)                  $valor                              NOT NULL
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 * @property  bigint                         $codpessoa                          
 * @property  smallint                       $indstatus                          1 - À Repassar / 2 - Repassado / 3 - Devolvido / 4 - Em Cobranca / 5 - Liquidado
 * @property  bigint                         $codtitulo                          
 *
 * Chaves Estrangeiras
 * @property  Banco                          $Banco                         
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 * @property  Pessoa                         $Pessoa                        
 * @property  Titulo                         $Titulo                        
 *
 * Tabelas Filhas
 * @property  ChequeRepasseCheque[]          $ChequeRepasseChequeS
 * @property  ChequeEmitente[]               $ChequeEmitenteS
 * @property  Cobranca[]                     $CobrancaS
 */

class Cheque extends MGModel
{
    protected $table = 'tblcheque';
    protected $primaryKey = 'codcheque';
    protected $fillable = [
        'cmc7',
        'emitente',
        'numero',
        'emissao',
        'vencimento',
        'observacao',
        'valor',
        'codpessoa',
    ];
    protected $dates = [
        'emissao',
        'vencimento',
        'repasse',
        'devolucao',
        'lancamento',
        'alteracao',
        'cancelamento',
        'criacao',
    ];


    // Chaves Estrangeiras
    public function Banco()
    {
        return $this->belongsTo(Banco::class, 'codbanco', 'codbanco');
    }

    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuarioalteracao', 'codusuario');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuariocriacao', 'codusuario');
    }

    public function Pessoa()
    {
        return $this->belongsTo(Pessoa::class, 'codpessoa', 'codpessoa');
    }

    public function Titulo()
    {
        return $this->belongsTo(Titulo::class, 'codtitulo', 'codtitulo');
    }


    // Tabelas Filhas
    public function ChequeRepasseChequeS()
    {
        return $this->hasMany(ChequeRepasseCheque::class, 'codcheque', 'codcheque');
    }

    public function ChequeEmitenteS()
    {
        return $this->hasMany(ChequeEmitente::class, 'codcheque', 'codcheque');
    }

    public function CobrancaS()
    {
        return $this->hasMany(Cobranca::class, 'codcheque', 'codcheque');
    }
    
}