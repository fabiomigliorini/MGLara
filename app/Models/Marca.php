<?php

namespace MGLara\Models;

use Illuminate\Support\Facades\Validator;

class Marca extends MGModel
{
    protected $table = 'tblmarca';
    protected $primaryKey = 'codmarca';
    protected $fillable = [
      'marca',
      'site',  
      'descricaosite',
    ];
    
    
    public function ProdutoS()
    {
        return $this->hasMany(Produto::class, 'codmarca', 'codmarca')->orderBy('produto');
    }      
    
    public function validate() {
        
        $this->_regrasValidacao = [
            'marca' => 'required|min:10', 
            'descricaosite' => 'required|min:50', 
        ];
    
        $this->_mensagensErro = [
            'marca.required' => 'Marca nao pode ser vazio bla bla bla bla bla!',
        ];
        
        return parent::validate();
        
    }
    
    # Buscas #
    public static function filterAndPaginate($marca)
    {
        return Marca::marca($marca)
            ->orderBy('marca', 'ASC')
            ->paginate(20);
    }
    
    public function scopeMarca($query, $marca)
    {
        if (trim($marca) != "")
        {
            $query->where('marca', "ILIKE", "%$marca%");
        }
    }

            
}
