<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codprodutoembalagem                NOT NULL DEFAULT nextval('tblprodutoembalagem_codprodutoembalagem_seq'::regclass)
 * @property  bigint                         $codproduto                         
 * @property  bigint                         $codunidademedida                   
 * @property  numeric(17,5)                  $quantidade                         
 * @property  numeric(14,2)                  $preco                              
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 *
 * Chaves Estrangeiras
 * @property  Produto                        $Produto                       
 * @property  UnidadeMedida                  $UnidadeMedida                 
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 * @property  ProdutoBarra[]                 $ProdutoBarraS
 * @property  ProdutoHistoricoPreco[]        $ProdutoHistoricoPrecoS
 */


class ProdutoEmbalagem extends MGModel
{
    protected $table = 'tblprodutoembalagem';
    protected $primaryKey = 'codprodutoembalagem';
    protected $fillable = [
        'codproduto',
        'codunidademedida',
        'quantidade',
        'preco',
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

    public function UnidadeMedida()
    {
        return $this->belongsTo(UnidadeMedida::class, 'codunidademedida', 'codunidademedida');
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
    public function ProdutoBarraS()
    {
        return $this->hasMany(ProdutoBarra::class, 'codprodutoembalagem', 'codprodutoembalagem');
    }

    public function ProdutoHistoricoPrecoS()
    {
        return $this->hasMany(ProdutoHistoricoPreco::class, 'codprodutoembalagem', 'codprodutoembalagem');
    }    

}
