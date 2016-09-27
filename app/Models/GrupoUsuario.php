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
        return $this->belongsTo(Usuario::class, 'codusuarioalteracao', 'codusuario');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuariocriacao', 'codusuario');
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
    
    public static function search($parametros)
    {
        $query = GrupoUsuario::query();
        
        if (!empty($parametros['codgrupousuario'])) {
            $query->where('codgrupousuario', $parametros['codgrupousuario']);
        }

        if (!empty($parametros['grupousuario'])) {
            $query->grupousuario($parametros['grupousuario']);
        }

        switch (isset($parametros['ativo'])?$parametros['ativo']:'9')
        {
            case 1: //Ativos
                $query->ativo();
                break;
            case 2: //Inativos
                $query->inativo();
                break;
            case 9; //Todos
            default:
        }

        return $query;
    }
    
    
    public function scopeGrupoUsuario($query, $grupousuario)
    {
        if (trim($grupousuario) === '')
            return;
        
        $grupousuario = explode(' ', removeAcentos($grupousuario));
        foreach ($grupousuario as $str) {
            $query->where('grupousuario', 'ILIKE', "%$str%");
        }
    }
    
    public function scopeInativo($query)
    {
        $query->whereNotNull('inativo');
    }

    public function scopeAtivo($query)
    {
        $query->whereNull('inativo');
    }
         
}
