<?php

namespace MGLara\Models;


class Produto extends MGModel
{
    protected $table = 'tblproduto';
    protected $primaryKey = 'codproduto';
    protected $fillable = [
        'produto',
        'referencia',
        'preco',
        'importado',
        'inativo',
        'site',
        'descricaosite',
        'codncm',
        'codcest',
        'codtipoproduto',
        'codtributacao',
        'codunidademedida',
        'codsubgrupoproduto',
        'codmarca',
    ];
    
    public function EstoqueSaldoS()
    {
        return $this->hasMany(EstoqueSaldo::class, 'codproduto', 'codproduto');
    } 
    
    public function ProdutoEmbalagemS()
    {
        return $this->hasMany(ProdutoEmbalagem::class, 'codproduto', 'codproduto');
    } 

    public function ProdutoBarraS()
    {
        return $this->hasMany(ProdutoBarra::class, 'codproduto', 'codproduto');
    } 

    public function UnidadeMedida()
    {
        return $this->belongsTo(UnidadeMedida::class, 'codunidademedida', 'codunidademedida');
    } 
    
    public function recalculaEstoque()
    {
        foreach ($this->ProdutoBarraS as $pb)
            $pb->recalculaEstoque();
    }
    
    // TODO: Criar Relacionamentos
    /*
     *         'codncm',
        'codcest',
        'codtipoproduto',
        'codtributacao',
        'codsubgrupoproduto',
        'codmarca',

     */
    
}
