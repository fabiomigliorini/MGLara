<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codprodutobarra                    NOT NULL DEFAULT nextval('tblprodutobarra_codprodutobarra_seq'::regclass)
 * @property  bigint                         $codproduto                         NOT NULL
 * @property  bigint                         $codprodutovariacao                 NOT NULL
 * @property  varchar(50)                    $barras                             NOT NULL
 * @property  varchar(50)                    $referencia
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
 * @property  UnidadeMedida                  $UnidadeMedida
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
            //'barras'              => "required|uniqueMultiple:tblprodutobarra,codprodutobarra,$this->codprodutobarra,barras",
        ];
    
        $this->_mensagensErro = [
            'codproduto.required'           => 'O Código do Produto não pode ser vazio!',
            'codprodutovariação.required'   => 'A Variação não pode ser vazia!',
            'barras.unique_multiple'        => 'Este Código de Barras já existe!',
            //'barras.required'               => 'Informe o Código de Barras!',
        ];
        
        return parent::validate();
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
    
    public function calculaDigitoGtin($barras = null)
    {
        if (empty($barras)) {
            $barras = $this->barras;
        }
        
        //preenche com zeros a esquerda
        $codigo = "000000000000000000" . $barras;
        
        //pega 18 digitos
        $codigo = substr($codigo, -18);
        $soma = 0;

        //soma digito par *1 e impar *3
        for ($i = 1; $i<strlen($codigo); $i++)
        {
            $digito = substr($codigo, $i-1, 1);
            if ($i === 0 || !!($i && !($i%2))) {
                $multiplicador = 1;
            } else {
                $multiplicador = 3;
            }
            $soma +=  $digito * $multiplicador;
        }
        
        //subtrai da maior dezena
        $digito = (ceil($soma/10)*10) - $soma;	

        //retorna digitocalculado
        return $digito;
    }
    
    public function geraBarrasInterno()
    {
        $barras = (234000000000 + $this->codprodutobarra);
        $this->barras = $barras . $this->calculaDigitoGtin($barras . '0');
    }
    
    public function save(array $options = [])
    {
        
        if (empty($this->barras)) {
            if (empty($this->codprodutobarra)) {
                $codprodutobarra = \DB::select("select nextval('tblprodutobarra_codprodutobarra_seq') codprodutobarra");
                $codprodutobarra = intval($codprodutobarra['0']->codprodutobarra);
                $this->codprodutobarra = $codprodutobarra;
            }
            $this->geraBarrasInterno();
        }
        
        return parent::save();
        
    }
    
    public function descricao()
    {
        $descr = "{$this->Produto->produto} {$this->ProdutoVariacao->variacao}";
        if (!empty($this->codprodutoembalagem)) {
            $quant = formataNumero($this->ProdutoEmbalagem->quantidade, 0);
            $descr = "{$descr} C/{$quant}";
        }
        return trim($descr);
    }
    
    public function UnidadeMedida()
    {
        if (!empty($this->codprodutoembalagem)) {
            return $this->ProdutoEmbalagem->UnidadeMedida();
        } 
        return $this->Produto->UnidadeMedida();
    }
    
    public function referencia()
    {
        if (!empty($this->referencia)) {
            return $this->referencia;
        }
        if (!empty($this->ProdutoVariacao->referencia)) {
            return $this->ProdutoVariacao->referencia;
        } 
        return $this->Produto->referencia;
    }

    public function Marca()
    {
        if (!empty($this->ProdutoVariacao->codmarca)) {
            return $this->ProdutoVariacao->Marca();
        } 
        return $this->Produto->Marca();
    }

    public function Preco()
    {
        if (!empty($this->codprodutoembalagem)) {
            if (empty($this->ProdutoEmbalagem->preco)) {
                return $this->ProdutoEmbalagem->quantidade * $this->produto->preco;
            }
            return (float) $this->ProdutoEmbalagem->preco;
        }
        return (float) $this->Produto->preco;
    }
}
