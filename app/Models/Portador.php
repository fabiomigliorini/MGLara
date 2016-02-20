<?php

namespace MGLara\Models;

class Portador extends MGModel
{
    protected $table = 'tblportador';
    protected $primaryKey = 'codportador';
    protected $fillable = [
      'portador',
    ];
    
    public function Usuario()
    {
        return $this->hasMany(Usuario::class, 'codportador', 'codportador');
    }
     

    public function validate() {
        
        $this->_regrasValidacao = [
            'portador' => 'required|min:2', 
        ];
    
        $this->_mensagensErro = [
            'portador.required' => 'Preencha o campo Portador',
        ];
        
        return parent::validate();
    }

}
