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
 * @property  numeric(14,3)                  $saldoquantidade                    
 * @property  numeric(14,2)                  $saldovalor                         
 * @property  numeric(14,6)                  $customedio                 
 * @property  timestamp                      $dataentrada
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
        'dataentrada',
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
    
    public static function totais($agrupamento, $valor = 'custo', $filtro = [])
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
                        , ' . (($valor=='venda')?'saldoquantidade * tblproduto.preco':'saldovalor') . ' as saldovalor
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
                        , sum(' . (($valor=='venda')?'saldoquantidade * tblproduto.preco':'saldovalor') . ') as saldovalor
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
                        , sum(' . (($valor=='venda')?'saldoquantidade * tblproduto.preco':'saldovalor') . ') as saldovalor
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
                        , sum(' . (($valor=='venda')?'saldoquantidade * tblproduto.preco':'saldovalor') . ') as saldovalor
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
                        , sum(' . (($valor=='venda')?'saldoquantidade * tblproduto.preco':'saldovalor') . ') as saldovalor
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
                        , sum(' . (($valor=='venda')?'saldoquantidade * tblproduto.preco':'saldovalor') . ') as saldovalor
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
                        , sum(' . (($valor=='venda')?'saldoquantidade * tblproduto.preco':'saldovalor') . ') as saldovalor
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
                        $q2->whereRaw('saldoquantidade < estoqueminimo');
                    } else if ($filtro['minimo'] == 1) {
                        $q2->whereRaw('saldoquantidade >= estoqueminimo');
                        
                    }
                }
                
                if (!empty($filtro['maximo'])) {
                    if ($filtro['maximo'] == -1) {
                        $q2->whereRaw('saldoquantidade <= estoquemaximo');
                    } else if ($filtro['maximo'] == 1) {
                        $q2->whereRaw('saldoquantidade > estoquemaximo');
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
    
    private static function relatorioAnaliseTotaliza($arr, $arritens) 
    {
        
    }
    
    public static function relatorioAnalise($agrupamento, $filtro) 
    {
        $camposVenda = [
            'vendabimestre' => new Carbon('-2 months'),
            'vendasemestre' => new Carbon('-6 months'),
            'vendaano' =>  new Carbon('-1 year'),
        ];
        
        $diasSemestre = $camposVenda['vendasemestre']->diffInDays();
        
        $qProd = Produto::query();
        
        $qProd->select([
            'tblproduto.codproduto', 
            'tblproduto.produto',
            'tblproduto.inativo',
            'tblproduto.preco',
            'tblproduto.referencia',
            'tblproduto.codunidademedida',
            'tblunidademedida.sigla',
            'tblproduto.codmarca',
            'tblmarca.marca',
            'tblsubgrupoproduto.codsubgrupoproduto',
            'tblsubgrupoproduto.subgrupoproduto',
            'tblgrupoproduto.codgrupoproduto',
            'tblgrupoproduto.grupoproduto',
            'tblfamiliaproduto.codfamiliaproduto',
            'tblfamiliaproduto.familiaproduto',
            'tblsecaoproduto.codsecaoproduto',
            'tblsecaoproduto.secaoproduto',
        ]);
        
        $qProd->leftJoin('tblsubgrupoproduto', 'tblsubgrupoproduto.codsubgrupoproduto', '=', 'tblproduto.codsubgrupoproduto');
        $qProd->leftJoin('tblgrupoproduto', 'tblgrupoproduto.codgrupoproduto', '=', 'tblsubgrupoproduto.codgrupoproduto');
        $qProd->leftJoin('tblfamiliaproduto', 'tblfamiliaproduto.codfamiliaproduto', '=', 'tblgrupoproduto.codfamiliaproduto');
        $qProd->leftJoin('tblsecaoproduto', 'tblsecaoproduto.codsecaoproduto', '=', 'tblfamiliaproduto.codsecaoproduto');
        $qProd->leftJoin('tblmarca', 'tblmarca.codmarca', '=', 'tblproduto.codmarca');
        $qProd->leftJoin('tblunidademedida', 'tblunidademedida.codunidademedida', '=', 'tblproduto.codunidademedida');
        
        if (!empty($filtro['codproduto'])) {
            $qProd->where('tblproduto.codproduto', $filtro['codproduto']);
        }
        
        if (!empty($filtro['codmarca'])) {
            $qProd->where(function ($q2) use($filtro) {
                $q2->orWhere('tblproduto.codmarca', '=', $filtro['codmarca']);
                $q2->orWhereIn('tblproduto.codproduto', function ($q3) use ($filtro) {
                    $q3->select('codproduto')
                        ->from('tblprodutovariacao')
                        ->where('codmarca', '=', $filtro['codmarca']);
                });
            });
        }
        
        if (!empty($filtro['codsubgrupoproduto'])) {
            $qProd->where('tblproduto.codsubgrupoproduto', $filtro['codsubgrupoproduto']);
        }
        
        if (!empty($filtro['codgrupoproduto'])) {
            $qProd->where('tblsubgrupoproduto.codgrupoproduto', $filtro['codgrupoproduto']);
        }
        
        if (!empty($filtro['codfamiliaproduto'])) {
            $qProd->where('tblgrupoproduto.codfamiliaproduto', $filtro['codfamiliaproduto']);
        }
        
        if (!empty($filtro['codsecaoproduto'])) {
            $qProd->where('tblfamiliaproduto.codsecaoproduto', $filtro['codsecaoproduto']);
        }
        
        if (empty($filtro['fiscal'])) {
            $filtro['fiscal'] = false;
        }
        
        switch (isset($filtro['ativo'])?$filtro['ativo']:'9') {
            case 1: //Ativos
                $qProd->whereNull('tblproduto.inativo');
                break;
            case 2: //Inativos
                $qProd->whereNotNull('tblproduto.inativo');
                break;
            case 9; //Todos
            default:
        }
        
        if (!empty($filtro['corredor'])
            || !empty($filtro['prateleira'])
            || !empty($filtro['coluna'])
            || !empty($filtro['bloco'])
            || !empty($filtro['minimo'])
            || !empty($filtro['maximo'])
            || !empty($filtro['saldo'])
            ) {
            $qProd->whereIn('tblproduto.codproduto', function ($q2) use ($filtro) {
                
                $q2->select('tblprodutovariacao.codproduto');
                $q2->from('tblprodutovariacao');
                $q2->leftJoin('tblestoquelocalprodutovariacao', 'tblestoquelocalprodutovariacao.codprodutovariacao', '=', 'tblprodutovariacao.codprodutovariacao');
                $q2->leftJoin('tblestoquesaldo', function ($join) use ($filtro) {
                    $join->on('tblestoquesaldo.codestoquelocalprodutovariacao', '=', 'tblestoquelocalprodutovariacao.codestoquelocalprodutovariacao');
                    $join->on('tblestoquesaldo.fiscal', '=', DB::raw($filtro['fiscal']?'true':'false'));
                });
                
                if (!empty($filtro['codestoquelocal'])) {
                    $q2->where('tblestoquelocalprodutovariacao.codestoquelocal', '=', $filtro['codestoquelocal']);
                }

                if (!empty($filtro['corredor'])) {
                    $q2->where('tblestoquelocalprodutovariacao.corredor', '=', $filtro['corredor']);
                }

                if (!empty($filtro['prateleira'])) {
                    $q2->where('tblestoquelocalprodutovariacao.prateleira', '=', $filtro['prateleira']);
                }

                if (!empty($filtro['coluna'])) {
                    $q2->where('tblestoquelocalprodutovariacao.coluna', '=', $filtro['coluna']);
                }

                if (!empty($filtro['bloco'])) {
                    $q2->where('tblestoquelocalprodutovariacao.bloco', '=', $filtro['bloco']);
                }

                if (!empty($filtro['minimo'])) {
                    if ($filtro['minimo'] == -1) {
                        $q2->whereRaw('saldoquantidade < estoqueminimo');
                    } else if ($filtro['minimo'] == 1) {
                        $q2->whereRaw('saldoquantidade >= estoqueminimo');

                    }
                }

                if (!empty($filtro['maximo'])) {
                    if ($filtro['maximo'] == -1) {
                        $q2->whereRaw('saldoquantidade <= estoquemaximo');
                    } else if ($filtro['maximo'] == 1) {
                        $q2->whereRaw('saldoquantidade > estoquemaximo');
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
        
        
        $qProd->orderBy('tblsecaoproduto.secaoproduto', 'ASC');
        $qProd->orderBy('tblfamiliaproduto.familiaproduto', 'ASC');
        $qProd->orderBy('tblgrupoproduto.grupoproduto', 'ASC');
        $qProd->orderBy('tblsubgrupoproduto.subgrupoproduto', 'ASC');
        $qProd->orderBy('tblproduto.produto', 'ASC');

        $itens = [];
        
        //dd($qProd->toSql());
        
        foreach ($qProd->get() as $prod) {
            $retProd = [
                'codproduto' => $prod->codproduto,
                'produto' => $prod->produto,
                'codmarca' => $prod->codmarca,
                'marca' => $prod->marca,
                'preco' => $prod->preco,
                'codunidademedida' => $prod->codunidademedida,
                'sigla' => $prod->sigla,
                'referencia' => $prod->referencia,

                'codsubgrupoproduto' => $prod->codsubgrupoproduto,
                'subgrupoproduto' => $prod->subgrupoproduto,
                'codgrupoproduto' => $prod->codgrupoproduto,
                'grupoproduto' => $prod->grupoproduto,
                'codfamiliaproduto' => $prod->codfamiliaproduto,
                'familiaproduto' => $prod->familiaproduto,
                'codsecaoproduto' => $prod->codsecaoproduto,
                'secaoproduto' => $prod->secaoproduto,
                
                'quantidadecompra' => null,
                'custocompra' => null,
                'valortotalcompra' => null,

                'estoqueminimo' => null,
                'estoquemaximo' => null,
                'saldoquantidade' => null,
                'saldodias' => null,
                
                'saldovalor' => null,
                'customedio' => null,
                'vendaprevisaoquinzena' => null,
                'vendabimestre' => null,
                'vendasemestre' => null,
                'vendaano' => null,
                //'vendabienio' => null,
                
                'variacoes' => [],
            ];
            
            $qVar = $prod->ProdutoVariacaoS();
                
            if (!empty($filtro['codmarca'])) {
                $qVar->where(function ($iq) use ($filtro) {
                    $iq->orWhereNull('tblprodutovariacao.codmarca');
                    $iq->orWhere('tblprodutovariacao.codmarca', '=', $filtro['codmarca']);
                });
            }
            
            if (!empty($filtro['corredor'])
                || !empty($filtro['prateleira'])
                || !empty($filtro['coluna'])
                || !empty($filtro['bloco'])
                || !empty($filtro['minimo'])
                || !empty($filtro['maximo'])
                || !empty($filtro['saldo'])
                ) {
                
                $qVar->whereIn('tblprodutovariacao.codprodutovariacao', function ($q2) use ($filtro) {

                    $q2->select('tblestoquelocalprodutovariacao.codprodutovariacao');
                    $q2->from('tblestoquelocalprodutovariacao');
                    $q2->leftJoin('tblestoquesaldo', function ($join) use ($filtro) {
                        $join->on('tblestoquesaldo.codestoquelocalprodutovariacao', '=', 'tblestoquelocalprodutovariacao.codestoquelocalprodutovariacao');
                        $join->on('tblestoquesaldo.fiscal', '=', DB::raw($filtro['fiscal']?'true':'false'));
                    });

                    if (!empty($filtro['codestoquelocal'])) {
                        $q2->where('tblestoquelocalprodutovariacao.codestoquelocal', '=', $filtro['codestoquelocal']);
                    }

                    if (!empty($filtro['corredor'])) {
                        $q2->where('tblestoquelocalprodutovariacao.corredor', '=', $filtro['corredor']);
                    }

                    if (!empty($filtro['prateleira'])) {
                        $q2->where('tblestoquelocalprodutovariacao.prateleira', '=', $filtro['prateleira']);
                    }

                    if (!empty($filtro['coluna'])) {
                        $q2->where('tblestoquelocalprodutovariacao.coluna', '=', $filtro['coluna']);
                    }

                    if (!empty($filtro['bloco'])) {
                        $q2->where('tblestoquelocalprodutovariacao.bloco', '=', $filtro['bloco']);
                    }

                    if (!empty($filtro['minimo'])) {
                        if ($filtro['minimo'] == -1) {
                            $q2->whereRaw('saldoquantidade < estoqueminimo');
                        } else if ($filtro['minimo'] == 1) {
                            $q2->whereRaw('saldoquantidade >= estoqueminimo');

                        }
                    }

                    if (!empty($filtro['maximo'])) {
                        if ($filtro['maximo'] == -1) {
                            $q2->whereRaw('saldoquantidade <= estoquemaximo');
                        } else if ($filtro['maximo'] == 1) {
                            $q2->whereRaw('saldoquantidade > estoquemaximo');
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

            //dd($qVar->toSql());
            foreach ($qVar->get() as $var) {
                
                $retVar = [
                    'codprodutovariacao' => $var->codprodutovariacao,
                    'variacao' => empty($var->variacao)?'{ Sem Variação }':$var->variacao,
                    'referencia' => $var->referencia,
                    'ultimacompra' => null,
                    
                    'quantidadecompra' => null,
                    'custocompra' => null,
                    'valortotalcompra' => null,
                    
                    'estoqueminimo' => null,
                    'estoquemaximo' => null,
                    'saldoquantidade' => null,
                    'saldodias' => null,
                    
                    'saldovalor' => null,
                    'customedio' => null,
                    'vendaprevisaoquinzena' => null,
                    'vendabimestre' => null,
                    'vendasemestre' => null,
                    'vendaano' => null,
                    //'vendabienio' => null,
                    
                    'locais' => [],
                ];
                
                $qCompra = DB::table('tblprodutobarra');
                $qCompra->selectRaw('tblnotafiscal.emissao, sum(tblnotafiscalprodutobarra.quantidade * coalesce(tblprodutoembalagem.quantidade, 1)) as quantidade, sum(tblnotafiscalprodutobarra.valortotal) as valortotal');
                $qCompra->leftJoin('tblprodutoembalagem', 'tblprodutoembalagem.codprodutoembalagem', '=', 'tblprodutobarra.codprodutoembalagem');
                $qCompra->join('tblnotafiscalprodutobarra', 'tblnotafiscalprodutobarra.codprodutobarra', '=', 'tblprodutobarra.codprodutobarra');
                $qCompra->join('tblnotafiscal', 'tblnotafiscal.codnotafiscal', '=', 'tblnotafiscalprodutobarra.codnotafiscal');
                $qCompra->join('tblnaturezaoperacao', 'tblnaturezaoperacao.codnaturezaoperacao', '=', 'tblnotafiscal.codnaturezaoperacao');
                $qCompra->where('tblprodutobarra.codprodutovariacao', '=', $var->codprodutovariacao);
                $qCompra->where('tblnaturezaoperacao.compra', '=', true);
                $qCompra->groupBy('tblnotafiscal.emissao');
                $qCompra->orderBy('tblnotafiscal.emissao', 'DESC');
                if ($compra = $qCompra->first()) {
                    $retVar['ultimacompra'] = new Carbon($compra->emissao);
                    $retVar['quantidadecompra'] = $compra->quantidade;
                    $retVar['valortotalcompra'] = $compra->valortotal;
                    $retVar['custocompra'] = $compra->valortotal;
                    if ($compra->quantidade > 0) {
                        $retVar['custocompra'] /= $compra->quantidade;
                    }
                }
                
                $qSaldo = DB::table('tblestoquelocal');
                $qSaldo->selectRaw('
                    tblestoquelocal.codestoquelocal,
                    tblestoquelocal.sigla,
                    tblestoquelocalprodutovariacao.corredor,
                    tblestoquelocalprodutovariacao.prateleira,
                    tblestoquelocalprodutovariacao.coluna,
                    tblestoquelocalprodutovariacao.bloco,
                    tblestoquelocalprodutovariacao.estoqueminimo,
                    tblestoquelocalprodutovariacao.estoquemaximo,
                    tblestoquesaldo.codestoquesaldo,
                    tblestoquesaldo.dataentrada,
                    tblestoquesaldo.saldoquantidade,
                    tblestoquesaldo.saldovalor,
                    tblestoquesaldo.customedio
                ');
                $qSaldo->leftJoin('tblestoquelocalprodutovariacao', function($join) use ($var) {
                    $join->on('tblestoquelocalprodutovariacao.codestoquelocal', '=', 'tblestoquelocal.codestoquelocal');
                    $join->on('tblestoquelocalprodutovariacao.codprodutovariacao', '=', DB::raw($var->codprodutovariacao));
                });
                $qSaldo->leftJoin('tblestoquesaldo', function ($join) use ($filtro) {
                    $join->on('tblestoquesaldo.codestoquelocalprodutovariacao', '=', 'tblestoquelocalprodutovariacao.codestoquelocalprodutovariacao');
                    $join->on('tblestoquesaldo.fiscal', '=', DB::raw($filtro['fiscal']?'true':'false'));
                });
                $qSaldo->whereNull('tblestoquelocal.inativo');
                $qSaldo->orderBy('tblestoquelocal.codestoquelocal');
                
                if (!empty($filtro['codestoquelocal'])) {
                    $qSaldo->where('tblestoquelocal.codestoquelocal', '=', $filtro['codestoquelocal']);
                }
                
                if (!empty($filtro['corredor'])) {
                    $qSaldo->where('tblestoquelocalprodutovariacao.corredor', '=', $filtro['corredor']);
                }

                if (!empty($filtro['prateleira'])) {
                    $qSaldo->where('tblestoquelocalprodutovariacao.prateleira', '=', $filtro['prateleira']);
                }

                if (!empty($filtro['coluna'])) {
                    $qSaldo->where('tblestoquelocalprodutovariacao.coluna', '=', $filtro['coluna']);
                }

                if (!empty($filtro['bloco'])) {
                    $qSaldo->where('tblestoquelocalprodutovariacao.bloco', '=', $filtro['bloco']);
                }
                
                if (!empty($filtro['minimo'])) {
                    if ($filtro['minimo'] == -1) {
                        $qSaldo->whereRaw('saldoquantidade < estoqueminimo');
                    } else if ($filtro['minimo'] == 1) {
                        $qSaldo->whereRaw('saldoquantidade >= estoqueminimo');
                        
                    }
                }
                
                if (!empty($filtro['maximo'])) {
                    if ($filtro['maximo'] == -1) {
                        $qSaldo->whereRaw('saldoquantidade <= estoquemaximo');
                    } else if ($filtro['maximo'] == 1) {
                        $qSaldo->whereRaw('saldoquantidade > estoquemaximo');
                    }
                }
                
                if (!empty($filtro['saldo'])) {
                    if ($filtro['saldo'] == -1) {
                        $qSaldo->whereRaw('saldoquantidade < 0');
                    } else if ($filtro['saldo'] == 1) {
                        $qSaldo->whereRaw('saldoquantidade > 0');
                        
                    }
                }
                

                foreach ($qSaldo->get() as $sld) {
                    
                    $retLocal = [
                        'codestoquelocal' => $sld->codestoquelocal,
                        'estoquelocal' => $sld->sigla,
                        'corredor' => $sld->corredor,
                        'prateleira' => $sld->prateleira,
                        'coluna' => $sld->coluna,
                        'bloco' => $sld->bloco,
                        'codestoquesaldo' => $sld->codestoquesaldo,
                        'dataentrada' => empty($sld->dataentrada)?null:new Carbon($sld->dataentrada),
                        'estoqueminimo' => $sld->estoqueminimo,
                        'estoquemaximo' => $sld->estoquemaximo,
                        'saldoquantidade' => $sld->saldoquantidade,
                        'saldodias' => null,
                        'saldovalor' => $sld->saldovalor,
                        'customedio' => $sld->customedio,
                        'vendaprevisaoquinzena' => null,
                        'vendabimestre' => null,
                        'vendasemestre' => null,
                        'vendaano' => null,
                        //'vendabienio' => null,
                    ];
                    
                    // Calcula vendas do Bimestre/Semestre/Ano/UltimaCompra
                    foreach ($camposVenda as $campo => $data) {
                        $qVenda = DB::table('tblnegocioprodutobarra');
                        $qVenda->selectRaw('sum(tblnegocioprodutobarra.quantidade * coalesce(tblprodutoembalagem.quantidade, 1)) as quantidade');
                        $qVenda->join('tblnegocio', 'tblnegocio.codnegocio', '=', 'tblnegocioprodutobarra.codnegocio');
                        $qVenda->join('tblprodutobarra', 'tblprodutobarra.codprodutobarra', '=', 'tblnegocioprodutobarra.codprodutobarra');
                        $qVenda->join('tblnaturezaoperacao', 'tblnaturezaoperacao.codnaturezaoperacao', '=', 'tblnegocio.codnaturezaoperacao');
                        $qVenda->leftJoin('tblprodutoembalagem', 'tblprodutoembalagem.codprodutoembalagem', '=', 'tblprodutobarra.codprodutoembalagem');
                        $qVenda->where('tblnegocio.codestoquelocal', '=', $retLocal['codestoquelocal']);
                        $qVenda->where('tblnegocio.codnegociostatus', '=', NegocioStatus::FECHADO);
                        $qVenda->where('tblprodutobarra.codprodutovariacao', '=', $retVar['codprodutovariacao']);
                        $qVenda->where('tblnegocio.lancamento', '>=', $data);
                        $qVenda->where('tblnaturezaoperacao.venda', '=', true);
                        if ($venda = $qVenda->get()) {
                            $retLocal[$campo] = $venda[0]->quantidade;
                        }
                    }
                    
                    // Calcula para quantos dias dura o estoque, baseado na venda do semestre
                    if (!empty($retLocal['vendasemestre'])) {
                        $retLocal['saldodias'] = floor($retLocal['saldoquantidade'] / ($retLocal['vendasemestre'] / $diasSemestre));
                    }
                    
                    // Calcula previsao de venda para quinzena, baseado na venda do semestre
                    if (!empty($retLocal['vendasemestre'])) {
                        $retLocal['vendaprevisaoquinzena'] = ceil(15 * ($retLocal['vendasemestre'] / $diasSemestre));
                    }
                    
                    if (!empty($retLocal['vendabimestre'])
                            || !empty($retLocal['vendasemestre'])
                            || !empty($retLocal['vendaano'])
                            || !empty($retLocal['saldoquantidade'])
                    ) {
                        $retVar['locais'][$sld->codestoquelocal] = $retLocal;
                    }
                }
                
                //Totaliza Locais
                $retVar['estoqueminimo'] = array_sum(array_column($retVar['locais'], 'estoqueminimo'));
                $retVar['estoquemaximo'] = array_sum(array_column($retVar['locais'], 'estoquemaximo'));
                $retVar['saldoquantidade'] = array_sum(array_column($retVar['locais'], 'saldoquantidade'));
                $retVar['saldovalor'] = array_sum(array_column($retVar['locais'], 'saldovalor'));
                $retVar['vendaprevisaoquinzena'] = array_sum(array_column($retVar['locais'], 'vendaprevisaoquinzena'));
                $retVar['vendabimestre'] = array_sum(array_column($retVar['locais'], 'vendabimestre'));
                $retVar['vendasemestre'] = array_sum(array_column($retVar['locais'], 'vendasemestre'));
                $retVar['vendaano'] = array_sum(array_column($retVar['locais'], 'vendaano'));
                //$retVar['vendabienio'] = array_sum(array_column($retVar['locais'], 'vendabienio'));

                // Calcula para quantos dias dura o estoque, baseado na venda do semestre
                if (!empty($retVar['vendasemestre'])) {
                    $retVar['saldodias'] = floor($retVar['saldoquantidade'] / ($retVar['vendasemestre'] / $diasSemestre));
                }

                // Custo Médio do Estoque
                if (!empty($retVar['saldoquantidade'])) {
                    $retVar['customedio'] = $retVar['saldovalor'] / $retVar['saldoquantidade'];
                }
                
                $retProd['variacoes'][$var->codprodutovariacao] = $retVar;
            }
                
            //Totaliza Variacoes
            $retProd['estoqueminimo'] = array_sum(array_column($retProd['variacoes'], 'estoqueminimo'));
            $retProd['estoquemaximo'] = array_sum(array_column($retProd['variacoes'], 'estoquemaximo'));
            $retProd['saldoquantidade'] = array_sum(array_column($retProd['variacoes'], 'saldoquantidade'));
            $retProd['saldovalor'] = array_sum(array_column($retProd['variacoes'], 'saldovalor'));
            $retProd['vendaprevisaoquinzena'] = array_sum(array_column($retProd['variacoes'], 'vendaprevisaoquinzena'));
            $retProd['vendabimestre'] = array_sum(array_column($retProd['variacoes'], 'vendabimestre'));
            $retProd['vendasemestre'] = array_sum(array_column($retProd['variacoes'], 'vendasemestre'));
            $retProd['vendaano'] = array_sum(array_column($retProd['variacoes'], 'vendaano'));
            //$retProd['vendabienio'] = array_sum(array_column($retProd['variacoes'], 'vendabienio'));

            if (!empty($retProd['saldoquantidade'])) {
                $retProd['customedio'] = $retProd['saldovalor'] / $retProd['saldoquantidade'];
            }
            
            // Calcula para quantos dias dura o estoque, baseado na venda do semestre
            if (!empty($retProd['vendasemestre'])) {
                $retProd['saldodias'] = floor($retProd['saldoquantidade'] / ($retProd['vendasemestre'] / $diasSemestre));
            }
            
            $retProd['valortotalcompra'] = array_sum(array_column($retProd['variacoes'], 'valortotalcompra'));
            $retProd['quantidadecompra'] = array_sum(array_column($retProd['variacoes'], 'quantidadecompra'));
            
            if (!empty($retProd['quantidadecompra'])) {
                $retProd['custocompra'] = $retProd['valortotalcompra'] / $retProd['quantidadecompra'];
            }
            
            $itens[$prod->codproduto] = $retProd;
            
        }
        
        $ret = [];
        foreach ($itens as $item) {
            switch ($agrupamento) {
                case 'marca':
                    $ret[$item['codmarca']]['codigo'] = $item['codmarca'];
                    $ret[$item['codmarca']]['descricao'] = $item['marca'];
                    $ret[$item['codmarca']]['produtos'][$item['codproduto']] = $item;
                    break;
                case 'subgrupoproduto':
                default:
                    $ret[$item['codsubgrupoproduto']]['codigo'] = $item['codsubgrupoproduto'];
                    $ret[$item['codsubgrupoproduto']]['descricao'] =
                        "{$item['secaoproduto']} » {$item['familiaproduto']} » {$item['grupoproduto']} » {$item['subgrupoproduto']}";
                    $ret[$item['codsubgrupoproduto']]['produtos'][$item['codproduto']] = $item;
                    break;
            }
        }
        
        
        //$query->leftJoin('tblprodutovariacao', 'tblprodutovariacao.codproduto', '=', 'tblproduto.codproduto');
        //$query->leftJoin('tblestoquelocalprodutovariacao', '')
        
        header('Content-Type: application/json');
        echo json_encode($ret);
        die();
        
        return $ret;
    }
}
