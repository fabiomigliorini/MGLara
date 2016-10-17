<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codnegocioformapagamento           NOT NULL DEFAULT nextval('tblnegocioformapagamento_codnegocioformapagamento_seq'::regclass)
 * @property  bigint                         $codnegocio                         NOT NULL
 * @property  bigint                         $codformapagamento                  NOT NULL
 * @property  numeric(14,2)                  $valorpagamento                     NOT NULL
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 *
 * Chaves Estrangeiras
 * @property  FormaPagamento                 $FormaPagamento                
 * @property  Negocio                        $Negocio                       
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 * @property  Titulo[]                       $TituloS
 */

class NegocioFormaPagamento extends MGModel
{
    protected $table = 'tblnegocioformapagamento';
    protected $primaryKey = 'codnegocioformapagamento';
    protected $fillable = [
        'codnegocio',
        'codformapagamento',
        'valorpagamento',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
    ];


    // Chaves Estrangeiras
    public function FormaPagamento()
    {
        return $this->belongsTo(FormaPagamento::class, 'codformapagamento', 'codformapagamento');
    }

    public function Negocio()
    {
        return $this->belongsTo(Negocio::class, 'codnegocio', 'codnegocio');
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
    public function TituloS()
    {
        return $this->hasMany(Titulo::class, 'codnegocioformapagamento', 'codnegocioformapagamento');
    }


}

