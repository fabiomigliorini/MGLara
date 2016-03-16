<?php

namespace MGLara\Models\Dominio;

class Registro 
{
    protected $_campos = [];
    //
    public function geraLinha()
    {
        $linha = '';
        foreach ($this->_campos as $campo => $detalhes)
        {
            $linha .= $this->$campo;
        }
        return $linha;
    }
}
