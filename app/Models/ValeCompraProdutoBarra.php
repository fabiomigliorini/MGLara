<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codvalecompraprodutobarra          NOT NULL DEFAULT nextval('tblvalecompraprodutobarra_codvalecompraprodutobarra_seq'::regclass)
 * @property  bigint                         $codprodutobarra                    NOT NULL
 * @property  bigint                         $codvalecompra                      NOT NULL
 * @property  integer                        $quantidade                         NOT NULL
 * @property  numeric(14,2)                  $preco                              NOT NULL
 * @property  numeric(14,2)                  $total                              NOT NULL
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 *
 * Chaves Estrangeiras
 * @property  ProdutoBarra                   $ProdutoBarra                  
 * @property  ValeCompra                     $ValeCompra                    
 * @property  Usuario                        $UsuarioCriacao
 * @property  Usuario                        $UsuarioAlteracao
 *
 * Tabelas Filhas
 */

class ValeCompraProdutoBarra extends MGModel
{
    protected $table = 'tblvalecompraprodutobarra';
    protected $primaryKey = 'codvalecompraprodutobarra';
    protected $fillable = [
        'codprodutobarra',
        'codvalecompra',
        'quantidade',
        'preco',
        'total',
    ];
    protected $dates = [
        'criacao',
        'alteracao',
    ];


    // Chaves Estrangeiras
    public function ProdutoBarra()
    {
        return $this->belongsTo(ProdutoBarra::class, 'codprodutobarra', 'codprodutobarra');
    }

    public function ValeCompra()
    {
        return $this->belongsTo(ValeCompra::class, 'codvalecompra', 'codvalecompra');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuario', 'codusuariocriacao');
    }

    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuario', 'codusuarioalteracao');
    }


    // Tabelas Filhas

}