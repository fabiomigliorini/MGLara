<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codcobrancahistorico               NOT NULL DEFAULT nextval('tblcobrancahistorico_codcobrancahistorico_seq'::regclass)
 * @property  bigint                         $codpessoa                          NOT NULL
 * @property  varchar(255)                   $historico                          NOT NULL
 * @property  boolean                        $emailautomatico                    NOT NULL DEFAULT false
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 *
 * Chaves Estrangeiras
 * @property  Pessoa                         $Pessoa                        
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 * @property  Cobrancahistoricotitulo[]      $CobrancahistoricotituloS
 */

class CobrancaHistorico extends MGModel
{
    protected $table = 'tblcobrancahistorico';
    protected $primaryKey = 'codcobrancahistorico';
    protected $fillable = [
        'codpessoa',
        'historico',
        'emailautomatico',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
    ];


    // Chaves Estrangeiras
    public function Pessoa()
    {
        return $this->belongsTo(Pessoa::class, 'codpessoa', 'codpessoa');
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
    public function CobrancaHistoricoTituloS()
    {
        return $this->hasMany(CobrancaHistoricoTitulo::class, 'codcobrancahistorico', 'codcobrancahistorico');
    }

    public static function scopeByPessoa($query, $codpessoa)
    {
        $query->where('codpessoa', $codpessoa);
    }

}
