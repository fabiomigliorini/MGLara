<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codvalecompraprodutobarra          NOT NULL DEFAULT nextval('tblvalecompramodeloprodutobarra_codvalecompraprodutobarra_seq'::regclass)
 * @property  bigint                         $codprodutobarra                    NOT NULL
 * @property  bigint                         $codvalecompramodelo                NOT NULL
 * @property  integer                        $quantidade                         NOT NULL
 * @property  numeric(14,2)                  $preco                              NOT NULL
 * @property  numeric(14,2)                  $total                              NOT NULL
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 *
 * Chaves Estrangeiras
 * @property  ValeCompraModelo               $ValeCompraModelo              
 * @property  Usuario                        $UsuarioCriacao
 * @property  Usuario                        $UsuarioAlteracao
 * @property  ProdutoBarra                   $ProdutoBarra                  
 *
 * Tabelas Filhas
 */

class ValeCompraModeloProdutoBarra extends MGModel
{
    protected $table = 'tblvalecompramodeloprodutobarra';
    protected $primaryKey = 'codvalecompramodeloprodutobarra';
    protected $fillable = [
        'codvalecompraprodutobarra',
        'codprodutobarra',
        'codvalecompramodelo',
        'quantidade',
        'preco',
        'total',
    ];
    protected $dates = [
        'criacao',
        'alteracao',
    ];


    // Chaves Estrangeiras
    public function ValeCompraModelo()
    {
        return $this->belongsTo(ValeCompraModelo::class, 'codvalecompramodelo', 'codvalecompramodelo');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuario', 'codusuariocriacao');
    }

    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuario', 'codusuarioalteracao');
    }

    public function ProdutoBarra()
    {
        return $this->belongsTo(ProdutoBarra::class, 'codprodutobarra', 'codprodutobarra');
    }


    // Tabelas Filhas

}