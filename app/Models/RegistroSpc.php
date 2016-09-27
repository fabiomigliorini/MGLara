<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codregistrospc                     NOT NULL DEFAULT nextval('tblregistrospc_codregistrospc_seq'::regclass)
 * @property  bigint                         $codpessoa                          NOT NULL
 * @property  date                           $inclusao                           NOT NULL
 * @property  date                           $baixa                              
 * @property  numeric(14,2)                  $valor                              
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 * @property  varchar(500)                   $observacoes                        
 *
 * Chaves Estrangeiras
 * @property  Pessoa                         $Pessoa                        
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 */

class RegistroSpc extends MGModel
{
    protected $table = 'tblregistrospc';
    protected $primaryKey = 'codregistrospc';
    protected $fillable = [
        'codpessoa',
        'inclusao',
        'baixa',
        'valor',
        'observacoes',
    ];
    protected $dates = [
        'inclusao',
        'baixa',
        'alteracao',
        'criacao',
    ];


    // Chaves Estrangeiras
    public function Pessoa()
    {
        return $this->belongsTo(Pessoa::class, 'codpessoa', 'codpessoa');
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
    // ...
    
    public static function scopeByPessoa($query, $codpessoa)
    {
        $query->where('codpessoa', $codpessoa);
    }    
    
    
}
