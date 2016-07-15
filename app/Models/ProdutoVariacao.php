<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codprodutovariacao                 NOT NULL DEFAULT nextval('tblprodutovariacao_codprodutovariacao_seq'::regclass)
 * @property  varchar(100)                   $variacao                           
 * @property  bigint                         $codproduto                         NOT NULL
 * @property  bigint                         $codmarca                           
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 * @property  varchar(50)                    $referencia                         
 *
 * Chaves Estrangeiras
 * @property  Marca                          $Marca                         
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 * @property  Produto                        $Produto                       
 *
 * Tabelas Filhas
 * @property  ProdutoBarra[]                 $ProdutoBarraS
 * @property  EstoqueLocalProdutoVariacao[]  $EstoqueLocalProdutoVariacaoS
 */

class ProdutoVariacao extends MGModel
{
    protected $table = 'tblprodutovariacao';
    protected $primaryKey = 'codprodutovariacao';
    protected $fillable = [
        'variacao',
        'codproduto',
        'codmarca',
        'referencia',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
    ];


    // Chaves Estrangeiras
    public function Marca()
    {
        return $this->belongsTo(Marca::class, 'codmarca', 'codmarca');
    }

    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuarioalteracao', 'codusuario');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuariocriacao', 'codusuario');
    }

    public function Produto()
    {
        return $this->belongsTo(Produto::class, 'codproduto', 'codproduto');
    }


    // Tabelas Filhas
    public function ProdutoBarraS()
    {
        return $this->hasMany(ProdutoBarra::class, 'codprodutovariacao', 'codprodutovariacao');
    }

    public function EstoqueLocalProdutoVariacaoS()
    {
        return $this->hasMany(EstoqueLocalProdutoVariacao::class, 'codprodutovariacao', 'codprodutovariacao');
    }


    public function validate()
    {
        
        $this->_regrasValidacao = [
          'variacao' => "uniqueMultiple:tblprodutovariacao,codprodutovariacao,$this->codprodutovariacao,variacao,codproduto,$this->codproduto",
          'codmarca' => "not_in:{$this->Produto->codmarca}"
        ];
        $this->_mensagensErro = [
            'variacao.unique_multiple' => 'Esta Variação já está cadastrada!',
            'variacao.min' => 'Variação deve ter mais de 3 caracteres!',
            'variacao.required' => 'Já existe uma Variação em branco, preencha a descrição desta nova Variação!',
            'codmarca.not_in' => 'Somente selecione a Marca caso seja diferente do produto!',
        ];
        
        if (isset($this->codproduto) && empty($this->variacao))
            if ($this->Produto->ProdutoVariacaoS()->whereNull('variacao')->count() > 0)
                $this->_regrasValidacao['variacao'] = 'required|' . $this->_regrasValidacao['variacao'];

        $ret = parent::validate();
        
        return $ret;
    }    


}