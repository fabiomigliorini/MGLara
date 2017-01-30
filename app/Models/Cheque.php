<?php

namespace MGLara\Models;
use MGLara\Library\Cmc7\Cmc7;
use MGLara\Models\Banco;
use Illuminate\Support\Facades\Validator;
/**
 * Campos
 * @property  bigint                         $codcheque                          NOT NULL DEFAULT nextval('tblcheque_codcheque_seq'::regclass)
 * @property  varchar(50)                    $cmc7
 * @property  bigint                         $codbanco                           NOT NULL
 * @property  varchar(10)                    $agencia                            NOT NULL
 * @property  varchar(15)                    $contacorrente                      NOT NULL
 * @property  varchar(100)                   $emitente                           NOT NULL
 * @property  varchar(15)                    $numero                             NOT NULL
 * @property  date                           $emissao                            NOT NULL
 * @property  date                           $vencimento                         NOT NULL
 * @property  date                           $repasse
 * @property  varchar(50)                    $destino
 * @property  date                           $devolucao
 * @property  varchar(50)                    $motivodevolucao
 * @property  varchar(200)                   $observacao
 * @property  timestamp                      $lancamento                         NOT NULL
 * @property  timestamp                      $alteracao
 * @property  timestamp                      $cancelamento
 * @property  numeric(14,2)                  $valor                              NOT NULL
 * @property  bigint                         $codusuarioalteracao
 * @property  timestamp                      $criacao
 * @property  bigint                         $codusuariocriacao
 * @property  bigint                         $codpessoa
 * @property  smallint                       $indstatus                          1 - À Repassar / 2 - Repassado / 3 - Devolvido / 4 - Em Cobranca / 5 - Liquidado
 * @property  bigint                         $codtitulo
 *
 * Chaves Estrangeiras
 * @property  Banco                          $Banco
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 * @property  Pessoa                         $Pessoa
 * @property  Titulo                         $Titulo
 *
 * Tabelas Filhas
 * @property  ChequeRepasseCheque[]          $ChequeRepasseChequeS
 * @property  ChequeEmitente[]               $ChequeEmitenteS
 * @property  Cobranca[]                     $CobrancaS
 */

class Cheque extends MGModel
{

    const INDSTATUS_AREPASSAR = 1;
    const INDSTATUS_REPASSADO = 2;
    const INDSTATUS_DEVOLVIDO = 3;
    const INDSTATUS_EMCOBRANCA = 4;
    const INDSTATUS_LIQUIDADO = 5;

    public static $indstatus_descricao = [
        self::INDSTATUS_AREPASSAR => 'À Repassar',
        self::INDSTATUS_REPASSADO => 'Repassado',
        self::INDSTATUS_DEVOLVIDO => 'Devolvido',
        self::INDSTATUS_EMCOBRANCA => 'Em Cobranca',
        self::INDSTATUS_LIQUIDADO => 'Liquidado'
    ];

    public static $indstatus_class = [
        self::INDSTATUS_AREPASSAR => 'label-primary',
        self::INDSTATUS_REPASSADO => 'label-warning',
        self::INDSTATUS_DEVOLVIDO => 'label-danger',
        self::INDSTATUS_EMCOBRANCA => 'label-danger',
        self::INDSTATUS_LIQUIDADO => 'label-success'
    ];

    protected $table = 'tblcheque';
    protected $primaryKey = 'codcheque';
    protected $fillable = [
        'cmc7',
        'emitente',
        'numero',
        'emissao',
        'vencimento',
        'observacao',
        'valor',
        'codpessoa',
    ];
    protected $dates = [
        'emissao',
        'vencimento',
        'repasse',
        'devolucao',
        'lancamento',
        'alteracao',
        'cancelamento',
        'criacao',
    ];


    // Chaves Estrangeiras
    public function Banco()
    {
        return $this->belongsTo(Banco::class, 'codbanco', 'codbanco');
    }

    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuarioalteracao', 'codusuario');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuariocriacao', 'codusuario');
    }

    public function Pessoa()
    {
        return $this->belongsTo(Pessoa::class, 'codpessoa', 'codpessoa');
    }

    public function Titulo()
    {
        return $this->belongsTo(Titulo::class, 'codtitulo', 'codtitulo');
    }


    // Tabelas Filhas
    public function ChequeRepasseChequeS()
    {
        return $this->hasMany(ChequeRepasseCheque::class, 'codcheque', 'codcheque');
    }

    public function ChequeEmitenteS()
    {
        return $this->hasMany(ChequeEmitente::class, 'codcheque', 'codcheque');
    }

    public function CobrancaS()
    {
        return $this->hasMany(Cobranca::class, 'codcheque', 'codcheque');
    }

    public static function search($parametros)
    {
        $query = Cheque::query();
        /*
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
        */
        return $query;
    }

    public static function findUltimoMesmoEmitente($banco, $agencia, $contacorrente){

        $query = Cheque::query();
        $query->where('codbanco', $banco);
        $query->where('agencia', $agencia);
        $query->where('contacorrente', $contacorrente);
        $query->orderBy('criacao', 'DESC');

        return $query->first();

    }

    public static function status() {
        return [
            1 => ['label'=>'À Repassar', 'class'=>'label-primary'],
            2 => ['label'=>'Repassado', 'class'=>'label-primary'],
            3 => ['label'=>'Devolvido', 'class'=>'label-primary'],
            4 => ['label'=>'Em Cobranca', 'class'=>'label-primary'],
            5 => ['label'=>'Liquidado', 'class'=>'label-primary']
        ];
    }

    public function validate() {

        $unique  = "uniqueMultiple:tblcheque,codcheque,$this->codcheque,cmc7";

        $this->_regrasValidacao = [
            'cmc7'  => "required|$unique",
            'valor'  => 'required|min:0.01|max:99999999|numeric',
            'emissao'  => 'required',
            'vencimento'  => 'required|vencimento',
            'codpessoa'  => 'required'
        ];

        $this->_mensagensErro = [
            'cmc7.required'                => 'O campo CMC7 deve ser preenchido!',
            'cmc7.unique_multiple'         => 'Já exite um CMC7 cadastrado com esse número!',

            'valor.numeric'                => 'O campo Valor deve ser um número',
            'valor.required'               => 'O campo Valor deve ser preenchido!',

            'emissao.required'             => 'O campo Emissão deve ser preenchido!',
            'vencimento.required'          => 'O campo Emissão deve ser preenchido!',
            'vencimento.vencimento'          => 'teste',

            'codpessoa.required'           => 'Selecione o Cliente'

        ];

        $model = $this;
        Validator::extend('vencimento', function($attribute, $value, $parameters, $validator) use ($model) {
            return $model->vencimento >= $model->emissao;
        });

        return parent::validate();
    }

    public function parseCmc7(){
         $cmc7n = new Cmc7($this->cmc7);
         $this->codbanco = Banco::where('numerobanco', '=', $cmc7n->banco())->first()->codbanco;
         $this->agencia = $cmc7n->agencia();
         $this->contacorrente = $cmc7n->contacorrente();
         $this->numero = $cmc7n->numero();
    }
}