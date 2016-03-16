<?php

namespace MGLara\Models\Dominio;

class Arquivo 
{
    protected $_arquivo;
    protected $_diretorio = '/media/publico/Arquivos/Dominio';
    protected $_erros = [];
    protected $_registros = [];
    
    function processa()
    {
        return true;
    }
    
    function grava()
    {
        dd($this->_registros);
        return true;
    }
    
    
}
