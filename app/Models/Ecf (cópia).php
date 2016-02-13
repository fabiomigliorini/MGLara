<?php

namespace MGLara\Models;

class Ecf extends MGModel
{
    protected $table = 'tblecf';
    protected $primaryKey = 'codecf';
    protected $fillable = [
      'ecf',
    ];
    
    public function Usuario()
    {
        return $this->hasMany('Usuario::class', 'codecf', 'codecf');
    }
     

    public function validate() {
        
        $this->_regrasValidacao = [
            'ecf' => 'required|min:2', 
        ];
    
        $this->_mensagensErro = [
            'ecf.required' => 'Preencha o campo ecf',
        ];
        
        return parent::validate();
    }

}
