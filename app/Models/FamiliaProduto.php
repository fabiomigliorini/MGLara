<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codfamiliaproduto                  NOT NULL DEFAULT nextval('tblfamiliaproduto_codfamiliaproduto_seq'::regclass)
 * @property  varchar(50)                    $familiaproduto                     NOT NULL
 * @property  timestamp                      $inativo                            
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 * @property  bigint                         $codimagem                          
 * @property  bigint                         $codsecaoproduto                    NOT NULL
 *
 * Chaves Estrangeiras
 * @property  Usuario                        $UsuarioCriacao
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Imagem                         $Imagem                        
 * @property  Secaoproduto                   $Secaoproduto                  
 *
 * Tabelas Filhas
 * @property  Grupoproduto[]                 $GrupoprodutoS
 */

class FamiliaProduto extends MGModel
{
    protected $table = 'tblfamiliaproduto';
    protected $primaryKey = 'codfamiliaproduto';
    protected $fillable = [
        'familiaproduto',
        'inativo',
        'codimagem',
        'codsecaoproduto',
    ];
    protected $dates = [
        'inativo',
        'alteracao',
        'criacao',
    ];

    public function validate() {
        
        if ($this->codfamiliaproduto) {
            $unique_familiaproduto = 'unique:tblfamiliaproduto,familiaproduto,'.$this->codfamiliaproduto.',codfamiliaproduto';
        } else {
            $unique_familiaproduto = 'unique:tblfamiliaproduto,familiaproduto';
        }
        
        $this->_regrasValidacao = [
            'codsecaoproduto' => 'required', 
            'familiaproduto' => 'required|min:3', 
        ];    
        $this->_mensagensErro = [
            'codsecaoproduto.required'  => 'Selecione uma Seção de produto.',
            'familiaproduto.required'   => 'Família de produto nao pode ser vazio.',
            'familiaproduto.min'        => 'Família de produto deve ter mais de 3 caracteres.',
            'familiaproduto.unique'     => 'Esta Família já esta cadastrada',
        ];

        return parent::validate();
    }    

    // Chaves Estrangeiras
    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuario', 'codusuariocriacao');
    }

    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuario', 'codusuarioalteracao');
    }

    public function Imagem()
    {
        return $this->belongsTo(Imagem::class, 'codimagem', 'codimagem');
    }

    public function SecaoProduto()
    {
        return $this->belongsTo(Secaoproduto::class, 'codsecaoproduto', 'codsecaoproduto');
    }


    // Tabelas Filhas
    public function GrupoProdutoS()
    {
        return $this->hasMany(GrupoProduto::class, 'codfamiliaproduto', 'codfamiliaproduto');
    }

    // Buscas 
    public static function filterAndPaginate($id, $codsecaoproduto, $familiaproduto, $inativo)
    {
        return FamiliaProduto::id(numeroLimpo($id))
            ->where('codsecaoproduto', $codsecaoproduto)  
            ->familiaproduto($familiaproduto)
            ->inativo($inativo)
            ->orderBy('familiaproduto', 'ASC')
            ->paginate(20);
    }
    
    public function scopeId($query, $id)
    {
        if (trim($id) === '')
            return;
        
        $query->where('codfamiliaproduto', $id);
    }
    
    public function scopeFamiliaproduto($query, $familiaproduto)
    {
        if (trim($familiaproduto) === '')
            return;
        
        $familiaproduto = explode(' ', $familiaproduto);
        foreach ($familiaproduto as $str)
            $query->where('familiaproduto', 'ILIKE', "%$str%");
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
