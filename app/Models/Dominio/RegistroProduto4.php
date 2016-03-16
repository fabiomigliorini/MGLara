<?php

namespace MGLara\Models\Dominio;

use MGLara\Models\Dominio\Registro;

class RegistroProduto4 extends Registro
{
    function __construct()
    {
        $this->_campos = [
            'identificador' => [
                'tamanho' => 1,
                'tipo' => 'char',
            ]
        ];
    }
}
