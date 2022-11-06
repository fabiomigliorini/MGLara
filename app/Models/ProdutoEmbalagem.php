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
        'peso',
        'altura',
        'largura',
        'profundidade'
    ];
    protected $dates = [
        'alteracao',
        'criacao',
    ];

    public function getDescricaoAttribute()
    {
        if (floor($this->quantidade) == $this->quantidade)
            $digitos = 0;
        else
            $digitos = 5;

        return $this->UnidadeMedida->sigla . ' C/' . formataNumero($this->quantidade, $digitos);
    }

    public function getPrecoCalculadoAttribute()
    {
        if ($this->Produto)
            $preco_calculado = ($this->preco) ? $this->preco : $this->Produto->preco * $this->quantidade;

        return $preco_calculado;
    }

    public function validate()
    {

        $preco = 'numeric|min:0.01';

        if (!empty($this->codproduto))
        {
            $preco .= "|min:" . ($this->Produto->preco * $this->quantidade * .5);
            $preco .= "|max:" . $this->Produto->preco * $this->quantidade;
        }

        $this->_regrasValidacao = [
            'codproduto'  => 'required|numeric',
            'codunidademedida'  => 'required|numeric',
            //'quantidade' => "required|numeric|validaQuantidade:$this->codproduto,$this->codprodutoembalagem",
            'quantidade' => "required|numeric|uniqueMultiple:tblprodutoembalagem,codprodutoembalagem,$this->codprodutoembalagem,quantidade,codproduto,$this->codproduto",
            'preco' => $preco,
        ];

        $this->_mensagensErro = [
            'codunidademedida.required'         => 'O campo Unidade Medida não pode ser vazio!',
            'codunidademedida.numeric'          => 'O campo Unidade Medida deve ser um valor numérico!',
            'quantidade.required'               => 'O campo Quantidade deve ser preenchido!',
            'quantidade.numeric'                => 'O campo Quantidade deve conter um valor numérico!',
            'quantidade.unique_multiple'        => 'Já existe uma embalagem cadastrada com esta mesma quantidade!',
            'preco.max'                         => 'Preço maior que o custo unitário!',
            'preco.min'                         => 'Preço inferior à 50% do custo unitário!',
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
        return $this->belongsTo(Usuario::class, 'codusuarioalteracao', 'codusuario');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuariocriacao', 'codusuario');
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

    public function MagazordProduto()
    {
        return $this->hasMany(MagazordProduto::class, 'codprodutoembalagem', 'codprodutoembalagem');
    }

}
