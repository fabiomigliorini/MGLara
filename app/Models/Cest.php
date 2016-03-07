<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codcest                            NOT NULL DEFAULT nextval('tblcest_codcest_seq'::regclass)
 * @property  varchar(7)                     $cest                               NOT NULL
 * @property  varchar(8)                     $ncm                                NOT NULL
 * @property  varchar(600)                   $descricao                          NOT NULL
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 * @property  bigint                         $codncm                             
 *
 * Chaves Estrangeiras
 * @property  Ncm                            $Ncm                           
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 * @property  Produto[]                      $ProdutoS
 */

class Cest extends MGModel
{
    protected $table = 'tblcest';
    protected $primaryKey = 'codcest';
    protected $fillable = [
        'cest',
        'ncm',
        'descricao',
        'codncm',
    ];
    protected $dates = [
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
        return $this->belongsTo(Usuario::class, 'codusuario', 'codusuarioalteracao');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuario', 'codusuariocriacao');
    }


    // Tabelas Filhas
    public function ProdutoS()
    {
        return $this->hasMany(Produto::class, 'codcest', 'codcest');
    }


}
    