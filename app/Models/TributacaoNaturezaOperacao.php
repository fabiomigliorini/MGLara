<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codtributacaonaturezaoperacao      NOT NULL DEFAULT nextval('tbltributacaonaturezaoperacao_codtributacaonaturezaoperacao_seq'::regclass)
 * @property  bigint                         $codtributacao                      NOT NULL
 * @property  bigint                         $codnaturezaoperacao                NOT NULL
 * @property  bigint                         $codcfop                            NOT NULL
 * @property  numeric(14,2)                  $icmsbase                           
 * @property  numeric(14,2)                  $icmspercentual                     
 * @property  bigint                         $codestado                          
 * @property  varchar(4)                     $csosn                              NOT NULL
 * @property  bigint                         $codtipoproduto                     NOT NULL
 * @property  integer                        $acumuladordominiovista             
 * @property  integer                        $acumuladordominioprazo             
 * @property  varchar(512)                   $historicodominio                   
 * @property  boolean                        $movimentacaofisica                 NOT NULL DEFAULT true
 * @property  boolean                        $movimentacaocontabil               NOT NULL DEFAULT true
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 * @property  varchar(10)                    $ncm                                
 * @property  numeric(3,0)                   $icmscst                            
 * @property  numeric(5,2)                   $icmslpbase                         
 * @property  numeric(4,2)                   $icmslppercentual                   
 * @property  numeric(3,0)                   $ipicst                             
 * @property  numeric(3,0)                   $piscst                             
 * @property  numeric(4,2)                   $pispercentual                      
 * @property  numeric(3,0)                   $cofinscst                          
 * @property  numeric(4,2)                   $cofinspercentual                   
 * @property  numeric(4,2)                   $csllpercentual                     
 * @property  numeric(4,2)                   $irpjpercentual                     
 *
 * Chaves Estrangeiras
 * @property  Cfop                           $Cfop                          
 * @property  Estado                         $Estado                        
 * @property  NaturezaOperacao               $NaturezaOperacao              
 * @property  TipoProduto                    $TipoProduto                   
 * @property  Tributacao                     $Tributacao                    
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 */

class TributacaoNaturezaOperacao extends MGModel
{
    protected $table = 'tbltributacaonaturezaoperacao';
    protected $primaryKey = 'codtributacaonaturezaoperacao';
    protected $fillable = [
        'codtributacao',
        'codnaturezaoperacao',
        'codcfop',
        'icmsbase',
        'icmspercentual',
        'codestado',
        'csosn',
        'codtipoproduto',
        'acumuladordominiovista',
        'acumuladordominioprazo',
        'historicodominio',
        'movimentacaofisica',
        'movimentacaocontabil',
        'ncm',
        'icmscst',
        'icmslpbase',
        'icmslppercentual',
        'ipicst',
        'piscst',
        'pispercentual',
        'cofinscst',
        'cofinspercentual',
        'csllpercentual',
        'irpjpercentual',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
    ];


    // Chaves Estrangeiras
    public function Cfop()
    {
        return $this->belongsTo(Cfop::class, 'codcfop', 'codcfop');
    }

    public function Estado()
    {
        return $this->belongsTo(Estado::class, 'codestado', 'codestado');
    }

    public function NaturezaOperacao()
    {
        return $this->belongsTo(NaturezaOperacao::class, 'codnaturezaoperacao', 'codnaturezaoperacao');
    }

    public function TipoProduto()
    {
        return $this->belongsTo(Tipoproduto::class, 'codtipoproduto', 'codtipoproduto');
    }

    public function Tributacao()
    {
        return $this->belongsTo(Tributacao::class, 'codtributacao', 'codtributacao');
    }

    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuarioalteracao', 'codusuario');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuariocriacao', 'codusuario');
    }


    // Tabelas Filhas

}
