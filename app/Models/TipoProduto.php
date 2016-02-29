<?php

namespace MGLara\Models;
use Illuminate\Support\Facades\Validator;

class TipoProduto extends MGModel
{
    protected $table = 'tbltipoproduto';
    protected $primaryKey = 'codtipoproduto';
    protected $fillable = [
      'tipoproduto',
    ];
    
    public function ProdutoS()
    {
        return $this->hasMany(Produto::class, 'codtipoproduto', 'codtipoproduto');
    }    
    
    
    public function validate() {
        $this->_regrasValidacao = [
            'tipoproduto' => 'required|min:2', 
        ];    
        $this->_mensagensErro = [
            'tipoproduto.required' => 'Tipo não pode ser vazio.',
        ];
        return parent::validate();
    }    
    
    
    public function scopeTributacao($query, $tipoproduto)
    {
        if (trim($tipoproduto) != "")
        {
            $query->where('tipoproduto', "ILIKE", "%$tipoproduto%");
        }
    }    
}
