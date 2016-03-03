<?php

namespace MGLara\Models;
use Illuminate\Support\Facades\Validator;

class GrupoProduto extends MGModel
{
    protected $table = 'tblgrupoproduto';
    protected $primaryKey = 'codgrupoproduto';
    protected $fillable = [
      'grupoproduto',
    ];
    
    public function SubGrupoProdutoS()
    {
        return $this->hasMany(SubGrupoProduto::class, 'codgrupoproduto', 'codgrupoproduto')->orderBy('subgrupoproduto');
    }    
    
    public function validate() {

        $this->_regrasValidacao = [
            'grupoproduto' => 'required|min:2', 
        ];    
        $this->_mensagensErro = [
            'grupoproduto.required' => 'Grupo de produto nao pode ser vazio.',
        ];

        return parent::validate();
    }    
    
    
    public function scopeGrupoproduto($query, $grupoproduto)
    {
        if (trim($grupoproduto) != "")
        {
            $query->where('grupoproduto', "ILIKE", "%$grupoproduto%");
        }
    }    
}
