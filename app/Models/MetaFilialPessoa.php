<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codmetafilialpessoa                NOT NULL DEFAULT nextval('tblmetafilialpessoa_codmetafilialpessoa_seq'::regclass)
 * @property  bigint                         $codmetafilial                      NOT NULL
 * @property  bigint                         $codpessoa                          NOT NULL
 * @property  bigint                         $codcargo                           NOT NULL
 * @property  timestamp                      $criacao                            
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  bigint                         $codusuariocriacao                  
 *
 * Chaves Estrangeiras
 * @property  Pessoa                         $Pessoa                        
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 * @property  Cargo                          $Cargo                         
 * @property  MetaFilial                     $MetaFilial                    
 *
 * Tabelas Filhas
 */

class MetaFilialPessoa extends MGModel
{
    protected $table = 'tblmetafilialpessoa';
    protected $primaryKey = 'codmetafilialpessoa';
    protected $fillable = [
        'codmetafilial',
        'codpessoa',
        'codcargo',
    ];
    protected $dates = [
        'criacao',
        'alteracao',
    ];


    // Chaves Estrangeiras
    public function Pessoa()
    {
        return $this->belongsTo(Pessoa::class, 'codpessoa', 'codpessoa');
    }

    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuario', 'codusuarioalteracao');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuario', 'codusuariocriacao');
    }

    public function Cargo()
    {
        return $this->belongsTo(Cargo::class, 'codcargo', 'codcargo');
    }

    public function MetaFilial()
    {
        return $this->belongsTo(MetaFilial::class, 'codmetafilial', 'codmetafilial');
    }


    // Tabelas Filhas

}

