<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codcobrancahistoricotitulo         NOT NULL DEFAULT nextval('tblcobrancahistoricotitulo_codcobrancahistoricotitulo_seq'::regclass)
 * @property  bigint                         $codcobrancahistorico               NOT NULL
 * @property  bigint                         $codtitulo                          NOT NULL
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 *
 * Chaves Estrangeiras
 * @property  CobrancaHistorico              $CobrancaHistorico             
 * @property  Titulo                         $Titulo                        
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 */

class CobrancaHistoricoTitulo extends MGModel
{
    protected $table = 'tblcobrancahistoricotitulo';
    protected $primaryKey = 'codcobrancahistoricotitulo';
    protected $fillable = [
        'codcobrancahistorico',
        'codtitulo',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
    ];


    // Chaves Estrangeiras
    public function CobrancaHistorico()
    {
        return $this->belongsTo(CobrancaHistorico::class, 'codcobrancahistorico', 'codcobrancahistorico');
    }

    public function Titulo()
    {
        return $this->belongsTo(Titulo::class, 'codtitulo', 'codtitulo');
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

}
