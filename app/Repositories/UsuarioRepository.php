<?php

namespace MGLara\Repositories;
use MGLara\Models\Usuario as Usuario;

class UsuarioRepository /*extends MGRepository*/ {

/*    
    private $model;

    public function __construct(Usuario $model)
    {
        $this->model = $model;
    }
*/    
    public function scopeUsuario($query, $value)
    {
        if (trim($value) != "")
        {
            $query->where('usuario', "ILIKE", "%$value%");
        }
    }    
}
