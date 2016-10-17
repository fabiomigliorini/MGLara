<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codbanco                           NOT NULL DEFAULT nextval('tblbanco_codbanco_seq'::regclass)
 * @property  varchar(50)                    $banco                              
 * @property  varchar(3)                     $sigla                              
 * @property  bigint                         $numerobanco                        
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 *
 * Chaves Estrangeiras
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 * @property  Cheque[]                       $ChequeS
 * @property  Portador[]                     $PortadorS
 */

class Banco extends MGModel
{
    protected $table = 'tblbanco';
    protected $primaryKey = 'codbanco';
    protected $fillable = [
        'banco',
        'sigla',
        'numerobanco',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
    ];


    // Chaves Estrangeiras
    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuarioalteracao', 'codusuario');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuariocriacao', 'codusuario');
    }


    // Tabelas Filhas
    public function ChequeS()
    {
        return $this->hasMany(Cheque::class, 'codbanco', 'codbanco');
    }

    public function PortadorS()
    {
        return $this->hasMany(Portador::class, 'codbanco', 'codbanco');
    }


}
