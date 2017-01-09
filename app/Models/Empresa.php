<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codempresa                         NOT NULL DEFAULT nextval('tblempresa_codempresa_seq'::regclass)
 * @property  varchar(50)                    $empresa                            
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 * @property  smallint                       $modoemissaonfce                    NOT NULL DEFAULT 1
 * @property  timestamp                      $contingenciadata                   
 * @property  varchar(256)                   $contingenciajustificativa          
 *
 * Chaves Estrangeiras
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 * @property  Filial[]                       $FilialS
 */

class Empresa extends MGModel
{
    protected $table = 'tblempresa';
    protected $primaryKey = 'codempresa';
    protected $fillable = [
        'empresa',
        'modoemissaonfce',
        'contingenciadata',
        'contingenciajustificativa',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
        'contingenciadata',
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
    public function FilialS()
    {
        return $this->hasMany(Filial::class, 'codempresa', 'codempresa');
    }


}