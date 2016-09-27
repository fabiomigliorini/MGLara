<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codestado                          NOT NULL DEFAULT nextval('tblestado_codestado_seq'::regclass)
 * @property  bigint                         $codpais                            
 * @property  varchar(50)                    $estado                             
 * @property  varchar(2)                     $sigla                              
 * @property  bigint                         $codigooficial                      
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 *
 * Chaves Estrangeiras
 * @property  Pais                           $Pais                          
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 * @property  Cidade[]                       $CidadeS
 * @property  TributacaoNaturezaOperacao[]   $TributacaoNaturezaOperacaoS
 */

class Estado extends MGModel
{
    protected $table = 'tblestado';
    protected $primaryKey = 'codestado';
    protected $fillable = [
        'codpais',
        'estado',
        'sigla',
        'codigooficial',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
    ];

    public function validate() 
    {
        if ($this->codestado) {
            $unique_estado = 'unique:tblestado,estado,'.$this->codestado.',codestado';
            $unique_sigla = 'unique:tblestado,sigla,'.$this->codestado.',codestado';
            $unique_codigo = 'unique:tblestado,codigooficial,'.$this->codestado.',codestado';
        } else {
            $unique_estado = 'unique:tblestado,estado';
            $unique_sigla = 'unique:tblestado,sigla';
            $unique_codigo = 'unique:tblestado,codigooficial';
        }           
        
        $this->_regrasValidacao = [
            'estado' => "required|$unique_estado",  
            'sigla' => "required|$unique_sigla",  
            'codigooficial' => "required|numeric|$unique_codigo",  
        ];
    
        $this->_mensagensErro = [
            'estado.required' => 'O campo Pais não pode ser vazio',
            'estado.unique' => 'Este estado já esta cadastrado',
            'sigla.required' => 'O campo Sigla não pode ser vazio',
            'sigla.unique' => 'Esta sigla já esta cadastrado',
            'codigooficial.required' => 'O campo Código não pode ser vazio',
            'codigooficial.unique' => 'Este código já esta cadastrado',
            'codigooficial.numeric' => 'O valor do código deve ser um numero',
        ];
        
        return parent::validate();
    }

    // Chaves Estrangeiras
    public function Pais()
    {
        return $this->belongsTo(Pais::class, 'codpais', 'codpais');
    }

    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuarioalteracao', 'codusuario');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuariocriacao', 'codusuario');
    }


    // Tabelas Filhas
    public function CidadeS()
    {
        return $this->hasMany(Cidade::class, 'codestado', 'codestado');
    }

    public function TributacaoNaturezaOperacaoS()
    {
        return $this->hasMany(TributacaoNaturezaOperacao::class, 'codestado', 'codestado');
    }

    // Buscas 
    public static function filterAndPaginate($codpais, $codestado, $estado, $sigla, $codigooficial)
    {
        return Estado::codpais($codpais)
            ->codestado(numeroLimpo($codestado))
            ->estado($estado)
            ->sigla($sigla)
            ->codigooficial($codigooficial)
            ->orderBy('estado', 'ASC')
            ->paginate(20);
    }
        
    public function scopeCodpais($query, $codpais)
    {
        $query->where('codpais', $codpais);
    }
    
    public function scopeCodestado($query, $codestado)
    {
        if (trim($codestado) === '')
            return;
        
        $query->where('codestado', $codestado);
    }

    public function scopeCodigooficial($query, $codigooficial)
    {
        if (trim($codigooficial) === '')
            return;
        
        $query->where('codigooficial', $codigooficial);
    }

    public function scopeEstado($query, $estado)
    {
        if (trim($estado) === '')
            return;
        
        $estado = explode(' ', $estado);
        foreach ($estado as $str)
            $query->where('estado', 'ILIKE', "%$str%");
    }    
    
    public function scopeSigla($query, $sigla)
    {
        if (trim($sigla) === '')
            return;
        
        $sigla = explode(' ', $sigla);
        foreach ($sigla as $str)
            $query->where('sigla', 'ILIKE', "%$str%");
    }    

}
