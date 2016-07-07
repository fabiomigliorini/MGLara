<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codprodutobarra                    NOT NULL DEFAULT nextval('tblprodutobarra_codprodutobarra_seq'::regclass)
 * @property  bigint                         $codproduto                         NOT NULL
 * @property  bigint                         $codprodutovariacao                 NOT NULL
 * @property  varchar(50)                    $barras                             NOT NULL
 * @property  bigint                         $codprodutovariacao                 
 * @property  bigint                         $codprodutoembalagem                
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 *
 * Chaves Estrangeiras
 * @property  Marca                          $Marca                         
 * @property  Produto                        $Produto                       
 * @property  ProdutoVariacao                $ProdutoVariacao               
 * @property  ProdutoEmbalagem               $ProdutoEmbalagem              
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 * @property  CupomFiscalProdutoBarra[]      $CupomFiscalProdutoBarraS
 * @property  NegocioProdutoBarra[]          $NegocioProdutoBarraS
 * @property  NfeTerceiroItem[]              $NfeTerceiroItemS
 * @property  NotaFiscalProdutoBarra[]       $NotaFiscalProdutoBarraS
 */

class ProdutoBarra extends MGModel
{
    protected $table = 'tblprodutobarra';
    protected $primaryKey = 'codprodutobarra';
    protected $fillable = [
        'codproduto',
        'codprodutovariacao',
        'barras',
        'variacao',
        'referencia',
        'codprodutoembalagem',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
    ];

    public function validate() {

        $this->_regrasValidacao = [            
            'codproduto'          => 'required',
            'codprodutovariacao'  => 'required',
            'barras'              => "required|uniqueMultiple:tblprodutobarra,codprodutobarra,$this->codprodutobarra,barras",
        ];
    
        $this->_mensagensErro = [
            'codproduto.required'           => 'O Código do Produto não pode ser vazio!',
            'codprodutovariação.required'   => 'A Variação não pode ser vazia!',
            'barras.unique_multiple'        => 'Este Código de Barras já existe!',
            'barras.required'               => 'Informe o Código de Barras!',
        ];
        
        return parent::validate();
    } 
    
    // Chaves Estrangeiras
    public function Marca()
    {
        return $this->belongsTo(Marca::class, 'codmarca', 'codmarca');
    }

    public function Produto()
    {
        return $this->belongsTo(Produto::class, 'codproduto', 'codproduto');
    }

    public function ProdutoVariacao()
    {
        return $this->belongsTo(ProdutoVariacao::class, 'codprodutovariacao', 'codprodutovariacao');
    }

    public function ProdutoEmbalagem()
    {
        return $this->belongsTo(ProdutoEmbalagem::class, 'codprodutoembalagem', 'codprodutoembalagem');
    }

    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuarioalteracao', 'codusuario');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuariocriacao', 'codusuario');
    }


    // Tabelas Filhas
    public function CupomFiscalProdutoBarraS()
    {
        return $this->hasMany(CupomFiscalProdutoBarra::class, 'codprodutobarra', 'codprodutobarra');
    }

    public function NegocioProdutoBarraS()
    {
        return $this->hasMany(NegocioProdutoBarra::class, 'codprodutobarra', 'codprodutobarra');
    }

    public function NfeTerceiroItemS()
    {
        return $this->hasMany(NfeTerceiroItem::class, 'codprodutobarra', 'codprodutobarra');
    }

    public function NotaFiscalProdutoBarraS()
    {
        return $this->hasMany(NotaFiscalProdutoBarra::class, 'codprodutobarra', 'codprodutobarra');
    }

    public function converteQuantidade($quantidade)
    {
        if (empty($this->codprodutoembalagem))
            return $quantidade;
        
        return $quantidade * $this->ProdutoEmbalagem->quantidade;
    }
    
    public static function buscaPorBarras($barras)
    {
        //Procura pelo Codigo de Barras
        if ($ret = ProdutoBarra::where('barras', '=', $barras)->first())
            return $ret;

        //Procura pelo Codigo Interno
        if (strlen($barras) == 6 && ($barras == numeroLimpo($barras)))
            if ($ret = ProdutoBarra::where('codproduto', '=', $barras)->whereNull('codprodutoembalagem')->first())
                return $ret;

        //Procura pelo Codigo Interno * Embalagem
        if (strstr($barras, '-'))
        {
            $arr = explode('-', $barras);
            if (count($arr == 2))
            {
                $codigo = numeroLimpo($arr[0]);
                $quantidade = numeroLimpo($arr[1]);

                if ($barras == "$codigo-$quantidade")
                    if ($ret = ProdutoBarra::where('codproduto', $codigo)->whereHas('ProdutoEmbalagem', function($query) use ($quantidade){
                            $query->where('quantidade', $quantidade);
                            })->first())
                        return $ret;
            }
        }
        
        return false;

    }    
}
