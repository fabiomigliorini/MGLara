<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codvalecompraformapagamento        NOT NULL DEFAULT nextval('tblvalecompraformapagamento_codvalecompraformapagamento_seq'::regclass)
 * @property  bigint                         $codvalecompra                      NOT NULL
 * @property  bigint                         $codformapagamento                  NOT NULL
 * @property  numeric(14,2)                  $valorpagamento                     NOT NULL
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 *
 * Chaves Estrangeiras
 * @property  ValeCompra                     $ValeCompra
 * @property  FormaPagamento                 $FormaPagamento
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 * @property  Titulo[]                       $TituloS
 */

class ValeCompraFormaPagamento extends MGModel
{
    protected $table = 'tblvalecompraformapagamento';
    protected $primaryKey = 'codvalecompraformapagamento';
    protected $fillable = [
        'codvalecompra',
        'codformapagamento',
        'valorpagamento',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
    ];


    // Chaves Estrangeiras
    public function ValeCompra()
    {
        return $this->belongsTo(ValeCompra::class, 'codvalecompra', 'codvalecompra');
    }

    public function FormaPagamento()
    {
        return $this->belongsTo(FormaPagamento::class, 'codformapagamento', 'codformapagamento');
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
    public function TituloS()
    {
        return $this->hasMany(Titulo::class, 'codvalecompraformapagamento', 'codvalecompraformapagamento');
    }


}