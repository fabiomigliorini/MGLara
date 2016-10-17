<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codgrupocliente                    NOT NULL DEFAULT nextval('tblgrupocliente_codgrupocliente_seq'::regclass)
 * @property  varchar(50)                    $grupocliente                       NOT NULL
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
 * @property  Pessoa[]                       $PessoaS
 */

class GrupoCliente extends MGModel
{
    protected $table = 'tblgrupocliente';
    protected $primaryKey = 'codgrupocliente';
    protected $fillable = [
        'grupocliente',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
    ];

    public function validate() {
        if ($this->codgrupocliente) {
            $unique = 'unique:tblgrupocliente,grupocliente,'.$this->codgrupocliente.',codgrupocliente';
        } else {
            $unique = 'unique:tblgrupocliente,grupocliente';
        }           
        
        $this->_regrasValidacao = [
            'grupocliente' => "required|min:5|$unique",  
        ];
    
        $this->_mensagensErro = [
            'grupocliente.required' => 'O campo Grupo cliente não pode ser vazio',
            'grupocliente.min' => 'O campo Grupo cliente deve ter mais de 4 caracteres',
        ];
        
        return parent::validate();
    }

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
    public function PessoaS()
    {
        return $this->hasMany(Pessoa::class, 'codgrupocliente', 'codgrupocliente');
    }
    
    // Buscas 
    public static function filterAndPaginate($codgrupocliente, $grupocliente)
    {
        return GrupoCliente::codgrupocliente(numeroLimpo($codgrupocliente))
            ->grupocliente($grupocliente)
            ->orderBy('grupocliente', 'ASC')
            ->paginate(20);
    }
    
    public function scopeCodgrupocliente($query, $codgrupocliente)
    {
        if (trim($codgrupocliente) === '')
            return;
        
        $query->where('codgrupocliente', $codgrupocliente);
    }
    
    public function scopeGrupocliente($query, $grupocliente)
    {
        if (trim($grupocliente) === '')
            return;
        
        $grupocliente = explode(' ', $grupocliente);
        foreach ($grupocliente as $str)
            $query->where('grupocliente', 'ILIKE', "%$str%");
    }
}
