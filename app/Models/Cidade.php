<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codcidade                          NOT NULL DEFAULT nextval('tblcidade_codcidade_seq'::regclass)
 * @property  bigint                         $codestado                          
 * @property  varchar(50)                    $cidade                             
 * @property  varchar(3)                     $sigla                              
 * @property  bigint                         $codigooficial                      
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 *
 * Chaves Estrangeiras
 * @property  Estado                         $Estado                        
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 * @property  Pessoa[]                       $PessoaCobrancaS
 * @property  Pessoa[]                       $PessoaS
 */

class Cidade extends MGModel
{
    protected $table = 'tblcidade';
    protected $primaryKey = 'codcidade';
    protected $fillable = [
        'codestado',
        'cidade',
        'sigla',
        'codigooficial',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
    ];
    
    public function validate() 
    {
        if ($this->codcidade) {
            $unique_cidade = 'unique:tblcidade,cidade,'.$this->codcidade.',codcidade';
            $unique_sigla = 'unique:tblcidade,sigla,'.$this->codcidade.',codcidade';
            $unique_codigo = 'unique:tblcidade,codigooficial,'.$this->codcidade.',codcidade';
        } else {
            $unique_cidade = 'unique:tblcidade,cidade';
            $unique_sigla = 'unique:tblcidade,sigla';
            $unique_codigo = 'unique:tblcidade,codigooficial';
        }           
        
        $this->_regrasValidacao = [
            'cidade' => "required|$unique_cidade",  
            'sigla' => "required|$unique_sigla",  
            'codigooficial' => "required|numeric|$unique_codigo",  
        ];
    
        $this->_mensagensErro = [
            'cidade.required' => 'O campo Cidade não pode ser vazio',
            'cidade.unique' => 'Esta Cidade já esta cadastrado',
            'sigla.required' => 'O campo Sigla não pode ser vazio',
            'sigla.unique' => 'Esta sigla já esta cadastrado',
            'codigooficial.required' => 'O campo Código não pode ser vazio',
            'codigooficial.unique' => 'Este código já esta cadastrado',
            'codigooficial.numeric' => 'O valor do código deve ser um numero',
        ];
        
        return parent::validate();
    }

    // Chaves Estrangeiras
    public function Estado()
    {
        return $this->belongsTo(Estado::class, 'codestado', 'codestado');
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
    public function PessoaCobrancaS()
    {
        return $this->hasMany(Pessoa::class, 'codcidade', 'codcidadecobranca');
    }

    public function PessoaS()
    {
        return $this->hasMany(Pessoa::class, 'codcidade', 'codcidade');
    }

    // Buscas 
    public static function filterAndPaginate($codestado, $codcidade, $cidade, $sigla, $codigooficial)
    {
        return Cidade::codestado($codestado)
            ->codcidade(numeroLimpo($codcidade))
            ->cidade($cidade)
            ->sigla($sigla)
            ->codigooficial($codigooficial)
            ->orderBy('cidade', 'ASC')
            ->paginate(20);
    }
    
    public static function select2($cidade)
    {
        return Cidade::cidade($cidade)
            ->join('tblestado', 'tblcidade.codestado', '=', 'tblestado.codestado')
            ->select('codcidade as id', 'cidade', 'tblestado.sigla as uf')
            ->orderBy('cidade', 'ASC')
            ->paginate(10);
    }

    public function scopeCodestado($query, $codestado)
    {
        $query->where('codestado', $codestado);
    }
    
    public function scopeCodcidade($query, $codcidade)
    {
        if (trim($codcidade) === '')
            return;
        
        $query->where('codcidade', $codcidade);
    }

    public function scopeCodigooficial($query, $codigooficial)
    {
        if (trim($codigooficial) === '')
            return;
        
        $query->where('codigooficial', $codigooficial);
    }

    public function scopeCidade($query, $cidade)
    {
        if (trim($cidade) === '')
            return;
        
        $cidade = explode(' ', $cidade);
        foreach ($cidade as $str)
            $query->where('cidade', 'ILIKE', "%$str%");
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
