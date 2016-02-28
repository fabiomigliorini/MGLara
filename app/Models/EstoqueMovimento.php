<?php

namespace MGLara\Models;

class EstoqueMovimento extends MGModel
{
    protected $table = 'tblestoquemovimento';
    protected $primaryKey = 'codestoquemovimento';
    protected $fillable = [
        'codestoquemes',
        'codestoquemovimentotipo',
        'data',
        'entradaquantidade',
        'entradavalor',
        'saidaquantidade',
        'saidavalor',
    ];
    
    public function EstoqueMes()
    {
        return $this->belongsTo(EstoqueMes::class, 'codestoquemes', 'codestoquemes');
    } 
    
    public function EstoqueMovimentoTipo()
    {
        return $this->belongsTo(EstoqueMovimentoTipo::class, 'codestoquemovimentotipo', 'codestoquemovimentotipo');
    } 

    public function NotaFiscalProdutoBarra()
    {
        return $this->belongsTo(NotaFiscalProdutoBarra::class, 'codnotafiscalprodutobarra', 'codnotafiscalprodutobarra');
    } 

    public function NegocioProdutoBarra()
    {
        return $this->belongsTo(NegocioProdutoBarra::class, 'codnegocioprodutobarra', 'codnegocioprodutobarra');
    } 

    public function validate() {
        
        $this->_regrasValidacao = [
            //'field' => 'required|min:2', 
        ];
    
        $this->_mensagensErro = [
            //'field.required' => 'Preencha o campo',
        ];
        
        return parent::validate();
    }
   
}
