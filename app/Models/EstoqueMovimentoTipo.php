<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codestoquemovimentotipo            NOT NULL DEFAULT nextval('tblestoquemovimentotipo_codestoquemovimentotipo_seq'::regclass)
 * @property  varchar(100)                   $descricao                          NOT NULL
 * @property  varchar(3)                     $sigla                              NOT NULL
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 * @property  smallint                       $preco                              NOT NULL
 *
 * Chaves Estrangeiras
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 * @property  NaturezaOperacao[]             $NaturezaOperacaoS
 * @property  EstoqueMovimento[]             $EstoqueMovimentoS
 */

class EstoqueMovimentotipo extends MGModel
{
    protected $table = 'tblestoquemovimentotipo';
    protected $primaryKey = 'codestoquemovimentotipo';
    protected $fillable = [
        'descricao',
        'sigla',
        'preco',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
    ];

    const PRECO_INFORMADO = 1;
    const PRECO_MEDIO = 2;
    const PRECO_ORIGEM = 3;

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
    public function NaturezaOperacaoS()
    {
        return $this->hasMany(NaturezaOperacao::class, 'codestoquemovimentotipo', 'codestoquemovimentotipo');
    }

    public function EstoqueMovimentoS()
    {
        return $this->hasMany(EstoqueMovimento::class, 'codestoquemovimentotipo', 'codestoquemovimentotipo');
    }

}
