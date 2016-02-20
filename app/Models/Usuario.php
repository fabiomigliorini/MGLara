<?php

namespace MGLara\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
##use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use MGLara\Models\MGModel;


class Usuario extends MGModel implements AuthenticatableContract, /*AuthorizableContract,*/ CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tblusuario';
    protected $primaryKey = 'codusuario';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'usuario', 
        'senha',
        'codecf',
        'codfilial',
        'codoperacao',
        'codpessoa',
        'impressoratelanegocio',
        'codportador',
        'impressoratermica',
        'codusuarioalteracao',
        'codusuariocriacao',
        'ultimoacesso',
        'inativo',
        'impressoramatricial',        
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['senha', 'remember_token'];
    
    public function validate() {
        
    	if ($this->codusuario)
    		$unique_usuario = "unique:tblusuario,usuario,$this->codusuario,codusuario|required|min:5";
    	else
    		$unique_usuario = "unique:tblusuario,usuario|required|min:5";    	
    	
        $this->_regrasValidacao = [
            'usuario' => $unique_usuario, 
            'senha' => 'required_if:codusuario,null|min:6', 
            'codoperacao' => 'required', 
            'impressoramatricial' => 'required', 
            'impressoratermica' => 'required', 
        ];
    
        $this->_mensagensErro = [
            'usuario.required' => 'O campo usuário não pode ser vazio',
        ];
        
        return parent::validate();
    }

    public function getAuthPassword(){
        return $this->senha;
    }

    public function Ecf()
    {
        return $this->belongsTo(Ecf::class, 'codecf', 'codecf');
    }

    public function Filial()
    {
        return $this->belongsTo(Filial::class, 'codfilial', 'codfilial');
    }

    public function Operacao()
    {
        return $this->belongsTo(Operacao::class, 'codoperacao', 'codoperacao');
    }

    public function Pessoa()
    {
        return $this->belongsTo(Pessoa::class, 'codpessoa', 'codpessoa');
    }

    public function Portador()
    {
        return $this->belongsTo(Portador::class, 'codportador', 'codportador');
    }
    
    public function GrupoUsuario()
    {
        return $this->belongsToMany(GrupoUsuario::class, 'tblgrupousuariousuario', 'codusuario', 'codgrupousuario')->withPivot('codgrupousuario', 'codfilial');
    }    
    
    public function extractgrupos()
    {
        $arraygrupos = [];
        foreach($this->GrupoUsuario as $data) 
        {
            $arraygrupos [] = [
                'filial'=>$data->pivot->codfilial, 
                'grupo'=>$data->pivot->codgrupousuario
            ];
        }
        return $arraygrupos;        
    }
    
    public function can($permission = null)
    {
        return (!is_null($permission) && $this->checkPermission($permission));
    }

    public function filiais()
    {
        $filiais = $this->GrupoUsuario->load('Filiais')->fetch('filiais')->toArray();
        return array_map('strtolower', array_unique(array_flatten(array_map(function ($filial) {
            return array_pluck($filial, 'codfilial');
        }, $filiais))));
    }

    protected function checkPermission($perm)
    {
        $permissions = $this->getAllPermissionsFormAllRoles();      
        $permissionArray = is_array($perm) ? $perm : [$perm];

        return count(array_intersect($permissions, $permissionArray));
    }
    
    protected function getAllPermissionsFormAllRoles()
    {
        $permissions = $this->GrupoUsuario->load('Permissao')->fetch('permissao')->toArray();
        return array_map('strtolower', array_unique(array_flatten(array_map(function ($permission) {
            return array_pluck($permission, 'permissao');
        }, $permissions))));
    } 

    static function printers() 
    {
        $o = shell_exec("lpstat -d -p");
        $res = explode("\n", $o);
        $printers = [];
        foreach ($res as $r) 
        {
            if (strpos($r, "printer") !== FALSE) 
            {
                $r = str_replace("printer ", "", $r);
                $r = explode(" ", $r);
                $printers[$r[0]] = $r[0];
            }
        }
        
        return $printers;
    }    
    
    # Buscas #
    public static function filterAndPaginate($codusuario, $usuario, $codpessoa, $codfilial)
    {
        return Usuario::codusuario($codusuario)
            ->usuario($usuario)
            ->codpessoa($codpessoa)
            ->codfilial($codfilial)
            ->orderBy('usuario', 'ASC')
            ->paginate(20);
    }
    
    public function scopeCodusuario($query, $codusuario)
    {
        if ($codusuario)
        {
            $query->where('codusuario', "$codusuario");
        }
    }   
    
    public function scopeUsuario($query, $usuario)
    {
        if (trim($usuario) != "")
        {
            $query->where('usuario', "ILIKE", "%$usuario%");
        }
    }    
    
    public function scopeCodpessoa($query, $codpessoa)
    {
        if ($codpessoa)
        {
            $query->where('codpessoa', "$codpessoa");
        }
    }      
    
    public function scopeCodfilial($query, $codfilial)
    {
        if ($codfilial)
        {
            $query->where('codfilial', "$codfilial");
        }
    }      
}
