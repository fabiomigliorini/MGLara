<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codvalecompra                      NOT NULL DEFAULT nextval('tblvalecompra_codvalecompra_seq'::regclass)
 * @property  bigint                         $codvalecompramodelo                NOT NULL
 * @property  bigint                         $codfilial                          NOT NULL
 * @property  bigint                         $codpessoafavorecido                NOT NULL
 * @property  bigint                         $codpessoa                          NOT NULL
 * @property  varchar(200)                   $observacoes                        NOT NULL
 * @property  varchar(50)                    $aluno                              
 * @property  varchar(30)                    $turma                              
 * @property  numeric(14,2)                  $totalprodutos                      
 * @property  numeric(14,2)                  $desconto                           
 * @property  numeric(14,2)                  $total                              
 * @property  bigint                         $codtitulo                          
 * @property  timestamp                      $inativo                            
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 *
 * Chaves Estrangeiras
 * @property  Usuario                        $UsuarioCriacao
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Pessoa                         $PessoaFavorecido
 * @property  Pessoa                         $Pessoa
 * @property  ValeCompraModelo               $ValeCompraModelo
 * @property  Titulo                         $Titulo                        
 * @property  Filial                         $Filial                        
 *
 * Tabelas Filhas
 * @property  ValeCompraFormaPagamento[]     $ValeCompraFormaPagamentoS
 * @property  ValeCompraProdutoBarra[]       $ValeCompraProdutoBarraS
 */

class ValeCompra extends MGModel
{
    protected $table = 'tblvalecompra';
    protected $primaryKey = 'codvalecompra';
    protected $fillable = [
        'codvalecompramodelo',
        'codfilial',
        'codpessoafavorecido',
        'codpessoa',
        'observacoes',
        'aluno',
        'turma',
        'totalprodutos',
        'desconto',
        'total',
        'codtitulo',
        'inativo',
    ];
    protected $dates = [
        'inativo',
        'criacao',
        'alteracao',
    ];


    // Chaves Estrangeiras
    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuariocriacao');
    }

    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuarioalteracao');
    }

    public function PessoaFavorecido()
    {
        return $this->belongsTo(Pessoa::class, 'codpessoafavorecido');
    }

    public function Pessoa()
    {
        return $this->belongsTo(Pessoa::class, 'codpessoa');
    }

    public function ValeCompraModelo()
    {
        return $this->belongsTo(ValeCompraModelo::class, 'codvalecompramodelo');
    }

    public function Titulo()
    {
        return $this->belongsTo(Titulo::class, 'codtitulo');
    }

    public function Filial()
    {
        return $this->belongsTo(Filial::class, 'codfilial');
    }


    // Tabelas Filhas
    public function ValeCompraFormaPagamentoS()
    {
        return $this->hasMany(ValeCompraFormaPagamento::class, 'codvalecompra');
    }

    public function ValeCompraProdutoBarraS()
    {
        return $this->hasMany(ValeCompraProdutoBarra::class, 'codvalecompra');
    }

    public static function search($parametros)
    {
        $query = ValeCompra::query();
        
        if (!empty($parametros['codvalecompra'])) {
            $query->where('codvalecompra', $parametros['codvalecompra']);
        }
        
        if (!empty($parametros['codpessoafavorecido'])) {
            $query->where('codpessoafavorecido', $parametros['codpessoafavorecido']);
        }
        
        if (!empty($parametros['codpessoa'])) {
            $query->where('codpessoa', $parametros['codpessoa']);
        }
        
        if (!empty($parametros['codusuariocriacao'])) {
            $query->where('codusuariocriacao', $parametros['codusuariocriacao']);
        }

        if (!empty($parametros['criacao_de'])) {
            $query->where('criacao', '>=', $parametros['criacao_de']);
        }

        if (!empty($parametros['criacao_ate'])) {
            $query->where('criacao', '<=', $parametros['criacao_ate']);
        }

        if (!empty($parametros['aluno'])) {
            $palavras = explode(' ', $parametros['aluno']);
            foreach ($palavras as $palavra) {
                $query->where('aluno', 'ilike', "%{$palavra}%");
            }
        }
        
        if (!empty($parametros['turma'])) {
            $palavras = explode(' ', $parametros['turma']);
            foreach ($palavras as $palavra) {
                $query->where('turma', 'ilike', "%{$palavra}%");
            }
        }
        
        if (!empty($parametros['codvalecompramodelo'])) {
            $query->where('codvalecompramodelo', $parametros['codvalecompramodelo']);
        }
        
        switch ($parametros['ativo']) {
            case 1:
                $query->whereNull('inativo');
                break;

            case 2:
                $query->whereNotNull('inativo');
                break;

            default:
                break;
        }
        
        return $query;
    }

}