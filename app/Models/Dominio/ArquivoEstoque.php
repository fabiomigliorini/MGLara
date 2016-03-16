<?php

namespace MGLara\Models\Dominio;

use MGLara\Models\Dominio\Arquivo;
use MGLara\Models\Filial;
use Carbon\Carbon;

/**
 * @property Carbon $mes 
 * @property Filial $Filial
 */
class ArquivoEstoque extends Arquivo
{
    var $_mes;
    var $_Filial;
    
    function __construct(Carbon $mes, Filial $Filial)
    {
        $this->_mes = $mes;
        $this->_Filial = $Filial;
        
    }
    
    function processa()
    {
        
    }
}
