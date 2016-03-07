<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codgrupousuario                    NOT NULL DEFAULT nextval('tblgrupousuario_codgrupousuario_seq'::regclass)
 * @property  varchar(50)                    $grupousuario                       NOT NULL
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
 * @property  Permissao[]                    $PermissaoS
 * @property  Usuario[]                      $UsuarioS
 * @property  Filial[]                       $FilialS
 */

class GrupoUsuario extends MGModel
{
    protected $table = 'tblgrupousuario';
    protected $primaryKey = 'codgrupousuario';
    protected $fillable = [
        'grupousuario',
        'observacoes',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
    ];


    // Chaves Estrangeiras
    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuario', 'codusuarioalteracao');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuario', 'codusuariocriacao');
    }
    
    // // Tabelas Filhas (sem gerador)
    public function UsuarioS()
    {
        return $this->belongsToMany(Usuario::class,'tblgrupousuariousuario', 'codgrupousuario', 'codusuario');
    }   

    public function PermissaoS()
    {
        return $this->belongsToMany(Permissao::class, 'tblgrupousuariopermissao', 'codgrupousuario', 'codpermissao');
    }

    public function FilialS()
    {
        return $this->belongsToMany(Filial::class, 'tblgrupousuariousuario', 'codgrupousuario', 'codfilial');
    }

    public function validate() {
    	
    	if ($this->codgrupousuario)
    		$unique_grupousuario = "unique:tblgrupousuario,grupousuario,$this->codgrupousuario,codgrupousuario|required|min:5";
    	else 
    		$unique_grupousuario = "unique:tblgrupousuario,grupousuario|required|min:5";
        
        $this->_regrasValidacao = [
            'grupousuario' => $unique_grupousuario,
        ];
    
        $this->_mensagensErro = [
            'grupousuario.unique' => 'Esse nome de grupo já esta utilizado',
        ];
        
        return parent::validate();
    }
    
    
    # Buscas #
    public static function filterAndPaginate($codgrupousuario, $grupousuario)
    {
        return GrupoUsuario::codgrupousuario($codgrupousuario)
            ->grupousuario($grupousuario)
            ->orderBy('grupousuario', 'DESC')
            ->paginate(20);
    }
    
    public function scopeCodgrupousuario($query, $codgrupousuario)
    {
        if ($codgrupousuario)
        {
            $query->where('codgrupousuario', "$codgrupousuario");
        }
    }     
    
    public function scopeGrupoUsuario($query, $grupousuario)
    {
        if (trim($grupousuario) != "")
        {
            $query->where('grupousuario', "ILIKE", "%$grupousuario%");
        }
    }

            
}
