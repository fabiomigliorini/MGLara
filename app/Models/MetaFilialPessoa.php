<?php

namespace MGLara\Models;
use Validator;

/**
 * Campos
 * @property  bigint                         $codmetafilialpessoa                NOT NULL DEFAULT nextval('tblmetafilialpessoa_codmetafilialpessoa_seq'::regclass)
 * @property  bigint                         $codmetafilial                      NOT NULL
 * @property  bigint                         $codpessoa                          NOT NULL
 * @property  bigint                         $codcargo                           NOT NULL
 * @property  timestamp                      $criacao                            
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  bigint                         $codusuariocriacao                  
 *
 * Chaves Estrangeiras
 * @property  Pessoa                         $Pessoa                        
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 * @property  Cargo                          $Cargo                         
 * @property  MetaFilial                     $MetaFilial                    
 *
 * Tabelas Filhas
 */

class MetaFilialPessoa extends MGModel
{
    protected $table = 'tblmetafilialpessoa';
    protected $primaryKey = 'codmetafilialpessoa';
    protected $fillable = [
        'codmetafilial',
        'codpessoa',
        'codcargo',
    ];
    protected $dates = [
        'criacao',
        'alteracao',
    ];
    
    

    public function validate() {
        
        $this->_regrasValidacao = [
            'codpessoa' => "uniqueMultiple:tblmetafilialpessoa,codmetafilialpessoa,$this->codmetafilialpessoa,codpessoa,codmetafilial,$this->codmetafilial",
            'codcargo' => "unique:tblmetafilialpessoa,codcargo,$this->codmetafilialpessoa,codmetafilialpessoa,codmetafilial,$this->codmetafilial,codcargo,".env('CODCARGO_SUBGERENTE').""
            
        ];
    
        $this->_mensagensErro = [
            'codpessoa.unique_multiple' => 'Uma pessoa foi selecionada mais de uma vez!',
            'codcargo.unique' => 'Mais de um Sub Gerente foi selecionado para uma filial!',
        ];
        
        return parent::validate();
        
    }    

    // Chaves Estrangeiras
    public function Pessoa()
    {
        return $this->belongsTo(Pessoa::class, 'codpessoa', 'codpessoa');
    }

    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuario', 'codusuarioalteracao');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuario', 'codusuariocriacao');
    }

    public function Cargo()
    {
        return $this->belongsTo(Cargo::class, 'codcargo', 'codcargo');
    }

    public function MetaFilial()
    {
        return $this->belongsTo(MetaFilial::class, 'codmetafilial', 'codmetafilial');
    }


    // Tabelas Filhas

}

