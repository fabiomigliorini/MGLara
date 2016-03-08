<?php

namespace MGLara\Models;


/**
 * Campos
 * @property  bigint                         $codportador                        NOT NULL DEFAULT nextval('tblportador_codportador_seq'::regclass)
 * @property  varchar(50)                    $portador                           
 * @property  bigint                         $codbanco                           
 * @property  bigint                         $agencia                            
 * @property  integer                        $agenciadigito                      
 * @property  bigint                         $conta                              
 * @property  integer                        $contadigito                        
 * @property  boolean                        $emiteboleto                        NOT NULL DEFAULT false
 * @property  bigint                         $codfilial                          
 * @property  numeric(20,0)                  $convenio                           
 * @property  varchar(100)                   $diretorioremessa                   
 * @property  varchar(100)                   $diretorioretorno                   
 * @property  integer                        $carteira                           
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 *
 * Chaves Estrangeiras
 * @property  Banco                          $Banco                         
 * @property  Filial                         $Filial                        
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 * @property  BoletoRetorno[]                $BoletoRetornoS
 * @property  Cobranca[]                     $CobrancaS
 * @property  LiquidacaoTitulo[]             $LiquidacaoTituloS
 * @property  MovimentoTitulo[]              $MovimentoTituloS
 * @property  Titulo[]                       $TituloS
 * @property  Usuario[]                      $UsuarioS
 */

class Portador extends MGModel
{
    protected $table = 'tblportador';
    protected $primaryKey = 'codportador';
    protected $fillable = [
        'portador',
        'codbanco',
        'agencia',
        'agenciadigito',
        'conta',
        'contadigito',
        'emiteboleto',
        'codfilial',
        'convenio',
        'diretorioremessa',
        'diretorioretorno',
        'carteira',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
    ];
    
    public function validate() {
        
        $this->_regrasValidacao = [
            'portador' => 'required|min:2', 
        ];
    
        $this->_mensagensErro = [
            'portador.required' => 'Preencha o campo Portador',
        ];
        
        return parent::validate();
    }
    

    // Chaves Estrangeiras
    public function Banco()
    {
        return $this->belongsTo(Banco::class, 'codbanco', 'codbanco');
    }

    public function Filial()
    {
        return $this->belongsTo(Filial::class, 'codfilial', 'codfilial');
    }

    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuario', 'codusuarioalteracao');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuario', 'codusuariocriacao');
    }


    // Tabelas Filhas
    public function BoletoRetornoS()
    {
        return $this->hasMany(BoletoRetorno::class, 'codportador', 'codportador');
    }

    public function CobrancaS()
    {
        return $this->hasMany(Cobranca::class, 'codportador', 'codportador');
    }

    public function LiquidacaoTituloS()
    {
        return $this->hasMany(LiquidacaoTitulo::class, 'codportador', 'codportador');
    }

    public function MovimentoTituloS()
    {
        return $this->hasMany(MovimentoTitulo::class, 'codportador', 'codportador');
    }

    public function TituloS()
    {
        return $this->hasMany(Titulo::class, 'codportador', 'codportador');
    }

    public function UsuarioS()
    {
        return $this->hasMany(Usuario::class, 'codportador', 'codportador');
    }
}
