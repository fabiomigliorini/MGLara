<?php

namespace MGLara\Models\Dominio;
use Carbon\Carbon;

class Registro 
{
    protected $_campos = [];
    //
    public function geraLinha()
    {
        $linha = '';
        foreach ($this->_campos as $campo => $detalhes)
        {
            $valor = (isset($this->$campo))?$this->$campo:'';
            switch ($detalhes['tipo'])
            {
                case 'numeric':
                    $valorFormatado = str_pad($valor, $detalhes['tamanho'], '0', STR_PAD_LEFT);
                    break;

                case 'decimal':
                    $valor = (int) ($valor * pow(10, $detalhes['casas']));
                    $valorFormatado = str_pad($valor, $detalhes['tamanho'], '0', STR_PAD_LEFT);
                    break;

                case 'date':
                    if ($valor instanceof Carbon)
                        $valorFormatado = str_pad($valor->format($detalhes['formato']), $detalhes['tamanho'], ' ', STR_PAD_RIGHT);
                    else
                        $valorFormatado = str_pad($valor, $detalhes['tamanho'], ' ', STR_PAD_RIGHT);
                    break;
                
                case 'char':
                default:
                    $valorFormatado = str_pad($valor, $detalhes['tamanho'], ' ', STR_PAD_RIGHT);
                    break;

            }
            
            if (strlen($valorFormatado) > $detalhes['tamanho'])
                $valorFormatado = substr ($valorFormatado, 0, $detalhes['tamanho']);

            $linha .= $valorFormatado;
        }
        return $linha . "\n";
    }
}
