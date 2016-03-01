<?php

namespace MGLara\Models;

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

    public function Ncm()
    {
        return $this->belongsTo(Ncm::class, 'codncm', 'codncm');
    }  
 
}