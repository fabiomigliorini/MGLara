<?php

namespace MGLara\Models;
use Illuminate\Support\Facades\Validator;

class Tributacao extends MGModel
{
    protected $table = 'tbltributacao';
    protected $primaryKey = 'codtributacao';
    protected $fillable = [
      'tributacao',
    ];
    
    public function ProdutoS()
    {
        return $this->hasMany(Produto::class, 'codtributacao', 'codtributacao');
    }    
    
    public function validate() {
        

        $this->_regrasValidacao = [
            'tributacao' => 'required|min:2', 
        ];    
        $this->_mensagensErro = [
            'tributacao.required' => 'Grupo de produto nao pode ser vazio.',
        ];
        
        parent::validate();
    }    
    
    
    public function scopeTributacao($query, $tributacao)
    {
        if (trim($tributacao) != "")
        {
            $query->where('tributacao', "ILIKE", "%$tributacao%");
        }
    }    
}
