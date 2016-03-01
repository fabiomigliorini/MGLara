<?php

namespace MGLara\Models;

class Ibptax extends MGModel
{
    protected $table = 'tblibptax';
    protected $primaryKey = 'codibptax';
    protected $fillable = [
      'codigo',
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
