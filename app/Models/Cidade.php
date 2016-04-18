<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codcidade                          NOT NULL DEFAULT nextval('tblcidade_codcidade_seq'::regclass)
 * @property  bigint                         $codestado                          
 * @property  varchar(50)                    $cidade                             
 * @property  varchar(3)                     $sigla                              
 * @property  bigint                         $codigooficial                      
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 *
 * Chaves Estrangeiras
 * @property  Estado                         $Estado                        
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 * @property  Pessoa[]                       $PessoaCobrancaS
 * @property  Pessoa[]                       $PessoaS
 */

class Cidade extends MGModel
{
    protected $table = 'tblcidade';
    protected $primaryKey = 'codcidade';
    protected $fillable = [
        'codestado',
        'cidade',
        'sigla',
        'codigooficial',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
    ];


    // Chaves Estrangeiras
    public function Estado()
    {
        return $this->belongsTo(Estado::class, 'codestado', 'codestado');
    }

    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuario', 'codusuarioalteracao');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuario', 'codusuariocriacao');
    }


    // Tabelas Filhas
    public function PessoaCobrancaS()
    {
        return $this->hasMany(Pessoa::class, 'codcidade', 'codcidadecobranca');
    }

    public function PessoaS()
    {
        return $this->hasMany(Pessoa::class, 'codcidade', 'codcidade');
    }


}
