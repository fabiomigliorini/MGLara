<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codunidademedida                   NOT NULL DEFAULT nextval('tblunidademedida_codunidademedida_seq'::regclass)
 * @property  varchar(15)                    $unidademedida                      
 * @property  varchar(3)                     $sigla                              
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
 * @property  Produto[]                      $ProdutoS
 * @property  ProdutoEmbalagem[]             $ProdutoEmbalagemS
 */

class UnidadeMedida extends MGModel
{
    protected $table = 'tblunidademedida';
    protected $primaryKey = 'codunidademedida';
    protected $fillable = [
        'unidademedida',
        'sigla',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
    ];
    
    public function validate() {
        if ($this->codunidademedida) {
            $unique_unidademedida = 'unique:tblunidademedida,unidademedida,'.$this->codunidademedida.',codunidademedida';
            $unique_sigla = 'unique:tblunidademedida,sigla,'.$this->codunidademedida.',codunidademedida';
        } else {
            $unique_unidademedida = 'unique:tblunidademedida,unidademedida';
            $unique_sigla = 'unique:tblunidademedida,sigla';
        }           
        
        $this->_regrasValidacao = [
            'unidademedida' => "required|$unique_unidademedida",  
            'sigla' => "required|$unique_sigla",  
        ];
    
        $this->_mensagensErro = [
            'unidademedida.required' => 'O campo Descrição não pode ser vazio',
            'unidademedida.unique' => 'Esta descrição já esta cadastrada',
            'sigla.required' => 'O campo Sigla não pode ser vazio',
            'sigla.unique' => 'Esta sigla já esta cadastrado',
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
    public function ProdutoS()
    {
        return $this->hasMany(Produto::class, 'codunidademedida', 'codunidademedida');
    }

    public function ProdutoEmbalagemS()
    {
        return $this->hasMany(ProdutoEmbalagem::class, 'codunidademedida', 'codunidademedida');
    }
    
    public static function search($parametros)
    {
        $query = UnidadeMedida::query();
            
        if(!empty($parametros['codunidademedida'])) {
            $query->where('codunidademedida', $parametros['codunidademedida']);
        }
            
        if(!empty($parametros['unidademedida'])) {
            $query->unidademedida($parametros['unidademedida']);
        }

        return $query;
    }
    
    public function scopeUnidademedida($query, $unidademedida)
    {
        if (trim($unidademedida) === '')
            return;
        
        $unidademedida = explode(' ', $unidademedida);
        foreach ($unidademedida as $str)
            $query->where('unidademedida', 'ILIKE', "%$str%");
    }    
}
