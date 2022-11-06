<?php

namespace MGLara\Models;


class MercosProduto extends MGModel
{
    protected $table = 'tblmercosproduto';
    protected $primaryKey = 'codmercosproduto';
    protected $fillable = [
        'codproduto',
        'codprodutovariacao',
        'codprodutoembalagem',
        'produtoid',
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
    public function MercosProdutoImagemS()
    {
        return $this->hasMany(MercosProdutoImagem::class, 'codmercosproduto', 'codmercosproduto');
    }

}
