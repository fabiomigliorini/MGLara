<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codtipoproduto                     NOT NULL DEFAULT nextval('tbltipoproduto_codtipoproduto_seq'::regclass)
 * @property  varchar()                      $tipoproduto                        NOT NULL DEFAULT 50
 * @property  boolean                        $estoque                            NOT NULL DEFAULT FALSE
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
 * @property  TributacaoNaturezaOperacao[]   $TributacaoNaturezaOperacaoS
 */

class TipoProduto extends MGModel
{
    const MERCADORIA = 0;
    
    protected $table = 'tbltipoproduto';
    protected $primaryKey = 'codtipoproduto';
    protected $fillable = [
        'tipoproduto',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
    ];
    
    public function validate() {
        $this->_regrasValidacao = [
            'tipoproduto' => 'required|min:2', 
        ];    
        $this->_mensagensErro = [
            'tipoproduto.required' => 'Tipo produto não pode ser vazio.',
        ];
        return parent::validate();
    }    
    
    // Chaves Estrangeiras
    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuarioalteracao', 'codusuario');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuariocriacao', 'codusuario');
    }


    // Tabelas Filhas
    public function ProdutoS()
    {
        return $this->hasMany(Produto::class, 'codtipoproduto', 'codtipoproduto');
    }

    public function TributacaoNaturezaOperacaoS()
    {
        return $this->hasMany(TributacaoNaturezaOperacao::class, 'codtipoproduto', 'codtipoproduto');
    }
    
    public static function search($parametros)
    {
        $query = TipoProduto::query();
            
        if(!empty($parametros['codtipoproduto'])) {
            $query->where('codtipoproduto', $parametros['codtipoproduto']);
        }
            
        if(!empty($parametros['tipoproduto'])) {
            $query->tipoproduto($parametros['tipoproduto']);
        }

        return $query;
    }
    
    public function scopeTipoproduto($query, $tipoproduto)
    {
        if (trim($tipoproduto) === '')
            return;
        
        $tipoproduto = explode(' ', $tipoproduto);
        foreach ($tipoproduto as $str)
            $query->where('tipoproduto', 'ILIKE', "%$str%");
    }     
}
