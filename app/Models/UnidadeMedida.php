<?php

namespace MGLara\Models;

use Illuminate\Database\Eloquent\Model;

class UnidadeMedida extends MGModel
{
    protected $table = 'tblunidademedida';
    protected $primaryKey = 'codunidademedida';
    
    public function ProdutoS()
    {
        return $this->hasMany(Produto::class, 'codunidademedida', 'codunidademedida');
    }
    
    public function ProdutoEmbalagemS()
    {
        return $this->hasMany(ProdutoEmbalagem::class, 'codunidademedida', 'codunidademedida');
    }
    
}
