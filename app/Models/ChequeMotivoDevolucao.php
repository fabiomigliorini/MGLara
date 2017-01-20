<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codchequemotivodevolucao           NOT NULL DEFAULT nextval('tblchequemotivodevolucao_codchequemotivodevolucao_seq'::regclass)
 * @property  smallint                       $numero                             NOT NULL
 * @property  varchar(100)                   $chequemotivodevolucao              NOT NULL
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 *
 * Chaves Estrangeiras
 * @property  Usuario                        $UsuarioCriacao
 * @property  Usuario                        $UsuarioAlteracao
 *
 * Tabelas Filhas
 * @property  ChequeDevolucao[]              $ChequeDevolucaoS
 */

class ChequeMotivoDevolucao extends MGModel
{
    protected $table = 'tblchequemotivodevolucao';
    protected $primaryKey = 'codchequemotivodevolucao';
    protected $fillable = [
        'numero',
        'chequemotivodevolucao',
    ];
    protected $dates = [
        'criacao',
        'alteracao',
    ];


    // Chaves Estrangeiras
    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuariocriacao', 'codusuario');
    }

    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuarioalteracao', 'codusuario');
    }


    // Tabelas Filhas
    public function ChequedevolucaoS()
    {
        return $this->hasMany(ChequeDevolucao::class, 'codchequemotivodevolucao', 'codchequemotivodevolucao');
    }


}