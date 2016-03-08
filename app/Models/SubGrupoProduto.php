<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codsubgrupoproduto                 NOT NULL DEFAULT nextval('tblsubgrupoproduto_codsubgrupoproduto_seq'::regclass)
 * @property  bigint                         $codgrupoproduto                    
 * @property  varchar(50)                    $subgrupoproduto                    
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 *
 * Chaves Estrangeiras
 * @property  GrupoProduto                   $GrupoProduto                  
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 * @property  Produto[]                      $ProdutoS
 */

class SubGrupoProduto extends MGModel
{
    protected $table = 'tblsubgrupoproduto';
    protected $primaryKey = 'codsubgrupoproduto';
    protected $fillable = [
        'codgrupoproduto',
        'subgrupoproduto',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
    ];

    public function validate() {

        $this->_regrasValidacao = [
            'subgrupoproduto' => 'required|min:2', 
        ];    
        $this->_mensagensErro = [
            'subgrupoproduto.required' => 'Sub grupo de produto nao pode ser vazio.',
        ];
        return parent::validate();
    }    


    // Chaves Estrangeiras
    public function GrupoProduto()
    {
        return $this->belongsTo(GrupoProduto::class, 'codgrupoproduto', 'codgrupoproduto');
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
    public function ProdutoS()
    {
        return $this->hasMany(Produto::class, 'codsubgrupoproduto', 'codsubgrupoproduto');
    }

    
    public function scopeSubgrupoproduto($query, $subgrupoproduto, $codgrupoproduto)
    {
        $query->where('codgrupoproduto', "=", "$codgrupoproduto");
        if (trim($subgrupoproduto) != "")
        {
            $query->where('subgrupoproduto', "ILIKE", "%$subgrupoproduto%");
        }
    }     
    
}
