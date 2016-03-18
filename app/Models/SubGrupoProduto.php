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
            'subgrupoproduto' => 'required|min:5', 
        ];    
        $this->_mensagensErro = [
            'subgrupoproduto.required' => 'Sub grupo de produto nao pode ser vazio.',
            'subgrupoproduto.min' => 'Sub grupo de produto nao pode ter menos de 5 caracteres.',
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
        return $this->hasMany(Produto::class, 'codsubgrupoproduto', 'codsubgrupoproduto')->orderBy('produto');
    }

    
    // Buscas 
    public static function filterAndPaginate($codsubgrupoproduto, $subgrupoproduto)
    {
        return SubGrupoProduto::codsubgrupoproduto(numeroLimpo($codsubgrupoproduto))
            ->subgrupoproduto($subgrupoproduto)
            ->orderBy('subgrupoproduto', 'ASC')
            ->paginate(20);
    }
    
    public function scopeCodsubgrupoproduto($query, $codsubgrupoproduto)
    {
        if (trim($codsubgrupoproduto) === '')
            return;
        
        $query->where('codsubgrupoproduto', $codsubgrupoproduto);
    }
    
    public function scopeSubgrupoproduto($query, $subgrupoproduto)
    {
        if (trim($subgrupoproduto) === '')
            return;
        
        $subgrupoproduto = explode(' ', $subgrupoproduto);
        foreach ($subgrupoproduto as $str)
            $query->where('subgrupoproduto', 'ILIKE', "%$str%");
    }    
    
}
