<?php

namespace MGLara\Models;

class EstoqueMovimento extends MGModel
{
    protected $table = 'tblestoquemovimento';
    protected $primaryKey = 'codestoquemovimento';
    protected $fillable = [
      'codestoquemes',
      'codestoquemovimentotipo',

    ];
    
    public function EstoqueMes()
    {
        return $this->belongsTo(EstoqueMes::class, 'codestoquemes', 'codestoquemes');
    } 
    
    public function EstoqueSaldo()
    {
        return $this->hasMany(EstoqueSaldo::class, 'codestoquesaldo', 'codestoquesaldo');
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
