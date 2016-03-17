<?php

namespace MGLara\Models\Dominio;
use Carbon\Carbon;

class Registro 
{
    protected $_campos = [];
    protected $_quebraLinha = "\r\n";
    //
    public function geraLinha()
    {
        $linha = '';
        foreach ($this->_campos as $campo => $detalhes)
        {
            $valor = (isset($this->$campo))?$this->$campo:null;
            
            $tamanho = $detalhes['tamanho'];
            
            $padChar = ' ';
            $padType = STR_PAD_RIGHT;
            
            if ($valor !== null)
            {
                switch ($detalhes['tipo'])
                {
                    case 'decimal':
                        $valor = (int) ($valor * pow(10, $detalhes['casas']));
                    case 'numeric':
                        $padChar = '0';
                        $padType = STR_PAD_LEFT;
                        break;

                    case 'date':
                        if ($valor instanceof Carbon)
                            $valor = $valor->format($detalhes['formato']);
                        break;

                }
            }
            
            $valor = str_pad($valor, $tamanho, $padChar, $padType);
            if (strlen($valor) > $tamanho)
                $valor = substr ($valor, 0, $tamanho);

            $linha .= $valor;
        }
        return $linha . $this->_quebraLinha;
    }
}
