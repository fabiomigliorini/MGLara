<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codmetafilial                      NOT NULL DEFAULT nextval('tblmetafilial_codmetafilial_seq'::regclass)
 * @property  bigint                         $codmeta                            NOT NULL
 * @property  bigint                         $codfilial                          NOT NULL
 * @property  numeric(14,2)                  $valormetafilial                    NOT NULL
 * @property  numeric(14,2)                  $valormetavendedor                  NOT NULL
 * @property  text                           $observacoes                        
 * @property  timestamp                      $criacao                            
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  bigint                         $codusuariocriacao                  
 *
 * Chaves Estrangeiras
 * @property  Filial                         $Filial                        
 * @property  Meta                           $Meta                          
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 * @property  MetaFilialPessoa[]             $MetaFilialPessoaS
 */

class MetaFilial extends MGModel
{
    protected $table = 'tblmetafilial';
    protected $primaryKey = 'codmetafilial';
    protected $fillable = [
        'codmeta',
        'codfilial',
        'valormetafilial',
        'valormetavendedor',
        'observacoes',
    ];
    protected $dates = [
        'criacao',
        'alteracao',
    ];


    // Chaves Estrangeiras
    public function Filial()
    {
        return $this->belongsTo(Filial::class, 'codfilial', 'codfilial');
    }

    public function Meta()
    {
        return $this->belongsTo(Meta::class, 'codmeta', 'codmeta');
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
    public function MetaFilialPessoaS()
    {
        return $this->hasMany(MetaFilialPessoa::class, 'codmetafilial', 'codmetafilial');
    }


}