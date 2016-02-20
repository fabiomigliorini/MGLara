<?php

namespace MGLara\Models;


class GrupoUsuario extends MGModel
{
    protected $table = 'tblgrupousuario';
    protected $primaryKey = 'codgrupousuario';
    protected $fillable = [
      'grupousuario',
      'observacoes',  
    ];
    
    public function Usuario()
    {
        return $this->belongsToMany(Usuario::class,'tblgrupousuariousuario', 'codgrupousuario', 'codusuario');
    }   

    public function Permissao()
    {
        return $this->belongsToMany(Permissao::class, 'tblgrupousuariopermissao', 'codgrupousuario', 'codpermissao');
    }

    public function Filiais()
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
