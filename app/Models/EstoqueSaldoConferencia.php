<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codestoquesaldoconferencia         NOT NULL DEFAULT nextval('tblestoquesaldoconferencia_codestoquesaldoconferencia_seq'::regclass)
 * @property  bigint                         $codestoquesaldo                    NOT NULL
 * @property  numeric(14,3)                  $quantidadesistema                  
 * @property  numeric(14,3)                  $quantidadeinformada                NOT NULL
 * @property  numeric(14,6)                  $customediosistema                  
 * @property  numeric(14,6)                  $customedioinformado                NOT NULL
 * @property  timestamp                      $data                               NOT NULL
 * @property  varchar(200)                   $observacoes                        
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 *
 * Chaves Estrangeiras
 * @property  EstoqueSaldo                   $EstoqueSaldo                  
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 * @property  EstoqueMovimento[]             $EstoqueMovimentoS
 */

class EstoqueSaldoConferencia extends MGModel
{
    protected $table = 'tblestoquesaldoconferencia';
    protected $primaryKey = 'codestoquesaldoconferencia';
    protected $fillable = [
        'codestoquesaldo',
        'quantidadesistema',
        'quantidadeinformada',
        'customediosistema',
        'customedioinformado',
        'data',
        'observacoes',
    ];
    protected $dates = [
        'data',
        'alteracao',
        'criacao',
    ];


    // Chaves Estrangeiras
    public function EstoqueSaldo()
    {
        return $this->belongsTo(EstoqueSaldo::class, 'codestoquesaldo', 'codestoquesaldo');
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
    public function EstoqueMovimentoS()
    {
        return $this->hasMany(EstoqueMovimento::class, 'codestoquesaldoconferencia', 'codestoquesaldoconferencia');
    }


}