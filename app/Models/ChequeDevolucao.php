<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codchequedevolucao                 NOT NULL DEFAULT nextval('tblchequedevolucao_codchequedevolucao_seq'::regclass)
 * @property  date                           $data                               NOT NULL
 * @property  bigint                         $codchequemotivodevolucao           NOT NULL
 * @property  varchar()                      $observacoes                        DEFAULT 200
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  bigint                         $codchequerepassecheque             NOT NULL
 *
 * Chaves Estrangeiras
 * @property  ChequeMotivoDevolucao          $ChequeMotivoDevolucao         
 * @property  ChequeRepasseCheque            $ChequeRepasseCheque           
 * @property  Usuario                        $UsuarioCriacao
 * @property  Usuario                        $UsuarioAlteracao
 *
 * Tabelas Filhas
 */

class ChequeDevolucao extends MGModel
{
    protected $table = 'tblchequedevolucao';
    protected $primaryKey = 'codchequedevolucao';
    protected $fillable = [
        'data',
        'codchequemotivodevolucao',
        'observacoes',
        'codchequerepassecheque',
    ];
    protected $dates = [
        'data',
        'criacao',
        'alteracao',
    ];


    // Chaves Estrangeiras
    public function ChequeMotivoDevolucao()
    {
        return $this->belongsTo(ChequeMotivoDevolucao::class, 'codchequemotivodevolucao', 'codchequemotivodevolucao');
    }

    public function ChequeRepasseCheque()
    {
        return $this->belongsTo(ChequeRepasseCheque::class, 'codchequerepassecheque', 'codchequerepassecheque');
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

}