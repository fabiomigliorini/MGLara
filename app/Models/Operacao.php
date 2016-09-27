<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codoperacao                        NOT NULL DEFAULT nextval('tbloperacao_codoperacao_seq'::regclass)
 * @property  varchar(50)                    $operacao                           
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 *
 * Chaves Estrangeiras
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 * @property  Negocio[]                      $NegocioS
 * @property  NfeTerceiro[]                  $NfeTerceiroS
 * @property  NotaFiscal[]                   $NotaFiscalS
 * @property  Usuario[]                      $UsuarioS
 */

class Operacao extends MGModel
{
    
    const ENTRADA = 1;
    const SAIDA = 2;

    protected $table = 'tbloperacao';
    protected $primaryKey = 'codoperacao';
    protected $fillable = [
        'operacao',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
    ];
    
    public function validate() {
        
        $this->_regrasValidacao = [
            'operacao' => 'required|min:3', 
        ];
    
        $this->_mensagensErro = [
            'operacap.required' => 'Preencha o campo operacao',
        ];
        
        return parent::validate();
    }
    

    // Chaves Estrangeiras
    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuarioalteracao', 'codusuario');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuariocriacao', 'codusuario');
    }


    // Tabelas Filhas
    public function NegocioS()
    {
        return $this->hasMany(Negocio::class, 'codoperacao', 'codoperacao');
    }

    public function NfeTerceiroS()
    {
        return $this->hasMany(NfeTerceiro::class, 'codoperacao', 'codoperacao');
    }

    public function NotaFiscalS()
    {
        return $this->hasMany(NotaFiscal::class, 'codoperacao', 'codoperacao');
    }

    public function UsuarioS()
    {
        return $this->hasMany(Usuario::class, 'codoperacao', 'codoperacao');
    }    
    /*
    public function NaturezaOperacaoS()
    {
        return $this->hasMany(NaturezaOperacao::class, 'codoperacao', 'codoperacao');
    }
    
    public function Usuario()
    {
        return $this->hasMany('Usuario::class', 'codoperacao', 'codoperacao');
    }
    */
     



}
