<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MGLara\Models;
use Carbon\Carbon;

use Illuminate\Support\Facades\DB;

/**
 * Campos
 * @property  bigint                         $codestoquesaldo                    NOT NULL DEFAULT nextval('tblestoquesaldo_codestoquesaldo_seq'::regclass)
 * @property  bigint                         $codestoquelocalprodutovariacao             NOT NULL
 * @property  boolean                        $fiscal                             NOT NULL
 * @property  timestamp                      $ultimaconferencia
 * @property  numeric(14,3)                  $saldoquantidade                    
 * @property  numeric(14,2)                  $saldovalor                         
 * @property  numeric(14,6)                  $customedio                 
 * @property  timestamp                      $ultimaconferencia
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 * @property  bigint                         $codestoquelocal                    NOT NULL
 *
 * Chaves Estrangeiras
 * @property  Produto                        $Produto                       
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 * @property  EstoqueLocalProdutoVariacao    $EstoqueLocalProdutoVariacao
 *
 * Tabelas Filhas
 * @property  EstoqueMes[]                   $EstoqueMesS
 * @property  EstoqueSaldoConferencia[]      $EstoqueSaldoConferenciaS
 */

class EstoqueSaldo extends MGModel
{
    protected $table = 'tblestoquesaldo';
    protected $primaryKey = 'codestoquesaldo';
    protected $fillable = [
        'fiscal',
        'saldoquantidade',
        'saldovalor',
        'customedio',
        'codestoquelocalprodutovariacao',
        'ultimaconferencia',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
        'ultimaconferencia',
    ];
    
