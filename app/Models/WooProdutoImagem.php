<?php

namespace MGLara\Models;

class WooProdutoImagem extends MGModel
{
    protected $table = 'tblwooprodutoimagem';
    protected $primaryKey = 'codwooprodutoimagem';


    protected $fillable = [
        'codprodutoimagem',
        'codwooproduto',
        'id'
    ];

    protected $dates = [
        'alteracao',
        'criacao'
    ];

    protected $casts = [
        'codprodutoimagem' => 'integer',
        'codusuarioalteracao' => 'integer',
        'codusuariocriacao' => 'integer',
        'codwooproduto' => 'integer',
        'codwooprodutoimagem' => 'integer',
        'id' => 'integer'
    ];


    // Chaves Estrangeiras
    public function ProdutoImagem()
    {
        return $this->belongsTo(ProdutoImagem::class, 'codprodutoimagem', 'codprodutoimagem');
    }

    public function WooProduto()
    {
        return $this->belongsTo(WooProduto::class, 'codwooproduto', 'codwooproduto');
    }

}
