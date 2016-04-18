<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codpais                            NOT NULL DEFAULT nextval('tblpais_codpais_seq'::regclass)
 * @property  varchar(50)                    $pais                               
 * @property  varchar(2)                     $sigla                              
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 * @property  bigint                         $codigooficial                      
 *
 * Chaves Estrangeiras
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 * @property  Estado[]                       $EstadoS
 */

class Pais extends MGModel
{
    protected $table = 'tblpais';
    protected $primaryKey = 'codpais';
    protected $fillable = [
        'pais',
        'sigla',
        'codigooficial',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
    ];

    public function validate() {
        if ($this->codpais) {
            $unique_pais = 'unique:tblpais,pais,'.$this->codpais.',codpais';
            $unique_sigla = 'unique:tblpais,sigla,'.$this->codpais.',codpais';
        } else {
            $unique_pais = 'unique:tblpais,pais';
            $unique_sigla = 'unique:tblpais,sigla';
        }           
        
        $this->_regrasValidacao = [
            'pais' => "required|$unique_pais",  
            'sigla' => "required|$unique_sigla",  
        ];
    
        $this->_mensagensErro = [
            'pais.required' => 'O campo Pais não pode ser vazio',
            'pais.unique' => 'Esta país já esta cadastrado',
            'sigla.required' => 'O campo Sigla não pode ser vazio',
            'sigla.unique' => 'Esta sigla já esta cadastrado',
        ];
        
        return parent::validate();
    }
    
    // Chaves Estrangeiras
    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuario', 'codusuarioalteracao');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuario', 'codusuariocriacao');
    }


    // Tabelas Filhas
    public function EstadoS()
    {
        return $this->hasMany(Estado::class, 'codpais', 'codpais');
    }

    // Buscas 
    public static function filterAndPaginate($codpais, $pais)
    {
        return Pais::codpais(numeroLimpo($codpais))
            ->pais($pais)
            ->orderBy('pais', 'ASC')
            ->paginate(20);
    }
    
    public function scopeCodpais($query, $codpais)
    {
        if (trim($codpais) === '')
            return;
        
        $query->where('codpais', $codpais);
    }
    
    public function scopePais($query, $pais)
    {
        if (trim($pais) === '')
            return;
        
        $pais = explode(' ', $pais);
        foreach ($pais as $str)
            $query->where('pais', 'ILIKE', "%$str%");
    }
}
