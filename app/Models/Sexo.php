<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codsexo                            NOT NULL DEFAULT nextval('tblsexo_codsexo_seq'::regclass)
 * @property  varchar(10)                    $sexo                               NOT NULL
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
 * @property  Pessoa[]                       $PessoaS
 */

class Sexo extends MGModel
{
    protected $table = 'tblsexo';
    protected $primaryKey = 'codsexo';
    protected $fillable = [
        'sexo',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
    ];


    // Chaves Estrangeiras
    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuario', 'codusuarioalteracao');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuario', 'codusuariocriacao');
    }


    // Tabelas Filhas
    public function PessoaS()
    {
        return $this->hasMany(Pessoa::class, 'codsexo', 'codsexo');
    }


}
