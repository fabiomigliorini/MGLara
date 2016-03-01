<?php

namespace MGLara\Models;

class RegulamentoIcmsStMt extends MGModel
{
    protected $table = 'tblregulamentoicmsstmt';
    protected $primaryKey = 'codregulamentoicmsstmt';
    protected $fillable = [
      'descricao',
    ];
    
    public function Ncm()
    {
        return $this->belongsTo(Ncm::class, 'codncm', 'codncm');
    }  
    
    public function validate() {
        
        $this->_regrasValidacao = [
            'codigo' => 'required|min:2', 
        ];
    
        $this->_mensagensErro = [
            'codigo.required' => '...',
        ];
        
        return parent::validate();
        
    }   
    
}
