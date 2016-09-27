<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codpermissao                       NOT NULL
 * @property  varchar(100)                   $permissao                          NOT NULL
 * @property  varchar(600)                   $observacoes                        
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
 * @property  GrupoUsuario[]        $GrupoUsuario
 */

class Permissao extends MGModel
{
    protected $table = 'tblpermissao';
    protected $primaryKey = 'codpermissao';
    protected $fillable = [
        'codpermissao',
        'permissao',
        'observacoes',  
    ];
    
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
    public function GrupoUsuario()
    {
        return $this->belongsToMany(GrupoUsuario::class, 'tblgrupousuariopermissao', 'codpermissao', 'codgrupousuario');
    }   

    public function validate() {
        
        $unique_codpermissao = "unique:tblpermissao,codpermissao|required|min:4";
        $unique_permissao = "unique:tblpermissao,permissao|required|min:4"; 	
        
    	if ($this->exists) {
            $unique_codpermissao = "unique:tblpermissao,codpermissao,$this->codpermissao,codpermissao|required|min:4";
            $unique_permissao = "unique:tblpermissao,permissao,$this->codpermissao,codpermissao|required|min:4";
        }
   
        $this->_regrasValidacao = [
        		
            'codpermissao' => $unique_codpermissao,
            'permissao' => $unique_permissao,
        ];
    
        $this->_mensagensErro = [
            'permissao.min' => 'Permissão deve ter no mínimo 4 caracteres',
        ];
        
        return parent::validate();
    }
    
    public static function search($parametros)
    {
        $query = Permissao::query();
        
        if (!empty($parametros['codpermissao'])) {
            $query->where('codpermissao', $parametros['codpermissao']);
        }

        if (!empty($parametros['permissao'])) {
            $query->permissao($parametros['permissao']);
        }

        return $query;
    }
    
    public function scopePermissao($query, $permissao)
    {
        if (trim($permissao) === '')
            return;
        
        $permissao = explode(' ', removeAcentos($permissao));
        foreach ($permissao as $str) {
            $query->where('permissao', 'ILIKE', "%$str%");
        }
    }
        
}
