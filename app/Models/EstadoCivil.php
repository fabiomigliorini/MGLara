<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codestadocivil                     NOT NULL DEFAULT nextval('tblestadocivil_codestadocivil_seq'::regclass)
 * @property  varchar(50)                    $estadocivil                        NOT NULL
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

class Estadocivil extends MGModel
{
    protected $table = 'tblestadocivil';
    protected $primaryKey = 'codestadocivil';
    protected $fillable = [
        'estadocivil',
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
    public function PessoaS()
    {
        return $this->hasMany(Pessoa::class, 'codestadocivil', 'codestadocivil');
    }


}
