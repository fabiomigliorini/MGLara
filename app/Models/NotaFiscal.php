<?php

namespace MGLara\Models;

class NotaFiscal extends MGModel
{
    protected $table = 'tblnotafiscal';
    protected $primaryKey = 'codnotafiscal';
    protected $dates = ['emissao', 'saida'];
    
    public function NaturezaOperacao()
    {
        return $this->belongsTo(NaturezaOperacao::class, 'codnaturezaoperacao', 'codnaturezaoperacao');
    }
    
    public function EstoqueLocal()
    {
        return $this->belongsTo(EstoqueLocal::class, 'codestoquelocal', 'codestoquelocal');
    }
    
    public function Filial()
    {
        return $this->belongsTo(Filial::class, 'codfilial', 'codfilial');
    }
    
    public function NotaFiscalProdutoBarraS()
    {
        return $this->hasMany(NotaFiscalProdutoBarra::class, 'codnotafiscal', 'codnotafiscal');
    }
    
}
