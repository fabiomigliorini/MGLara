<?php

namespace MGLara\Models;

class Permissao extends MGModel
{
    protected $table = 'tblpermissao';
    protected $primaryKey = 'codpermissao';
    protected $fillable = [
        'codpermissao',
        'permissao',
        'observacoes',  
    ];
    
    public function GrupoUsuario()
    {
        return $this->belongsToMany(GrupoUsuario::class, 'tblgrupousuariopermissao', 'codpermissao', 'codgrupousuario');
    }   

    public function validate() {
        
        $unique_codpermissao = "unique:tblpermissao,codpermissao|required|min:5";
        $unique_permissao = "unique:tblpermissao,permissao|required|min:5"; 	
        
    	if ($this->exists) {
            $unique_codpermissao = "unique:tblpermissao,codpermissao,$this->codpermissao,codpermissao|required|min:5";
            $unique_permissao = "unique:tblpermissao,permissao,$this->codpermissao,codpermissao|required|min:5";
        }
   
        $this->_regrasValidacao = [
        		
            'codpermissao' => $unique_codpermissao,
            'permissao' => $unique_permissao,
        ];
    
        $this->_mensagensErro = [
            'permissao.min' => 'Permissão deve ter no mínimo 5 caracteres',
        ];
        
        return parent::validate();
    }
    
    
    # Buscas #
    public static function filterAndPaginate($codpermissao, $permissao)
    {
        return Permissao::codpermissao($codpermissao)
            ->permissao($permissao)    
            ->orderBy('permissao', 'DESC')
            ->paginate(20);
    }
    
    public function scopeCodpermissao($query, $codpermissao)
    {
        if ($codpermissao)
        {
            $query->where('codpermissao', "$codpermissao");
        }
    }      
    
    public function scopePermissao($query, $permissao)
    {
        if (trim($permissao) != "")
        {
            $query->where('permissao', "ILIKE", "%$permissao%");
        }
    }

            
}
