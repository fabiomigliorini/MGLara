<?php

namespace MGLara\Models;

class WooProduto extends MGModel
{
    protected $table = 'tblwooproduto';
    protected $primaryKey = 'codwooproduto';


    protected $fillable = [
        'codproduto',
        'codprodutobarraunidade',
        'codprodutovariacao',
        'exportacao',
        'id',
        'idvariation',
        'inativo',
        'integracao',
        'margempacote',
        'margemunidade',
        'quantidadeembalagem',
        'quantidadepacote'
    ];

    protected $dates = [
        'alteracao',
        'criacao',
        'exportacao',
        'inativo'
    ];

    protected $casts = [
        'codproduto' => 'integer',
        'codprodutobarraunidade' => 'integer',
        'codprodutovariacao' => 'integer',
        'codusuarioalteracao' => 'integer',
        'codusuariocriacao' => 'integer',
        'codwooproduto' => 'integer',
        'id' => 'integer',
        'idvariation' => 'integer',
        'margempacote' => 'float',
        'margemunidade' => 'float',
        'quantidadeembalagem' => 'float',
        'quantidadepacote' => 'float'
    ];


    // Chaves Estrangeiras
    public function Produto()
    {
        return $this->belongsTo(Produto::class, 'codproduto', 'codproduto');
    }

    public function ProdutoBarraUnidade()
    {
        return $this->belongsTo(ProdutoBarra::class, 'codprodutobarraunidade', 'codprodutobarra');
    }

    public function ProdutoVariacao()
    {
        return $this->belongsTo(ProdutoVariacao::class, 'codprodutovariacao', 'codprodutovariacao');
    }


    // Tabelas Filhas
    public function WooProdutoImagemS()
    {
        return $this->hasMany(WooProdutoImagem::class, 'codwooproduto', 'codwooproduto');
    }

}
