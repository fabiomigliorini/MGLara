<?php

namespace MGLara\Models;

class Filial extends MGModel
{
    protected $table = 'tblfilial';
    protected $primaryKey = 'codfilial';
    protected $fillable = [
      'filial',
    ];
    
    public function EstoqueLocalS()
    {
        return $this->hasMany(EstoqueLocal::class, 'codfilial', 'codfilial');
    }       
    
    public function NotaFiscalS()
    {
        return $this->hasMany(NotaFiscal::class, 'codfilial', 'codfilial');
    }       
    
    public function NegocioS()
    {
        return $this->hasMany(Negocio::class, 'codfilial', 'codfilial');
    }       
    
    
    
    
    public function Usuario()
    {
        return $this->hasMany(Usuario::class, 'codfilial', 'codfilial');
    }
    

    public function validate() {
        
        $this->_regrasValidacao = [
            'filial' => 'required|min:5', 
        ];
    
        $this->_mensagensErro = [
            'filial.required' => 'Preencha o campo filial',
        ];
        
        return parent::validate();
    }
    
    public function scopeFilial($query, $filial)
    {
        if (trim($filial) != "")
        {
            $query->where('filial', "ILIKE", "%$filial%");
        }
    } 
}
