<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codchequeemitente                  NOT NULL DEFAULT nextval('tblchequeemitente_codchequeemitente_seq'::regclass)
 * @property  bigint                         $codcheque                          NOT NULL
 * @property  numeric(14,0)                  $cnpj                               NOT NULL
 * @property  varchar(100)                   $emitente                           NOT NULL
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 *
 * Chaves Estrangeiras
 * @property  Cheque                         $Cheque                        
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 */

class ChequeEmitente extends MGModel
{
    protected $table = 'tblchequeemitente';
    protected $primaryKey = 'codchequeemitente';
    protected $fillable = [
        'codcheque',
        'cnpj',
        'emitente',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
    ];


    // Chaves Estrangeiras
    public function Cheque()
    {
        return $this->belongsTo(Cheque::class, 'codcheque', 'codcheque');
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

}