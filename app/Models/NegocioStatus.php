<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codnegociostatus                   NOT NULL DEFAULT nextval('tblnegociostatus_codnegociostatus_seq'::regclass)
 * @property  varchar(50)                    $negociostatus                      NOT NULL
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
 * @property  Negocio[]                      $NegocioS
 */

class NegocioStatus extends MGModel
{
    const ABERTO = 1;
    const FECHADO = 2;
    const CANCELADO = 3;
    
    protected $table = 'tblnegociostatus';
    protected $primaryKey = 'codnegociostatus';
    protected $fillable = [
        'negociostatus',
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
    public function NegocioS()
    {
        return $this->hasMany(Negocio::class, 'codnegociostatus', 'codnegociostatus');
    }


}