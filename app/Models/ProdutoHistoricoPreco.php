<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codprodutohistoricopreco           NOT NULL DEFAULT nextval('tblprodutohistoricopreco_codprodutohistoricopreco_seq'::regclass)
 * @property  bigint                         $codproduto                         NOT NULL
 * @property  bigint                         $codprodutoembalagem                
 * @property  numeric(14,2)                  $precoantigo                        
 * @property  numeric(14,2)                  $preconovo                          
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 *
 * Chaves Estrangeiras
 * @property  Produto                        $Produto                       
 * @property  Produtoembalagem               $Produtoembalagem              
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 */

class ProdutoHistoricoPreco extends MGModel
{
    protected $table = 'tblprodutohistoricopreco';
    protected $primaryKey = 'codprodutohistoricopreco';
    protected $fillable = [
        'codproduto',
        'codprodutoembalagem',
        'precoantigo',
        'preconovo',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
    ];


    // Chaves Estrangeiras
    public function Produto()
    {
        return $this->belongsTo(Produto::class, 'codproduto', 'codproduto');
    }

    public function ProdutoEmbalagem()
    {
        return $this->belongsTo(Produtoembalagem::class, 'codprodutoembalagem', 'codprodutoembalagem');
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

}
