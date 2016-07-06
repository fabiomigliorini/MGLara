<?php

namespace MGLara\Models;
use Carbon\Carbon;
/**
 * Campos
 * @property  bigint                         $codestoquemes                      NOT NULL DEFAULT nextval('tblestoquemes_codestoquemes_seq'::regclass)
 * @property  bigint                         $codestoquesaldo                    NOT NULL
 * @property  date                           $mes                                NOT NULL
 * @property  numeric(14,3)                  $inicialquantidade                  
 * @property  numeric(14,2)                  $inicialvalor                       
 * @property  numeric(14,3)                  $entradaquantidade                  
 * @property  numeric(14,2)                  $entradavalor                       
 * @property  numeric(14,3)                  $saidaquantidade                    
 * @property  numeric(14,2)                  $saidavalor                         
 * @property  numeric(14,3)                  $saldoquantidade                    
 * @property  numeric(14,2)                  $saldovalor                         
 * @property  numeric(14,6)                  $customedio                 
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

class EstoqueMes extends MGModel
{
    const CORTE_FISICO = '2016-04-01 00:00:00';
    const CORTE_FISCAL = '2015-01-01 00:00:00';
    
    protected $table = 'tblestoquemes';
    protected $primaryKey = 'codestoquemes';
    protected $fillable = [
        'codestoquesaldo',
        'mes',
        'inicialquantidade',
        'inicialvalor',
        'entradaquantidade',
        'entradavalor',
        'saidaquantidade',
        'saidavalor',
        'saldoquantidade',
        'saldovalor',
        'customedio',
    ];
    protected $dates = [
        'mes',
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
        return $this->hasMany(EstoqueMovimento::class, 'codestoquemes', 'codestoquemes');
    }


    
    # Buscas #
    public static function filterAndPaginate($codestoquemes)
    {
        return EstoqueMes::codestoquemes($codestoquemes)
            ->orderBy('criacao', 'DESC')
            ->paginate(20);
    }
    
    public function scopeCodestoquemes($query, $codestoquemes)
    {
        if ($codestoquemes)
        {
            $query->where('codestoquemes', "$codestoquemes");
        }
    }
    
    /**
     * 
     * @param type $codproduto
     * @param type $codestoquelocal
     * @param type $fiscal
     * @param Carbon $data
     * @return EstoqueMes
     */
    public static function buscaOuCria($codprodutovariacao, $codestoquelocal, $fiscal, $data)
    {
        $es = EstoqueSaldo::buscaOuCria($codprodutovariacao, $codestoquelocal, $fiscal);
        $mes = $data;
        $mes->day = 1;
        //Antes de 2015, cria somente um registro de mes por ano, em dezembro
        if ($mes->year <= 2015)
            $mes->month = 12;
        
        $em = self::where('codestoquesaldo', $es->codestoquesaldo)->where('mes', $mes)->first();
        if ($em == false)
        {
            $em = new EstoqueMes;
            $em->codestoquesaldo = $es->codestoquesaldo;
            $em->mes = $mes;
            $em->save();
        }
        return $em;
    }
    
    public function buscaProximos($qtd = 7)
    {
        $ems = self::where('codestoquesaldo', $this->codestoquesaldo)
               ->where('mes', '>', $this->mes)
               ->orderBy('mes', 'asc')
               ->take($qtd)
               ->get();
        return $ems;
    }
    
    public function buscaAnteriores($qtd = 7)
    {
        $ems = self::where('codestoquesaldo', $this->codestoquesaldo)
               ->where('mes', '<', $this->mes)
               ->orderBy('mes', 'desc')
               ->take($qtd)
               ->get();
        return $ems->reverse();
    }
    
    public function scopeUltimo($query, Carbon $mes = null)
    {
        if ($mes !== null)
        {
            $query->where('mes', '<=', $mes);
        }
        $query->OrderBy('mes', 'DESC');
    }
    
    public function scopeSaldo($query, $EstoqueSaldo)
    {
        if (gettype($EstoqueSaldo) == 'integer')
            $query->where('codestoquesaldo', $EstoqueSaldo);
        else
            $query->where('codestoquesaldo', $EstoqueSaldo->codestoquesaldo);
    } 
    
}
