<?php

namespace MGLara\Models;


class MagazordProduto extends MGModel
{
    protected $table = 'tblmagazordproduto';
    protected $primaryKey = 'codmagazordproduto';
    protected $fillable = [
        'codproduto',
        'codprodutovariacao',
        'codprodutoembalagem',
        'sku',
        'precovarejo',
        'precovarejoatualizado',
        'precoatacado',
        'precoatacadoatualizado',
        'saldoquantidade',
        'saldoquantidadeatualizado',
    ];
    protected $dates = [
        'precovarejoatualizado',
        'precoatacadoatualizado',
        'saldoquantidadeatualizado',
        'alteracao',
        'criacao',
    ];

    // Chaves Estrangeiras
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
    public function ProdutoBarraS()
    {
        return $this->hasMany(ProdutoBarra::class, 'codprodutoembalagem', 'codprodutoembalagem');
    }

    public function ProdutoHistoricoPrecoS()
    {
        return $this->hasMany(ProdutoHistoricoPreco::class, 'codprodutoembalagem', 'codprodutoembalagem');
    }

}
