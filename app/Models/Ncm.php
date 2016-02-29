<?php

namespace MGLara\Models;
use Illuminate\Support\Facades\Validator;

class Ncm extends MGModel
{
    protected $table = 'tblncm';
    protected $primaryKey = 'codncm';
    protected $fillable = [
      'ncm',
    ];
    
    public function ProdutoS()
    {
        return $this->hasMany(Produto::class, 'codncm', 'codncm');
    }  
    
    public function validate() {
        
        $this->_regrasValidacao = [
            'ncm' => 'required|min:2', 
        ];
    
        $this->_mensagensErro = [
            'ncm.required' => 'NCM não pode ser vazio',
        ];
        
        return parent::validate();
        
    }   
    
    
    public function scopeTributacao($query, $ncm)
    {
        if (trim($ncm) != "")
        {
            $query->where('ncm', "ILIKE", "%$ncm%");
        }
    }    
}
