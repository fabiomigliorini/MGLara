<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codibptax                          NOT NULL DEFAULT nextval('tblibptax_codibptax_seq'::regclass)
 * @property  varchar(10)                    $codigo                             NOT NULL
 * @property  bigint                         $codncm                             
 * @property  varchar(3)                     $ex                                 
 * @property  integer                        $tipo                               NOT NULL
 * @property  varchar(500)                   $descricao                          NOT NULL
 * @property  numeric(4,2)                   $nacionalfederal                    NOT NULL
 * @property  numeric(4,2)                   $importadosfederal                  NOT NULL
 * @property  numeric(4,2)                   $estadual                           NOT NULL
 * @property  numeric(4,2)                   $municipal                          NOT NULL
 * @property  date                           $vigenciainicio                     NOT NULL DEFAULT ('now'::text)::date
 * @property  date                           $vigenciafim                        NOT NULL DEFAULT ('now'::text)::date
 * @property  varchar(20)                    $chave                              NOT NULL
 * @property  varchar(20)                    $versao                             NOT NULL
 * @property  varchar(20)                    $fonte                              NOT NULL
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 *
 * Chaves Estrangeiras
 * @property  Ncm                            $Ncm                           
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 */

class Ibptax extends MGModel
{
    protected $table = 'tblibptax';
    protected $primaryKey = 'codibptax';
    protected $fillable = [
        'codigo',
        'codncm',
        'ex',
        'tipo',
        'descricao',
        'nacionalfederal',
        'importadosfederal',
        'estadual',
        'municipal',
        'vigenciainicio',
        'vigenciafim',
        'chave',
        'versao',
        'fonte',
    ];
    protected $dates = [
        'vigenciainicio',
        'vigenciafim',
        'alteracao',
        'criacao',
    ];


    // Chaves Estrangeiras
    public function Ncm()
    {
        return $this->belongsTo(Ncm::class, 'codncm', 'codncm');
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