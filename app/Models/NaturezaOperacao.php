<?php

namespace MGLara\Models;

use Illuminate\Database\Eloquent\Model;

class NaturezaOperacao extends Model
{
    protected $table = 'tblnaturezaoperacao';
    protected $primaryKey = 'codnaturezaoperacao';
    
    public function EstoqueMovimentoTipo()
    {
        return $this->belongsTo(EstoqueMovimentoTipo::class, 'codestoquemovimentotipo', 'codestoquemovimentotipo');
    }
    
    public function Operacao()
    {
        return $this->belongsTo(Operacao::class, 'codoperacao', 'codoperacao');
    }
    
    public function NegocioS()
    {
        return $this->hasMany(Negocio::class, 'codnaturezaoperacao', 'codnaturezaoperacao');
    }       
    
    public function NotaFiscalS()
    {
        return $this->hasMany(NotaFiscal::class, 'codnaturezaoperacao', 'codnaturezaoperacao');
    }       
    
    public function NaturezaOperacaoDevolucaoS()
    {
        return $this->hasMany(NaturezaOperacao::class, 'codnaturezaoperacaodevolucao', 'codnaturezaoperacao');
    }
    
    public function NaturezaOperacaoDevolucao()
    {
        return $this->belongsTo(NaturezaOperacao::class, 'codnaturezaoperacao', 'codnaturezaoperacaodevolucao');
    }
    
    
}
