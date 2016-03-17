<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codgrupoproduto                    NOT NULL DEFAULT nextval('tblgrupoproduto_codgrupoproduto_seq'::regclass)
 * @property  varchar(50)                    $grupoproduto                       
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 *
 * Chaves Estrangeiras
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 * @property  SubGrupoProduto[]              $SubGrupoProdutoS
 */

class GrupoProduto extends MGModel
{
    protected $table = 'tblgrupoproduto';
    protected $primaryKey = 'codgrupoproduto';
    protected $fillable = [
        'grupoproduto',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
    ];

    public function validate() {

        $this->_regrasValidacao = [
            'grupoproduto' => 'required|min:5', 
        ];    
        $this->_mensagensErro = [
            'grupoproduto.required' => 'Grupo de produto nao pode ser vazio.',
            'grupoproduto.min' => 'Grupo de produto deve ter mais de 4 caracteres.',
        ];

        return parent::validate();
    }    

    // Chaves Estrangeiras
    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuario', 'codusuarioalteracao');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuario', 'codusuariocriacao');
    }


    // Tabelas Filhas
    public function SubGrupoProdutoS()
    {
        return $this->hasMany(SubGrupoProduto::class, 'codgrupoproduto', 'codgrupoproduto')->orderBy('subgrupoproduto');
    }

    // Buscas 
    public static function filterAndPaginate($codgrupoproduto, $grupoproduto)
    {
        return GrupoProduto::codgrupoproduto(numeroLimpo($codgrupoproduto))
            ->grupoproduto($grupoproduto)
            ->orderBy('grupoproduto', 'ASC')
            ->paginate(20);
    }
    
    public function scopeCodgrupoproduto($query, $codgrupoproduto)
    {
        if (trim($codgrupoproduto) === '')
            return;
        
        $query->where('codgrupoproduto', $codgrupoproduto);
    }
    
    public function scopeGrupoproduto($query, $grupoproduto)
    {
        if (trim($grupoproduto) === '')
            return;
        
        $grupoproduto = explode(' ', $grupoproduto);
        foreach ($grupoproduto as $str)
            $query->where('grupoproduto', 'ILIKE', "%$str%");
    }

}