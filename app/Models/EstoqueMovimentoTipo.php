<?php

namespace MGLara\Models;

class EstoqueMovimentoTipo extends MGModel
{
    protected $table = 'tblestoquemovimentotipo';
    protected $primaryKey = 'codestoquemovimentotipo';
    protected $fillable = [
      'sigla',
      'descricao',
    ];
    
    public function EstoqueMovimentoS()
    {
        return $this->hasMany(EstoqueMovimento::class, 'codestoquemovimento', 'codestoquemovimento');
    }    
         
    public function NaturezaOperacaoS()
    {
        return $this->hasMany(NaturezaOperacao::class, 'codnaturezaoperacao', 'codnaturezaoperacao');
    }    
    
}
