<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codcobranca                        NOT NULL DEFAULT nextval('tblcobranca_codcobranca_seq'::regclass)
 * @property  bigint                         $codtitulo                          
 * @property  bigint                         $codcheque                          
 * @property  date                           $agendamento                        NOT NULL
 * @property  varchar(500)                   $posicao                            
 * @property  bigint                         $codportador                        NOT NULL
 * @property  date                           $reagendamento                      
 * @property  varchar(500)                   $observacoes                        
 * @property  numeric(14,2)                  $debitoacerto                       
 * @property  numeric(14,2)                  $creditoacerto                      
 * @property  boolean                        $acertado                           NOT NULL DEFAULT false
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 *
 * Chaves Estrangeiras
 * @property  Cheque                         $Cheque                        
 * @property  Portador                       $Portador                      
 * @property  Titulo                         $Titulo                        
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 * @property  Movimentotitulo[]              $MovimentotituloS
 */

class Cobranca extends MGModel
{
    protected $table = 'tblcobranca';
    protected $primaryKey = 'codcobranca';
    protected $fillable = [
        'codtitulo',
        'codcheque',
        'agendamento',
        'posicao',
        'codportador',
        'reagendamento',
        'observacoes',
        'debitoacerto',
        'creditoacerto',
        'acertado',
    ];
    protected $dates = [
        'agendamento',
        'reagendamento',
        'alteracao',
        'criacao',
    ];


    // Chaves Estrangeiras
    public function Cheque()
    {
        return $this->belongsTo(Cheque::class, 'codcheque', 'codcheque');
    }

    public function Portador()
    {
        return $this->belongsTo(Portador::class, 'codportador', 'codportador');
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
    public function MovimentoTituloS()
    {
        return $this->hasMany(MovimentoTitulo::class, 'codcobranca', 'codcobranca');
    }


}
