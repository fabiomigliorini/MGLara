<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codestoquelocalproduto             NOT NULL DEFAULT nextval('tblestoquelocalproduto_codestoquelocalproduto_seq'::regclass)
 * @property  bigint                         $codestoquelocal                    NOT NULL
 * @property  bigint                         $codproduto                         NOT NULL
 * @property  bigint                         $corredor                           
 * @property  bigint                         $prateleira                         
 * @property  bigint                         $coluna                             
 * @property  bigint                         $bloco                              
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 *
 * Chaves Estrangeiras
 * @property  EstoqueLocal                   $EstoqueLocal                  
 * @property  Produto                        $Produto                       
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 * @property  Estoquesaldo[]                 $EstoquesaldoS
 */

class EstoqueLocalProduto extends MGModel
{
    protected $table = 'tblestoquelocalproduto';
    protected $primaryKey = 'codestoquelocalproduto';
    protected $fillable = [
        'codestoquelocal',
        'codproduto',
        'corredor',
        'prateleira',
        'coluna',
        'bloco',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
    ];


    // Chaves Estrangeiras
    public function EstoqueLocal()
    {
        return $this->belongsTo(EstoqueLocal::class, 'codestoquelocal', 'codestoquelocal');
    }

    public function Produto()
    {
        return $this->belongsTo(Produto::class, 'codproduto', 'codproduto');
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
    public function EstoqueSaldoS()
    {
        return $this->hasMany(EstoqueSaldo::class, 'codestoquelocalproduto', 'codestoquelocalproduto');
    }


}
