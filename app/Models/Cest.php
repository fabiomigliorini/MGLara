<?php

namespace MGLara\Models;
use Illuminate\Support\Facades\Validator;

class Cest extends MGModel
{
    protected $table = 'tblcest';
    protected $primaryKey = 'codcest';
    protected $fillable = [
      'cest',
    ];
    
    public function ProdutoS()
    {
        return $this->hasMany(Produto::class, 'codcest', 'codcest');
    }  
    

    
  
}