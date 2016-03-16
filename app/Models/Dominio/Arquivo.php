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
        $conteudo = '';
        foreach ($this->_registros as $reg)
        {
            $conteudo .= $reg->geraLinha();
        }
        echo '<pre>';
        echo $conteudo;
        echo '</pre>';
        dd($conteudo);
        return true;
    }
    
    
}
