<?php

namespace MGLara\Models;
use Validator;
use DB;

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
    public $codcargo_subgerente = 2;
    
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
        Validator::extend('uniqueGerente', function ($attribute, $value, $parameters)
        {
            $query = DB::table('tblmetafilialpessoa')
                    ->where('codmetafilial', $parameters[0])
                    ->where('codcargo', env('CODCARGO_SUBGERENTE'));
            
            $count = $query->count();
            if ($count > 1){
                return false;
            }
            return true;        
        });  
        
        $this->_regrasValidacao = [
            'codpessoa' => "uniqueMultiple:tblmetafilialpessoa,codmetafilialpessoa,$this->codmetafilialpessoa,codpessoa,codmetafilial,$this->codmetafilial",
            'codcargo'=>"uniqueGerente:$this->codmetafilial"
        ];
    
        $this->_mensagensErro = [
            'codpessoa.unique_multiple' => 'Uma pessoa foi selecionada mais de uma vez!',
            'codcargo.unique_gerente' => 'Mais de um Sub-Gerente foi selecionado para uma filial!',
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

