<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codvalecompramodelo                NOT NULL DEFAULT nextval('tblvalecompramodelo_codvalecompramodelo_seq'::regclass)
 * @property  bigint                         $codpessoafavorecido                NOT NULL
 * @property  varchar(50)                    $modelo                             
 * @property  varchar(30)                    $turma                              
 * @property  varchar(200)                   $observacoes                        NOT NULL
 * @property  numeric(14,2)                  $totalprodutos                      
 * @property  numeric(14,2)                  $desconto                           
 * @property  numeric(14,2)                  $total                              
 * @property  timestamp                      $inativo                            
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  integer                        $ano                                
 *
 * Chaves Estrangeiras
 * @property  Usuario                        $UsuarioCriacao
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Pessoa                         $PessoaFavorecido                        
 *
 * Tabelas Filhas
 * @property  ValeCompra[]                   $ValeCompraS
 * @property  ValeCompraModeloProdutoBarra[] $ValeCompraModeloProdutoBarraS
 */

class ValeCompraModelo extends MGModel
{
    protected $table = 'tblvalecompramodelo';
    protected $primaryKey = 'codvalecompramodelo';
    protected $fillable = [
        'codpessoafavorecido',
        'modelo',
        'turma',
        'observacoes',
        'totalprodutos',
        'desconto',
        'total',
        'inativo',
        'ano',
    ];
    protected $dates = [
        'inativo',
        'criacao',
        'alteracao',
    ];


    // Chaves Estrangeiras
    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuario', 'codusuariocriacao');
    }

    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuario', 'codusuarioalteracao');
    }

    public function PessoaFavorecido()
    {
        return $this->belongsTo(Pessoa::class, 'codpessoa', 'codpessoafavorecido');
    }


    // Tabelas Filhas
    public function ValeCompraS()
    {
        return $this->hasMany(ValeCompra::class, 'codvalecompramodelo', 'codvalecompramodelo');
    }

    public function ValeCompraModeloProdutoBarraS()
    {
        return $this->hasMany(ValeCompraModeloProdutoBarra::class, 'codvalecompramodelo', 'codvalecompramodelo');
    }


}