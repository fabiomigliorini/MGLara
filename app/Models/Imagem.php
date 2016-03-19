<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codimagem                          NOT NULL DEFAULT nextval('tblimagem_codimagem_seq'::regclass)
 * @property  varchar(200)                   $observacoes                        
 * @property  timestamp                      $inativo                            
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 *
 * Chaves Estrangeiras
 *
 * Tabelas Filhas
 * @property  GrupoProduto[]                 $GrupoProdutoS
 * @property  Marca[]                        $MarcaS
 * @property  ProdutoImagem[]                $ProdutoImagemS
 * @property  SubGrupoProduto[]              $SubGrupoProdutoS
 */

class Imagem extends MGModel
{
    protected $table = 'tblimagem';
    protected $primaryKey = 'codimagem';
    protected $fillable = [
        'observacoes',
        'inativo',
    ];
    protected $dates = [
        'inativo',
        'criacao',
        'alteracao',
    ];


    // Chaves Estrangeiras

    // Tabelas Filhas
    public function GrupoProdutoS()
    {
        return $this->hasMany(GrupoProduto::class, 'codimagem', 'codimagem');
    }

    public function MarcaS()
    {
        return $this->hasMany(Marca::class, 'codimagem', 'codimagem');
    }

    public function ProdutoS()
    {
        return $this->belongsToMany(Produto::class, 'tblprodutoimagem', 'codimagem', 'codproduto');
    }

    public function SubGrupoProdutoS()
    {
        return $this->hasMany(SubGrupoProduto::class, 'codimagem', 'codimagem');
    }   

}
