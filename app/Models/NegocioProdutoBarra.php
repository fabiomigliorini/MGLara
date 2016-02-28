<?php

namespace MGLara\Models;

class NegocioProdutoBarra extends MGModel
{
    protected $table = 'tblnegocioprodutobarra';
    protected $primaryKey = 'codnegocioprodutobarra';
    
    public function ProdutoBarra()
    {
        return $this->belongsTo(ProdutoBarra::class, 'codprodutobarra', 'codprodutobarra');
    }
    
    public function Negocio()
    {
        return $this->belongsTo(Negocio::class, 'codnegocio', 'codnegocio');
    }
    
    public function EstoqueMovimentoS()
    {
        return $this->hasMany(EstoqueMovimento::class, 'codnegocioprodutobarra', 'codnegocioprodutobarra');
    }
    
    public function NotaFiscalProdutoBarraS()
    {
        return $this->hasMany(NotaFiscalProdutoBarra::class, 'codnegocioprodutobarra', 'codnegocioprodutobarra');
    }
    
    public function NegocioProdutoBarraDevolucaoS()
    {
        return $this->hasMany(NegocioProdutoBarra::class, 'codnegocioprodutobarradevolucao', 'codnegocioprodutobarra');
    }
    
    public function NegocioProdutoBarraDevolucao()
    {
        return $this->belongsTo(NegocioProdutoBarra::class, 'codnegocioprodutobarra', 'codnegocioprodutobarradevolucao');
    }
    
    
}
