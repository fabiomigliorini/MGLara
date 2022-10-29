<?php

namespace MGLara\Models;

class ProdutoImagem extends MGModel
{
    protected $table = 'tblprodutoimagem';
    protected $primaryKey = 'codprodutoimagem';
    protected $fillable = [
        'codproduto',
        'codimagem',
        'ordem',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
    ];


    // Chaves Estrangeiras
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

    public function Imagem()
    {
        return $this->belongsTo(Imagem::class, 'codimagem', 'codimagem');
    }


    // Tabelas Filhas
    public function ProdutoImagemProdutoVariacaoS()
    {
        return $this->hasMany(ProdutoImagemProdutoVariacao::class, 'codprodutoimagem', 'codprodutoimagem');
    }



}
