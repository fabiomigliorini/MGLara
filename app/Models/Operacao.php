<?php

namespace MGLara\Models;

class Operacao extends MGModel
{
    protected $table = 'tbloperacao';
    protected $primaryKey = 'codoperacao';
    protected $fillable = [
      'operacao',
    ];
    
    public function Usuario()
    {
        return $this->hasMany('Usuario::class', 'codoperacao', 'codoperacao');
    }
     

    public function validate() {
        
        $this->_regrasValidacao = [
            'operacao' => 'required|min:3', 
        ];
    
        $this->_mensagensErro = [
            'operacap.required' => 'Preencha o campo operacao',
        ];
        
        return parent::validate();
    }

}
