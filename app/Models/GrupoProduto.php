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
 * @property  FamiliaProduto                 $FamiliaProduto                
 * @property  Imagem                         $Imagem     
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
        'inativo',
        'alteracao',
        'criacao',
    ];

    public function validate() {
        
        if ($this->codgrupoproduto) {
            $unique_grupoproduto = 'unique:tblgrupoproduto,grupoproduto,'.$this->codgrupoproduto.',codgrupoproduto';
        } else {
            $unique_grupoproduto = 'unique:tblgrupoproduto,grupoproduto';
        }

        $this->_regrasValidacao = [
            'grupoproduto' => "required|min:5|$unique_grupoproduto", 
        ];    
        $this->_mensagensErro = [
            'grupoproduto.required' => 'Grupo de produto nao pode ser vazio.',
            'grupoproduto.unique' => 'Este Grupo de produto ja esta cadastrado.',
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

    public function FamiliaProduto()
    {
        return $this->belongsTo(FamiliaProduto::class, 'codfamiliaproduto', 'codfamiliaproduto');
    }

    public function Imagem()
    {
        return $this->belongsTo(Imagem::class, 'codimagem', 'codimagem');
    }

    // Tabelas Filhas
    public function SubGrupoProdutoS()
    {
        return $this->hasMany(SubGrupoProduto::class, 'codgrupoproduto', 'codgrupoproduto')->orderBy('subgrupoproduto');
    }

    // Buscas 
    public static function filterAndPaginate($id, $codfamiliaproduto, $grupoproduto, $inativo)
    {
        return GrupoProduto::id(numeroLimpo($id))
            ->where('codfamiliaproduto', $codfamiliaproduto)
            ->grupoproduto($grupoproduto)
            ->inativo($inativo)
            ->orderBy('grupoproduto', 'ASC')
            ->paginate(20);
    }
    
    public function scopeId($query, $id)
    {
        if (trim($id) === '')
            return;
        
        $query->where('codgrupoproduto', $id);
    }
    
    public function scopeGrupoproduto($query, $grupoproduto)
    {
        if (trim($grupoproduto) === '')
            return;
        
        $grupoproduto = explode(' ', $grupoproduto);
        foreach ($grupoproduto as $str)
            $query->where('grupoproduto', 'ILIKE', "%$str%");
    }
    
    public function scopeInativo($query, $inativo)
    {
        if (trim($inativo) === '')
            $query->whereNull('inativo');
        
        if($inativo == 1)
            $query->whereNull('inativo');

        if($inativo == 2)
            $query->whereNotNull('inativo');
    }

}