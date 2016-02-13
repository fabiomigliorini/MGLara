<?php

namespace MGLara\Models;

class Pessoa extends MGModel
{
    protected $table = 'tblpessoa';
    protected $primaryKey = 'codpessoa';
    protected $fillable = [
      'pessoa',
    ];
    
    public function Usuario()
    {
        return $this->hasMany('Usuario::class', 'codpessoa', 'codpessoa');
    }
     

    public function validate() {
        
        $this->_regrasValidacao = [
            'pessoa' => 'required|min:3', 
        ];
    
        $this->_mensagensErro = [
            'pessoa.required' => 'Preencha o campo pessoa',
        ];
        
        return parent::validate();
    }
    
    public function scopePessoa($query, $pessoa)
    {
        if (trim($pessoa) != "")
        {
            $query->where('pessoa', "ILIKE", "%$pessoa%");
        }
    } 
}
