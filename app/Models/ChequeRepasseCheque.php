<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codchequerepassecheque             NOT NULL DEFAULT nextval('tblchequerepassecheque_codchequerepassecheque_seq'::regclass)
 * @property  bigint                         $codcheque                          NOT NULL
 * @property  bigint                         $codchequerepasse                   NOT NULL
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  date                           $compensacao                        
 *
 * Chaves Estrangeiras
 * @property  Cheque                         $Cheque                        
 * @property  ChequeRepasse                  $ChequeRepasse                 
 * @property  Usuario                        $UsuarioCriacao
 * @property  Usuario                        $UsuarioAlteracao
 *
 * Tabelas Filhas
 * @property  ChequeDevolucao[]              $ChequeDevolucaoS
 */

class ChequeRepasseCheque extends MGModel
{
    protected $table = 'tblchequerepassecheque';
    protected $primaryKey = 'codchequerepassecheque';
    protected $fillable = [
        'codcheque',
        'codchequerepasse',
        'compensacao',
    ];
    protected $dates = [
        'criacao',
        'alteracao',
        'compensacao',
    ];


    // Chaves Estrangeiras
    public function Cheque()
    {
        return $this->belongsTo(Cheque::class, 'codcheque', 'codcheque');
    }

    public function Chequerepasse()
    {
        return $this->belongsTo(Chequerepasse::class, 'codchequerepasse', 'codchequerepasse');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuariocriacao', 'codusuario');
    }

    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuarioalteracao', 'codusuario');
    }


    // Tabelas Filhas
    public function ChequeDevolucaoS()
    {
        return $this->hasMany(ChequeDevolucao::class, 'codchequerepassecheque', 'codchequerepassecheque');
    }


}