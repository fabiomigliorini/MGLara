<?php

namespace MGLara\Models;

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
    
    public function Produto()
    {
        return $this->belongsTo(Produto::class, 'codproduto', 'codproduto');
    }
    
    public function UnidadeMedida()
    {
        return $this->belongsTo(UnidadeMedida::class, 'codunidademedida', 'codunidademedida');
    } 
    
    public function ProdutoBarraS()
    {
        return $this->hasMany(ProdutoBarra::class, 'codprodutoembalagem', 'codprodutoembalagem');
    } 

}
