<?php

namespace MGLara\Models\Dominio;
use MGLara\Models\Dominio\Registro;

/**
 * Classe base para geração de arquivos textos para integracao 
 * com o Dominio Sistemas
 * 
 * @property string     $_arquivo   Nome do arquivo que será gerado
 * @property string     $_diretorio Diretório onde o arquivo será gerado
 * @property array      $_erros     Erros gerados no processo 
 * @property Registro[] $_registros Registros gerados
 */
class Arquivo 
{
    protected $_arquivo;
    protected $_diretorio = '/media/publico/Arquivos/Dominio';
    protected $_erros = [];
    protected $_registros = [];
    
    /**
     * Processa e gera os registros 
     * @return boolean
     */
    function processa()
    {
        return true;
    }
    
    /**
     * Metodo que grava o arquivo texto
     * @return boolean
     */
    function grava()
    {
        $conteudo = '';
        
        foreach ($this->_registros as $reg)
        {
            $conteudo .= $reg->geraLinha();
        }
        
        $arquivo = $this->_diretorio . DIRECTORY_SEPARATOR . $this->_arquivo;
        
        file_put_contents($arquivo, $conteudo);
        
        return true;
    }
    
    
}