    // Chaves Estrangeiras
    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuario', 'codusuarioalteracao');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuario', 'codusuariocriacao');
    }

    public function EstoqueLocalProdutoVariacao()
    {
        return $this->belongsTo(EstoqueLocalProdutoVariacao::class, 'codestoquelocalprodutovariacao', 'codestoquelocalprodutovariacao');
    }


    // Tabelas Filhas
    public function EstoqueMesS()
    {
        return $this->hasMany(EstoqueMes::class, 'codestoquesaldo', 'codestoquesaldo');
    }
    
    public function EstoqueSaldoConferenciaS()
    {
        return $this->hasMany(EstoqueSaldoConferencia::class, 'codestoquesaldo', 'codestoquesaldo');
    }
    
    public static function buscaOuCria($codprodutovariacao, $codestoquelocal, $fiscal)
    {
        $elpv = EstoqueLocalProdutoVariacao::buscaOuCria($codprodutovariacao, $codestoquelocal);

        $es = self::where('codestoquelocalprodutovariacao', $elpv->codestoquelocalprodutovariacao)->where('fiscal', $fiscal)->first();
        if ($es == false)
        {
            $es = new EstoqueSaldo;
            $es->codestoquelocalprodutovariacao = $elpv->codestoquelocalprodutovariacao;
            $es->fiscal = $fiscal;
            $es->save();
        }
        return $es;
    }
    
    public static function totais($agrupamento, $filtro = [])
    {
        //$query = DB::table('tblestoquesaldo');
        $query = DB::table('tblestoquelocalprodutovariacao');

        if ($agrupamento != 'variacao') {
            $query->groupBy('fiscal');
            $query->groupBy('tblestoquelocal.codestoquelocal');
            $query->groupBy('tblestoquelocal.estoquelocal');
        }
        
        $query->join('tblestoquelocal', 'tblestoquelocal.codestoquelocal', '=', 'tblestoquelocalprodutovariacao.codestoquelocal');
        $query->join('tblprodutovariacao', 'tblprodutovariacao.codprodutovariacao', '=', 'tblestoquelocalprodutovariacao.codprodutovariacao');
        $query->join('tblproduto', 'tblproduto.codproduto', '=', 'tblprodutovariacao.codproduto');
        $query->leftJoin('tblestoquesaldo', 'tblestoquesaldo.codestoquelocalprodutovariacao', '=', 'tblestoquelocalprodutovariacao.codestoquelocalprodutovariacao');
        $query->leftJoin('tblsubgrupoproduto', 'tblsubgrupoproduto.codsubgrupoproduto', '=', 'tblproduto.codsubgrupoproduto');
        $query->leftJoin('tblgrupoproduto', 'tblgrupoproduto.codgrupoproduto', '=', 'tblsubgrupoproduto.codgrupoproduto');
        $query->leftJoin('tblfamiliaproduto', 'tblfamiliaproduto.codfamiliaproduto', '=', 'tblgrupoproduto.codfamiliaproduto');
        
        
        switch ($agrupamento) {
            case 'variacao':
                $query->select(
                    DB::raw(
                        ' saldoquantidade
                        , saldovalor
                        , estoqueminimo
                        , estoquemaximo
                        , fiscal
                        , tblprodutovariacao.codprodutovariacao as coditem
                        , tblproduto.produto || \' » \' || coalesce(tblprodutovariacao.variacao, \'{ Sem Variação }\') as item
                        , tblestoquelocal.codestoquelocal
                        , tblestoquelocal.estoquelocal
                        , tblestoquesaldo.codestoquesaldo
                        '
                    )
                );
                $query->orderBy('tblproduto.produto');
                $query->orderBy('variacao');
                break;
            
            case 'produto':
                $query->select(
                    DB::raw(
                        ' sum(saldoquantidade) as saldoquantidade
                        , sum(saldovalor) as saldovalor
                        , sum(estoqueminimo) as estoqueminimo
                        , sum(estoquemaximo) as estoquemaximo
                        , fiscal
                        , tblproduto.codproduto as coditem
                        , tblproduto.produto as item
                        , tblestoquelocal.codestoquelocal
                        , tblestoquelocal.estoquelocal
                        '
                    )
                );
                $query->groupBy('tblproduto.codproduto');
                $query->groupBy('tblproduto.produto');
                $query->orderBy('produto');
                break;
            
            case 'marca':
                $query->select(
                    DB::raw(
                        ' sum(saldoquantidade) as saldoquantidade
                        , sum(saldovalor) as saldovalor
                        , sum(estoqueminimo) as estoqueminimo
                        , sum(estoquemaximo) as estoquemaximo
                        , fiscal
                        , tblmarca.codmarca as coditem
                        , tblmarca.marca as item
                        , tblestoquelocal.codestoquelocal
                        , tblestoquelocal.estoquelocal
                        '
                    )
                );
                $query->leftJoin('tblmarca', 'tblmarca.codmarca', '=', 'tblproduto.codmarca');
                $query->groupBy('tblmarca.codmarca');
                $query->groupBy('tblmarca.marca');
                $query->orderBy('marca');
                break;
            
            case 'subgrupoproduto':
                $query->select(
                    DB::raw(
                        ' sum(saldoquantidade) as saldoquantidade
                        , sum(saldovalor) as saldovalor
                        , sum(estoqueminimo) as estoqueminimo
                        , sum(estoquemaximo) as estoquemaximo
                        , fiscal
                        , tblsubgrupoproduto.codsubgrupoproduto as coditem
                        , tblsubgrupoproduto.subgrupoproduto as item
                        , tblestoquelocal.codestoquelocal
                        , tblestoquelocal.estoquelocal
                        '
                    )
                );
                $query->groupBy('tblsubgrupoproduto.codsubgrupoproduto');
                $query->groupBy('tblsubgrupoproduto.subgrupoproduto');
                $query->orderBy('subgrupoproduto');
                break;
            
            case 'grupoproduto':
                $query->select(
                    DB::raw(
                        ' sum(saldoquantidade) as saldoquantidade
                        , sum(saldovalor) as saldovalor
                        , sum(estoqueminimo) as estoqueminimo
                        , sum(estoquemaximo) as estoquemaximo
                        , fiscal
                        , tblgrupoproduto.codgrupoproduto as coditem
                        , tblgrupoproduto.grupoproduto as item
                        , tblestoquelocal.codestoquelocal
                        , tblestoquelocal.estoquelocal
                        '
                    )
                );
                $query->groupBy('tblgrupoproduto.codgrupoproduto');
                $query->groupBy('tblgrupoproduto.grupoproduto');
                $query->orderBy('grupoproduto');
                break;
            
            case 'familiaproduto':
                $query->select(
                    DB::raw(
                        ' sum(saldoquantidade) as saldoquantidade
                        , sum(saldovalor) as saldovalor
                        , sum(estoqueminimo) as estoqueminimo
                        , sum(estoquemaximo) as estoquemaximo
                        , fiscal
                        , tblfamiliaproduto.codfamiliaproduto as coditem
                        , tblfamiliaproduto.familiaproduto as item
                        , tblestoquelocal.codestoquelocal
                        , tblestoquelocal.estoquelocal
                        '
                    )
                );
                $query->groupBy('tblfamiliaproduto.codfamiliaproduto');
                $query->groupBy('tblfamiliaproduto.familiaproduto');
                $query->orderBy('familiaproduto');
                break;
            
            case 'secaoproduto':
            default:
                $query->select(
                    DB::raw(
                        ' sum(saldoquantidade) as saldoquantidade
                        , sum(saldovalor) as saldovalor
                        , sum(estoqueminimo) as estoqueminimo
                        , sum(estoquemaximo) as estoquemaximo
                        , fiscal
                        , tblsecaoproduto.codsecaoproduto as coditem
                        , tblsecaoproduto.secaoproduto as item
                        , tblestoquelocal.codestoquelocal
                        , tblestoquelocal.estoquelocal
                        '
                    )
                );
                $query->groupBy('tblsecaoproduto.codsecaoproduto');
                $query->groupBy('tblsecaoproduto.secaoproduto');
                $query->leftJoin('tblsecaoproduto', 'tblsecaoproduto.codsecaoproduto', '=', 'tblfamiliaproduto.codsecaoproduto');
                $query->orderBy('secaoproduto');
                break;
        }
        
        $query->orderBy('tblestoquelocal.codestoquelocal');
        
        if (!empty($filtro['codsecaoproduto'])) {
            $query->where('tblfamiliaproduto.codsecaoproduto', '=', $filtro['codsecaoproduto']);
        }
        
        if (!empty($filtro['codestoquelocal'])) {
            $query->where('tblestoquelocalprodutovariacao.codestoquelocal', '=', $filtro['codestoquelocal']);
        }

        if (!empty($filtro['codfamiliaproduto'])) {
            $query->where('tblgrupoproduto.codfamiliaproduto', '=', $filtro['codfamiliaproduto']);
        }

        if (!empty($filtro['codproduto'])) {
            $query->where('tblprodutovariacao.codproduto', '=', $filtro['codproduto']);
        }

        if (!empty($filtro['codprodutovariacao'])) {
            $query->where('tblestoquelocalprodutovariacao.codprodutovariacao', '=', $filtro['codprodutovariacao']);
        }

        if (!empty($filtro['codgrupoproduto'])) {
            $query->where('tblsubgrupoproduto.codgrupoproduto', '=', $filtro['codgrupoproduto']);
        }

        if (!empty($filtro['codsubgrupoproduto'])) {
            $query->where('tblproduto.codsubgrupoproduto', '=', $filtro['codsubgrupoproduto']);
        }

        if (!empty($filtro['corredor'])) {
            $query->where('tblestoquelocalprodutovariacao.corredor', '=', $filtro['corredor']);
        }

        if (!empty($filtro['prateleira'])) {
            $query->where('tblestoquelocalprodutovariacao.prateleira', '=', $filtro['prateleira']);
        }

        if (!empty($filtro['coluna'])) {
            $query->where('tblestoquelocalprodutovariacao.coluna', '=', $filtro['coluna']);
        }

        if (!empty($filtro['bloco'])) {
            $query->where('tblestoquelocalprodutovariacao.bloco', '=', $filtro['bloco']);
        }

        if (!empty($filtro['codmarca'])) {
            $query->where(function ($q2) use($filtro) {
                $q2->orWhere('tblproduto.codmarca', '=', $filtro['codmarca']);
                $q2->orWhere('tblprodutovariacao.codmarca', '=', $filtro['codmarca']);                        
            });
        }

        if (!empty($filtro['saldo']) || !empty($filtro['minimo']) || !empty($filtro['maximo'])) {
            
            $query->whereIn('tblestoquesaldo.codestoquelocalprodutovariacao', function($q2) use ($filtro){
                
                $q2->select('tblestoquesaldo.codestoquelocalprodutovariacao')
                    ->from('tblestoquesaldo')
                    ->join('tblestoquelocalprodutovariacao', 'tblestoquelocalprodutovariacao.codestoquelocalprodutovariacao', '=', 'tblestoquesaldo.codestoquelocalprodutovariacao')
                    ->whereRaw('fiscal = false');
                
                if (!empty($filtro['minimo'])) {
                    if ($filtro['minimo'] == -1) {
                        $q2->whereRaw('saldoquantidade < coalesce(estoqueminimo, 0)');
                    } else if ($filtro['minimo'] == 1) {
                        $q2->whereRaw('saldoquantidade >= coalesce(estoqueminimo, 0)');
                        
                    }
                }
                
                if (!empty($filtro['maximo'])) {
                    if ($filtro['maximo'] == -1) {
                        $q2->whereRaw('saldoquantidade <= coalesce(estoquemaximo, 99999999999999999999)');
                    } else if ($filtro['maximo'] == 1) {
                        $q2->whereRaw('saldoquantidade > coalesce(estoquemaximo, 99999999999999999999)');
                    }
                }
                
                if (!empty($filtro['saldo'])) {
                    if ($filtro['saldo'] == -1) {
                        $q2->whereRaw('saldoquantidade < 0');
                    } else if ($filtro['saldo'] == 1) {
                        $q2->whereRaw('saldoquantidade > 0');
                        
                    }
                }
                
            });
        }

        $query->whereRaw('tblestoquesaldo.saldoquantidade != 0');
        
        $rows = $query->get();
        
        $ret = [];
        
        $total = [
            'coditem' => null,
            'item' => null,
            'estoquelocal' => [
                'total' => [
                    'estoqueminimo' => null,
                    'estoquemaximo' => null,
                    'fisico' => [
                        'saldoquantidade' => null,
                        'saldovalor' => null,
                    ],
                    'fiscal' => [
                        'saldoquantidade' => null,
                        'saldovalor' => null,
                    ]                    
                ]
            ]
        ];
                
        foreach($rows as $row) {

            if (!isset($ret[$row->coditem])) {
                $ret[$row->coditem] = [
                    'coditem' => $row->coditem,
                    'item' => $row->item,
                    'estoquelocal' => [
                        'total' => [
                            'estoqueminimo' => null,
                            'estoquemaximo' => null,                            
                            'fisico' => [
                                'saldoquantidade' => null,
                                'saldovalor' => null,
                            ],
                            'fiscal' => [
                                'saldoquantidade' => null,
                                'saldovalor' => null,
                            ]
                        ]
                    ]
                ];
            }

            if (!isset($ret[$row->coditem]['estoquelocal'][$row->codestoquelocal])) {
                $ret[$row->coditem]['estoquelocal'][$row->codestoquelocal] = [
                    'codestoquelocal' => $row->codestoquelocal,
                    'estoquelocal' => $row->estoquelocal,
                    'estoqueminimo' => null,
                    'estoquemaximo' => null,
                    'fisico' => [
                        'saldoquantidade' => null,
                        'saldovalor' => null,
                    ],
                    'fiscal' => [
                        'saldoquantidade' => null,
                        'saldovalor' => null,
                    ]
                ];
            }
            
            if (!isset($total['estoquelocal'][$row->codestoquelocal])) {
                $total['estoquelocal'][$row->codestoquelocal] = [
                    'codestoquelocal' => $row->codestoquelocal,
                    'estoquelocal' => $row->estoquelocal,
                    'estoqueminimo' => null,
                    'estoquemaximo' => null,
                    'fisico' => [
                        'saldoquantidade' => null,
                        'saldovalor' => null,
                    ],
                    'fiscal' => [
                        'saldoquantidade' => null,
                        'saldovalor' => null,
                    ]                    
                ];
            }
            
            if  (empty($ret[$row->coditem]['estoquelocal'][$row->codestoquelocal]['estoqueminimo'])) {
                $ret[$row->coditem]['estoquelocal'][$row->codestoquelocal]['estoqueminimo'] = $row->estoqueminimo;
                $ret[$row->coditem]['estoquelocal']['total']['estoqueminimo'] += $row->estoqueminimo;
                $total['estoquelocal'][$row->codestoquelocal]['estoqueminimo'] += $row->estoqueminimo;
                $total['estoquelocal']['total']['estoqueminimo'] += $row->estoqueminimo;
            }
            
            if  (empty($ret[$row->coditem]['estoquelocal'][$row->codestoquelocal]['estoquemaximo'])) {
                $ret[$row->coditem]['estoquelocal'][$row->codestoquelocal]['estoquemaximo'] = $row->estoquemaximo;
                $ret[$row->coditem]['estoquelocal']['total']['estoquemaximo'] += $row->estoquemaximo;
                $total['estoquelocal'][$row->codestoquelocal]['estoquemaximo'] += $row->estoquemaximo;
                $total['estoquelocal']['total']['estoquemaximo'] += $row->estoquemaximo;
            }
            
            $fiscal = ($row->fiscal)?'fiscal':'fisico';
            
            $ret[$row->coditem]['estoquelocal'][$row->codestoquelocal][$fiscal]['saldoquantidade'] = $row->saldoquantidade;
            $ret[$row->coditem]['estoquelocal'][$row->codestoquelocal][$fiscal]['saldovalor'] = $row->saldovalor;
            
            if (!empty($row->codestoquesaldo)) {
                $ret[$row->coditem]['estoquelocal'][$row->codestoquelocal][$fiscal]['codestoquesaldo'] = $row->codestoquesaldo;
            }
            
            $ret[$row->coditem]['estoquelocal']['total'][$fiscal]['saldoquantidade'] += $row->saldoquantidade;
            $ret[$row->coditem]['estoquelocal']['total'][$fiscal]['saldovalor'] += $row->saldovalor;
            
            $total['estoquelocal'][$row->codestoquelocal][$fiscal]['saldoquantidade'] += $row->saldoquantidade;
            $total['estoquelocal'][$row->codestoquelocal][$fiscal]['saldovalor'] += $row->saldovalor;

            $total['estoquelocal']['total'][$fiscal]['saldoquantidade'] += $row->saldoquantidade;
            $total['estoquelocal']['total'][$fiscal]['saldovalor'] += $row->saldovalor;

        }
        
        $ret['total'] = $total;
        
        return $ret;
    }
}
