<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codestoquesaldoconferencia         NOT NULL DEFAULT nextval('tblestoquesaldoconferencia_codestoquesaldoconferencia_seq'::regclass)
 * @property  bigint                         $codestoquesaldo                    NOT NULL
 * @property  numeric(14,3)                  $quantidadesistema                  
 * @property  numeric(14,3)                  $quantidadeinformada                NOT NULL
 * @property  numeric(14,6)                  $customediosistema                  
 * @property  numeric(14,6)                  $customedioinformado                NOT NULL
 * @property  timestamp                      $data                               NOT NULL
 * @property  varchar(200)                   $observacoes                        
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 *
 * Chaves Estrangeiras
 * @property  EstoqueSaldo                   $EstoqueSaldo                  
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 * @property  EstoqueMovimento[]             $EstoqueMovimentoS
 */

class EstoqueSaldoConferencia extends MGModel
{
    protected $table = 'tblestoquesaldoconferencia';
    protected $primaryKey = 'codestoquesaldoconferencia';
    protected $fillable = [
        'codestoquesaldo',
        'quantidadesistema',
        'quantidadeinformada',
        'customediosistema',
        'customedioinformado',
        'data',
        'observacoes',
    ];
    protected $dates = [
        'data',
        'alteracao',
        'criacao',
    ];


    // Chaves Estrangeiras
    public function EstoqueSaldo()
    {
        return $this->belongsTo(EstoqueSaldo::class, 'codestoquesaldo', 'codestoquesaldo');
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
    public function EstoqueMovimentoS()
    {
        return $this->hasMany(EstoqueMovimento::class, 'codestoquesaldoconferencia', 'codestoquesaldoconferencia');
    }

    public static function search($parametros)
    {
        $query = EstoqueSaldoConferencia::query();
        
        if ( (!empty($parametros['codproduto'])) || (!empty($parametros['codestoquelocal'])) || (!empty($parametros['fiscal'])) ) {

            $query->join('tblestoquesaldo', function($join) use ($parametros) {
                $join->on('tblestoquesaldo.codestoquesaldo', '=', 'tblestoquesaldoconferencia.codestoquesaldo');
            });
            
            if($parametros['fiscal'] == 'true') {
                $query->where('tblestoquesaldo.fiscal', true);
            } else {
                $query->where('tblestoquesaldo.fiscal', false);
            }            

            if ( (!empty($parametros['codproduto'])) || (!empty($parametros['codestoquelocal'])) ) {
                
                $query->join('tblestoquelocalprodutovariacao', function($join) use ($parametros) {
                    $join->on('tblestoquelocalprodutovariacao.codestoquelocalprodutovariacao', '=', 'tblestoquesaldo.codestoquelocalprodutovariacao');
                });

                if(!empty($parametros['codproduto'])) {

                    $query->join('tblprodutovariacao', function($join) use ($parametros) {
                        $join->on('tblprodutovariacao.codprodutovariacao', '=', 'tblestoquelocalprodutovariacao.codprodutovariacao');
                    });

                    $query->where('tblprodutovariacao.codproduto', '=', $parametros['codproduto']);
                }

                if(!empty($parametros['codestoquelocal'])) {
                    $query->where('tblestoquelocalprodutovariacao.codestoquelocal', '=', $parametros['codestoquelocal']);
                }
            }
        }
        
        if(!empty($parametros['criacao_de'])) {
            $query->where('criacao', '>=', $parametros['criacao_de']);
        }
            
        if (isset($parametros['criacao_ate']) and ! empty($parametros['criacao_ate'])) {
            $query->where('criacao', '<=', $parametros['criacao_ate']);
        }

        if (isset($parametros['data_de']) and ! empty($parametros['data_de'])) {
            $query->where('data', '>=', $parametros['data_de']);
        }

        if (isset($parametros['data_ate']) and ! empty($parametros['data_ate'])) {
            $query->where('data', '<=', $parametros['data_ate']);
        }

        if (isset($parametros['codusuario']) and ! empty($parametros['codusuario'])) {
            $query->where('codusuariocriacao', $parametros['codusuario']);
        }
        
        return $query;
    }
    

}