<?php

namespace MGLara\Models;
use Illuminate\Support\Facades\Validator;

class SubGrupoProduto extends MGModel
{
    protected $table = 'tblsubgrupoproduto';
    protected $primaryKey = 'codsubgrupoproduto';
    protected $fillable = [
      'subgrupoproduto',
    ]; 
    
    public function GrupoProduto()
    {
        return $this->belongsTo(GrupoProduto::class, 'codgrupoproduto', 'codgrupoproduto');
    }    
    
    public function ProdutoS()
    {
        return $this->hasMany(Produto::class, 'codsubgrupoproduto', 'codsubgrupoproduto')->orderBy('produto');;
    }    
    
    public function validate() {

        $this->_regrasValidacao = [
            'subgrupoproduto' => 'required|min:2', 
        ];    
        $this->_mensagensErro = [
            'subgrupoproduto.required' => 'Sub grupo de produto nao pode ser vazio.',
        ];
        return parent::validate();
    }    
    
    public function scopeSubgrupoproduto($query, $subgrupoproduto, $codgrupoproduto)
    {
        $query->where('codgrupoproduto', "=", "$codgrupoproduto");
        if (trim($subgrupoproduto) != "")
        {
            $query->where('subgrupoproduto', "ILIKE", "%$subgrupoproduto%");
        }
    }     
    
}
