<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codvalecompramodelo                NOT NULL DEFAULT nextval('tblvalecompramodelo_codvalecompramodelo_seq'::regclass)
 * @property  bigint                         $codpessoafavorecido                NOT NULL
 * @property  varchar(50)                    $modelo                             
 * @property  varchar(30)                    $turma                              
 * @property  varchar(200)                   $observacoes                        NOT NULL
 * @property  numeric(14,2)                  $totalprodutos                      
 * @property  numeric(14,2)                  $desconto                           
 * @property  numeric(14,2)                  $total                              
 * @property  timestamp                      $inativo                            
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  integer                        $ano                                
 *
 * Chaves Estrangeiras
 * @property  Usuario                        $UsuarioCriacao
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Pessoa                         $PessoaFavorecido                        
 *
 * Tabelas Filhas
 * @property  ValeCompra[]                   $ValeCompraS
 * @property  ValeCompraModeloProdutoBarra[] $ValeCompraModeloProdutoBarraS
 */

class ValeCompraModelo extends MGModel
{
    protected $table = 'tblvalecompramodelo';
    protected $primaryKey = 'codvalecompramodelo';
    protected $fillable = [
        'codpessoafavorecido',
        'modelo',
        'turma',
        'observacoes',
        'totalprodutos',
        'desconto',
        'total',
        'inativo',
        'ano',
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


    // Tabelas Filhas
    public function ValeCompraS()
    {
        return $this->hasMany(ValeCompra::class, 'codvalecompramodelo', 'codvalecompramodelo');
    }

    public function ValeCompraModeloProdutoBarraS()
    {
        return $this->hasMany(ValeCompraModeloProdutoBarra::class, 'codvalecompramodelo', 'codvalecompramodelo');
    }

    public static function search($parametros)
    {
        $query = ValeCompraModelo::query();
        
        if (!empty($parametros['codpessoafavorecido'])) {
            $query->where('codpessoafavorecido', $parametros['codpessoafavorecido']);
        }
        
        if (!empty($parametros['ano'])) {
            $query->where('ano', $parametros['ano']);
        }
        
        if (!empty($parametros['modelo'])) {
            $palavras = explode(' ', $parametros['modelo']);
            foreach ($palavras as $palavra) {
                $query->where('modelo', 'ilike', "%{$palavra}%");
            }
        }
        
        //dd($query->toSql());
        
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