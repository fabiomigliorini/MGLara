<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codnotafiscalreferenciada          NOT NULL DEFAULT nextval('tblnotafiscalreferenciada_codnotafiscalreferenciada_seq'::regclass)
 * @property  varchar(100)                   $nfechave                           NOT NULL
 * @property  bigint                         $codnotafiscal                      NOT NULL
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 *
 * Chaves Estrangeiras
 * @property  NotaFiscal                     $NotaFiscal                    
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 */

class NotaFiscalReferenciada extends MGModel
{
    protected $table = 'tblnotafiscalreferenciada';
    protected $primaryKey = 'codnotafiscalreferenciada';
    protected $fillable = [
        'nfechave',
        'codnotafiscal',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
    ];


    // Chaves Estrangeiras
    public function NotaFiscal()
    {
        return $this->belongsTo(NotaFiscal::class, 'codnotafiscal', 'codnotafiscal');
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
