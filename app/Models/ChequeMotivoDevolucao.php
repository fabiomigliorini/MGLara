<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codchequemotivodevolucao           NOT NULL DEFAULT nextval('tblchequemotivodevolucao_codchequemotivodevolucao_seq'::regclass)
 * @property  smallint                       $numero                             NOT NULL
 * @property  varchar(100)                   $chequemotivodevolucao              NOT NULL
 * @property  timestamp                      $criacao
 * @property  bigint                         $codusuariocriacao
 * @property  timestamp                      $alteracao
 * @property  bigint                         $codusuarioalteracao
 *
 * Chaves Estrangeiras
 * @property  Usuario                        $UsuarioCriacao
 * @property  Usuario                        $UsuarioAlteracao
 *
 * Tabelas Filhas
 * @property  ChequeDevolucao[]              $ChequeDevolucaoS
 */

class ChequeMotivoDevolucao extends MGModel
{
    protected $table = 'tblchequemotivodevolucao';
    protected $primaryKey = 'codchequemotivodevolucao';
    protected $fillable = [
        'numero',
        'chequemotivodevolucao',
    ];
    protected $dates = [
        'criacao',
        'alteracao',
    ];


    // Chaves Estrangeiras
    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuariocriacao', 'codusuario');
    }

    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuarioalteracao', 'codusuario');
    }


    // Tabelas Filhas
    public function ChequedevolucaoS()
    {
        return $this->hasMany(ChequeDevolucao::class, 'codchequemotivodevolucao', 'codchequemotivodevolucao');
    }

    public function validate() {

        $unique  = "uniqueMultiple:tblchequemotivodevolucao,codchequemotivodevolucao,$this->codchequemotivodevolucao,numero";

        $this->_regrasValidacao = [
            'numero'  => "required|numeric|$unique",
            'chequemotivodevolucao'  => 'required|min:5'
        ];

        $this->_mensagensErro = [
            'numero.required'               => 'O campo Número deve ser preenchido!',
            'chequemotivodevolucao.required'=> 'O campo Descrição deve ser preenchido!',
            'chequemotivodevolucao.min'     => 'O campo Descrição deve ter no minimo 5 caracteres!',
            'numero.numeric'                => 'O campo Número deve ser um número',
            'numero.unique_multiple'        => 'Já exite um motivo de devolução cadastrado com esse número!'
        ];

        return parent::validate();
    }

    public static function search($parametros)
    {
        $query = ChequeMotivoDevolucao::query();

        if (!empty($parametros['codchequemotivodevolucao'])) {
            $query->where('codchequemotivodevolucao', $parametros['codchequemotivodevolucao']);
        }

        if (!empty($parametros['numero'])) {
            $query->where('numero', $parametros['numero']);
        }

        if (!empty($parametros['chequemotivodevolucao'])) {
            $palavras = explode(' ', $parametros['chequemotivodevolucao']);
            foreach ($palavras as $palavra) {
                $query->where('chequemotivodevolucao', 'ilike', "%{$palavra}%");
            }
        }

        return $query;
    }

}