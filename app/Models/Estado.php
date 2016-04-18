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


    // Chaves Estrangeiras
    public function Pais()
    {
        return $this->belongsTo(Pais::class, 'codpais', 'codpais');
    }

    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuario', 'codusuarioalteracao');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuario', 'codusuariocriacao');
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
