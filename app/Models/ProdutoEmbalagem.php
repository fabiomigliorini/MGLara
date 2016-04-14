<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codprodutoembalagem                NOT NULL DEFAULT nextval('tblprodutoembalagem_codprodutoembalagem_seq'::regclass)
 * @property  bigint                         $codproduto                         
 * @property  bigint                         $codunidademedida                   
 * @property  numeric(17,5)                  $quantidade                         
 * @property  numeric(14,2)                  $preco                              
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 *
 * Chaves Estrangeiras
 * @property  Produto                        $Produto                       
 * @property  UnidadeMedida                  $UnidadeMedida                 
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 * @property  ProdutoBarra[]                 $ProdutoBarraS
 * @property  ProdutoHistoricoPreco[]        $ProdutoHistoricoPrecoS
 */


class ProdutoEmbalagem extends MGModel
{
    protected $table = 'tblprodutoembalagem';
    protected $primaryKey = 'codprodutoembalagem';
    protected $fillable = [
        'codproduto',
        'codunidademedida',
        'quantidade',
        'preco',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
    ];
    
    
    public function validate() {

        $this->_regrasValidacao = [            
            'codunidademedida'  => 'required|numeric',
            'quantidade' => "required|numeric|validaQuantidade:$this->codproduto,$this->codprodutoembalagem",
            'preco' => "numeric|min:0.01|validaPrecoMin:$this->codproduto|validaPrecoMax:$this->codproduto,$this->quantidade",          
        ];
    
        $this->_mensagensErro = [
            'codunidademedida.required'         => 'O campo Unidade Medida não pode ser vazio',
            'codunidademedida.numeric'          => 'O campo Unidade Medida deve ser um valor numérico',
            'quantidade.required'               => 'O campo Quantidade deve ser preenchido',
            'quantidade.numeric'                => 'O campo Quantidade deve ser um valor numérico',
            'quantidade.validaQuantidade'       => 'Erroquantidade',
            'preco.numeric'                     => 'A descrição do produto não pode ter menos de 10 caracteres',
            'preco.min'                         => 'A descrição do produto não pode ter menos de 10 caracteres',
            'preco.valida_preco_min'                 => 'O preço deve ser maior do que preço unitário',
            'preco.valida_preco_max'                 => 'O preço deve ser menos que preço unitário X quantidade da embalagem',
        ];
        
        return parent::validate();
    }     
    
    // Chaves Estrangeiras
    public function Produto()
    {
        return $this->belongsTo(Produto::class, 'codproduto', 'codproduto');
    }

    public function UnidadeMedida()
    {
        return $this->belongsTo(UnidadeMedida::class, 'codunidademedida', 'codunidademedida');
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
    public function ProdutoBarraS()
    {
        return $this->hasMany(ProdutoBarra::class, 'codprodutoembalagem', 'codprodutoembalagem');
    }

    public function ProdutoHistoricoPrecoS()
    {
        return $this->hasMany(ProdutoHistoricoPreco::class, 'codprodutoembalagem', 'codprodutoembalagem');
    }    

    /*
     * Verifica se já existe uma embalagem com a mesma quantidade a ser cadastrada.
     */
    public static function validaQuantidade($codproduto, $quantidade, $codprodutoembalagem)
    {
        $query = ProdutoEmbalagem::where('codproduto', $codproduto)
                ->where('quantidade', $quantidade)
                ->where('codprodutoembalagem', $codprodutoembalagem)
                ->get();
        
        return $query;
    }
    
}
