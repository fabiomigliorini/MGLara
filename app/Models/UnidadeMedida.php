<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codunidademedida                   NOT NULL DEFAULT nextval('tblunidademedida_codunidademedida_seq'::regclass)
 * @property  varchar(15)                    $unidademedida                      
 * @property  varchar(3)                     $sigla                              
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
 * @property  Produto[]                      $ProdutoS
 * @property  ProdutoEmbalagem[]             $ProdutoEmbalagemS
 */

class UnidadeMedida extends MGModel
{
    protected $table = 'tblunidademedida';
    protected $primaryKey = 'codunidademedida';
    protected $fillable = [
        'unidademedida',
        'sigla',
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
    public function ProdutoS()
    {
        return $this->hasMany(Produto::class, 'codunidademedida', 'codunidademedida');
    }

    public function ProdutoEmbalagemS()
    {
        return $this->hasMany(ProdutoEmbalagem::class, 'codunidademedida', 'codunidademedida');
    }
}
