<?php

namespace MGLara\Models;

class ProdutoBarra extends MGModel
{
    protected $table = 'tblprodutobarra';
    protected $primaryKey = 'codprodutobarra';
    protected $fillable = [
        'codproduto',
        'codmarca',
        'codprodutoembalagem',
        'variacao',
        'barras',
        'referencia',
    ];
    
    public function Produto()
    {
        return $this->belongsTo(Produto::class, 'codproduto', 'codproduto');
    }
    
    public function ProdutoEmbalagem()
    {
        return $this->belongsTo(ProdutoEmbalagem::class, 'codprodutoembalagem', 'codprodutoembalagem');
    }
    
    public function NegocioProdutoBarraS()
    {
        return $this->hasMany(NegocioProdutoBarra::class, 'codprodutobarra', 'codprodutobarra');
    }
    
    public function NotaFiscalProdutoBarraS()
    {
        return $this->hasMany(NotaFiscalProdutoBarra::class, 'codprodutobarra', 'codprodutobarra');
    }

    public function recalculaEstoque()
    {
        foreach ($this->NotaFiscalProdutoBarraS as $nfpb)
            $nfpb->recalculaEstoque();
    }
    
    public function converteQuantidade($quantidade)
    {
        if (empty($this->codprodutoembalagem))
            return $quantidade;
        
        return $quantidade * $this->ProdutoEmbalagem->quantidade;
    }
    
    //TODO: Criar relacionamentos
    /**
        'codmarca',
     * 
     */

}
