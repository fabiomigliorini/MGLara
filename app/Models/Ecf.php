<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codecf                             NOT NULL DEFAULT nextval('tblecf_codecf_seq'::regclass)
 * @property  varchar(50)                    $ecf                                NOT NULL
 * @property  varchar(100)                   $acbrmonitorcaminho                 NOT NULL
 * @property  varchar(100)                   $acbrmonitorcaminhorede             NOT NULL
 * @property  bigint                         $codusuario                         
 * @property  timestamp                      $bloqueado                          
 * @property  varchar(20)                    $serie                              
 * @property  bigint                         $codfilial                          
 * @property  varchar(2)                     $modelo                             
 * @property  integer                        $numero                             
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 *
 * Chaves Estrangeiras
 * @property  Filial                         $Filial                        
 * @property  Usuario                        $Usuario                       
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 * @property  CupomFiscal[]                  $CupomFiscalS
 * @property  EcfReducaoz[]                  $EcfReducaozS
 * @property  Usuario[]                      $UsuarioS
 */

class Ecf extends MGModel
{
    protected $table = 'tblecf';
    protected $primaryKey = 'codecf';
    protected $fillable = [
        'ecf',
        'acbrmonitorcaminho',
        'acbrmonitorcaminhorede',
        'codusuario',
        'bloqueado',
        'serie',
        'codfilial',
        'modelo',
        'numero',
    ];
    protected $dates = [
        'bloqueado',
        'alteracao',
        'criacao',
    ];

    public function validate() {
        
        $this->_regrasValidacao = [
            'ecf' => 'required|min:2', 
        ];
    
        $this->_mensagensErro = [
            'ecf.required' => 'Preencha o campo ecf',
        ];
        
        return parent::validate();
    }

    // Chaves Estrangeiras
    public function Filial()
    {
        return $this->belongsTo(Filial::class, 'codfilial', 'codfilial');
    }

    public function Usuario()
    {
        return $this->belongsTo(Usuario::class, 'codusuario', 'codusuario');
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
    public function CupomFiscalS()
    {
        return $this->hasMany(CupomFiscal::class, 'codecf', 'codecf');
    }

    public function EcfreducaozS()
    {
        return $this->hasMany(EcfReducaoz::class, 'codecf', 'codecf');
    }

    public function UsuarioS()
    {
        return $this->hasMany(Usuario::class, 'codecf', 'codecf');
    }

}
