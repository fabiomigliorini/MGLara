<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codregulamentoicmsstmt             NOT NULL DEFAULT nextval('tblregulamentoicmsstmt_codregulamentoicmsstmt_seq'::regclass)
 * @property  varchar(10)                    $subitem                            NOT NULL
 * @property  varchar(600)                   $descricao                          NOT NULL
 * @property  varchar(8)                     $ncm                                NOT NULL
 * @property  varchar(100)                   $ncmexceto                          
 * @property  numeric(4,2)                   $icmsstsul                          
 * @property  numeric(14,2)                  $icmsstnorte                        
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 * @property  bigint                         $codncm                             
 *
 * Chaves Estrangeiras
 * @property  Ncm                            $Ncm                           
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 */


class RegulamentoIcmsStMt extends MGModel
{
    protected $table = 'tblregulamentoicmsstmt';
    protected $primaryKey = 'codregulamentoicmsstmt';
    protected $fillable = [
        'subitem',
        'descricao',
        'ncm',
        'ncmexceto',
        'icmsstsul',
        'icmsstnorte',
        'codncm',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
    ];    
    
    public function validate() {
        
        $this->_regrasValidacao = [
            'codigo' => 'required|min:2', 
        ];
    
        $this->_mensagensErro = [
            'codigo.required' => '...',
        ];
        
        return parent::validate();
    } 


    // Chaves Estrangeiras
    public function Ncm()
    {
        return $this->belongsTo(Ncm::class, 'codncm', 'codncm');
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
