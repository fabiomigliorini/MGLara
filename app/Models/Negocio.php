<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codnegocio                         NOT NULL DEFAULT nextval('tblnegocio_codnegocio_seq'::regclass)
 * @property  bigint                         $codpessoa
 * @property  bigint                         $codfilial                          NOT NULL
 * @property  timestamp                      $lancamento                         NOT NULL
 * @property  bigint                         $codpessoavendedor
 * @property  bigint                         $codoperacao                        NOT NULL
 * @property  bigint                         $codnegociostatus                   NOT NULL
 * @property  varchar(500)                   $observacoes
 * @property  bigint                         $codusuario                         NOT NULL
 * @property  numeric(14,2)                  $valordesconto
 * @property  boolean                        $entrega                            NOT NULL DEFAULT false
 * @property  timestamp                      $acertoentrega
 * @property  bigint                         $codusuarioacertoentrega
 * @property  timestamp                      $alteracao
 * @property  bigint                         $codusuarioalteracao
 * @property  timestamp                      $criacao
 * @property  bigint                         $codusuariocriacao
 * @property  bigint                         $codnaturezaoperacao                NOT NULL
 * @property  numeric(14,2)                  $valorprodutos                      NOT NULL
 * @property  numeric(14,2)                  $valortotal                         NOT NULL
 * @property  numeric(14,2)                  $valoraprazo                        NOT NULL
 * @property  numeric(14,2)                  $valoravista                        NOT NULL
 * @property  bigint                         $codestoquelocal                    NOT NULL
 *
 * Chaves Estrangeiras
 * @property  Filial                         $Filial
 * @property  NaturezaOperacao               $NaturezaOperacao
 * @property  NegocioStatus                  $NegocioStatus
 * @property  Operacao                       $Operacao
 * @property  Pessoa                         $Pessoa
 * @property  Pessoa                         $PessoaVendedor
 * @property  Usuario                        $Usuario
 * @property  Usuario                        $UsuarioAcertoEntrega
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 * @property  Estoquelocal                   $Estoquelocal
 *
 * Tabelas Filhas
 * @property  NegocioFormaPagamento[]        $NegocioformapagamentoS
 * @property  NegocioProdutoBarra[]          $NegocioprodutobarraS
 */

class Negocio extends MGModel
{
    protected $table      = 'tblnegocio';
    protected $primaryKey = 'codnegocio';
    protected $fillable   = [
        'codpessoa',
        'codfilial',
        'lancamento',
        'codpessoavendedor',
        'codoperacao',
        'codnegociostatus',
        'observacoes',
        'codusuario',
        'valordesconto',
        'entrega',
        'acertoentrega',
        'codusuarioacertoentrega',
        'codnaturezaoperacao',
        'valorprodutos',
        'valortotal',
        'valoraprazo',
        'valoravista',
        'codestoquelocal',
    ];
    protected $dates = [
        'lancamento',
        'acertoentrega',
        'alteracao',
        'criacao',
    ];

    // Chaves Estrangeiras
    public function Filial()
    {
        return $this->belongsTo(Filial::class, 'codfilial', 'codfilial');
    }

    public function NaturezaOperacao()
    {
        return $this->belongsTo(NaturezaOperacao::class, 'codnaturezaoperacao', 'codnaturezaoperacao');
    }

    public function NegocioStatus()
    {
        return $this->belongsTo(NegocioStatus::class, 'codnegociostatus', 'codnegociostatus');
    }

    public function Operacao()
    {
        return $this->belongsTo(Operacao::class, 'codoperacao', 'codoperacao');
    }

    public function Pessoa()
    {
        return $this->belongsTo(Pessoa::class, 'codpessoa', 'codpessoa');
    }

    public function PessoaVendedor()
    {
        return $this->belongsTo(Pessoa::class, 'codpessoavendedor', 'codpessoa');
    }

    public function Usuario()
    {
        return $this->belongsTo(Usuario::class, 'codusuario', 'codusuario');
    }

    public function UsuarioAcertoEntrega()
    {
        return $this->belongsTo(Usuario::class, 'codusuario', 'codusuarioacertoentrega');
    }

    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuario', 'codusuarioalteracao');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuario', 'codusuariocriacao');
    }

    public function EstoqueLocal()
    {
        return $this->belongsTo(EstoqueLocal::class, 'codestoquelocal', 'codestoquelocal');
    }

    // Tabelas Filhas
    public function NegocioFormaPagamentosS()
    {
        return $this->hasMany(NegocioFormaPagamentos::class, 'codnegocio', 'codnegocio');
    }

    public function NegocioProdutoBarraS()
    {
        return $this->hasMany(NegocioProdutoBarra::class, 'codnegocio', 'codnegocio');
    }

    public function validate()
    {
        $this->_regrasValidacao = [
            'codfilial'           => 'required',
            'codestoquelocal'     => 'required',
            'codnaturezaoperacao' => 'required',
            'codpessoa'           => 'required',
            'codpessoavendedor'   => 'required',
        ];

        $this->_mensagensErro = [
            'codfilial.required'           => 'O campo Filial é obrigatório.',
            'codestoquelocal.required'     => 'O campo Local Estoque é obrigatório.',
            'codnaturezaoperacao.required' => 'O campo Natureza de Operação é obrigatório.',
            'codpessoa.required'           => 'O campo Pessoa é obrigatório.',
            'codpessoavendedor.required'   => 'O campo Vendedor é obrigatório.',
        ];

        return parent::validate();
    }

}
