<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codtributacao                      NOT NULL DEFAULT nextval('tbltributacao_codtributacao_seq'::regclass)
 * @property  varchar(50)                    $tributacao                         NOT NULL
 * @property  varchar(10)                    $aliquotaicmsecf                    NOT NULL
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 *
 * Chaves Estrangeiras
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 * @property  NcmTributacao[]                $NcmTributacaoS
 * @property  Produto[]                      $ProdutoS
 * @property  TributacaoNaturezaOperacao[]   $TributacaoNaturezaOperacaoS
 */

class Tributacao extends MGModel
{
    const TRIBUTADO = 1;
    const ISENTO = 2;
    const SUBSTITUICAO = 3;

    protected $table = 'tbltributacao';
    protected $primaryKey = 'codtributacao';
    protected $fillable = [
        'tributacao',
        'aliquotaicmsecf',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
    ];
  
    public function validate() {
        
        $this->_regrasValidacao = [
            'tributacao' => 'required',
            'aliquotaicmsecf' => 'required',
        ];
    
        $this->_mensagensErro = [
            'tributacao.required' => 'Tributação não pode ser vazio.',
            'aliquotaicmsecf.required' => 'Aliquota ICMS ECF não pode ser vazio.',
        ];
        
        return parent::validate();
    }    

    // Chaves Estrangeiras
    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuarioalteracao', 'codusuario');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuariocriacao', 'codusuario');
    }


    // Tabelas Filhas
    public function NcmTributacaoS()
    {
        return $this->hasMany(NcmTributacao::class, 'codtributacao', 'codtributacao');
    }

    public function ProdutoS()
    {
        return $this->hasMany(Produto::class, 'codtributacao', 'codtributacao');
    }

    public function TributacaoNaturezaOperacaoS()
    {
        return $this->hasMany(TributacaoNaturezaOperacao::class, 'codtributacao', 'codtributacao');
    }    

    // Buscas 
    public static function filterAndPaginate($codtributacao, $tributacao)
    {
        return Tributacao::codtributacao(numeroLimpo($codtributacao))
            ->tributacao($tributacao)
            ->orderBy('tributacao', 'ASC')
            ->paginate(20);
    }
    
    public function scopeCodtributacao($query, $codtributacao)
    {
        if (trim($codtributacao) === '')
            return;
        
        $query->where('codtributacao', $codtributacao);
    }
    
    public function scopeTributacao($query, $tributacao)
    {
        if (trim($tributacao) === '')
            return;
        
        $tributacao = explode(' ', $tributacao);
        foreach ($tributacao as $str)
            $query->where('tributacao', 'ILIKE', "%$str%");
    }
}
