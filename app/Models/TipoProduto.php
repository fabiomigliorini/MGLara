<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codtipoproduto                     NOT NULL DEFAULT nextval('tbltipoproduto_codtipoproduto_seq'::regclass)
 * @property  varchar()                      $tipoproduto                        NOT NULL DEFAULT 50
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
            'tipoproduto.required' => 'Tipo não pode ser vazio.',
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
        return $this->hasMany(Produto::class, 'codtipoproduto', 'codtipoproduto');
    }

    public function TributacaoNaturezaOperacaoS()
    {
        return $this->hasMany(TributacaoNaturezaOperacao::class, 'codtipoproduto', 'codtipoproduto');
    }
    
    
    public function scopeTributacao($query, $tipoproduto)
    {
        if (trim($tipoproduto) != "")
        {
            $query->where('tipoproduto', "ILIKE", "%$tipoproduto%");
        }
    }    
}
