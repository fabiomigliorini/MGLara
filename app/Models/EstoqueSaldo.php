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
        return $this->belongsTo(Usuario::class, 'codusuarioalteracao', 'codusuario');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuariocriacao', 'codusuario');
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
                        , tblestoquelocalprodutovariacao.estoqueminimo
                        , tblestoquelocalprodutovariacao.estoquemaximo
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
                        , sum(tblestoquelocalprodutovariacao.estoqueminimo) as estoqueminimo
                        , sum(tblestoquelocalprodutovariacao.estoquemaximo) as estoquemaximo
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
                        , sum(tblestoquelocalprodutovariacao.estoqueminimo) as estoqueminimo
                        , sum(tblestoquelocalprodutovariacao.estoquemaximo) as estoquemaximo
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
                        , sum(tblestoquelocalprodutovariacao.estoqueminimo) as estoqueminimo
                        , sum(tblestoquelocalprodutovariacao.estoquemaximo) as estoquemaximo
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
                        , sum(tblestoquelocalprodutovariacao.estoqueminimo) as estoqueminimo
                        , sum(tblestoquelocalprodutovariacao.estoquemaximo) as estoquemaximo
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
                        , sum(tblestoquelocalprodutovariacao.estoqueminimo) as estoqueminimo
                        , sum(tblestoquelocalprodutovariacao.estoquemaximo) as estoquemaximo
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
                        , sum(tblestoquelocalprodutovariacao.estoqueminimo) as estoqueminimo
                        , sum(tblestoquelocalprodutovariacao.estoquemaximo) as estoquemaximo
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
                        $q2->whereRaw('saldoquantidade < tblestoquelocalprodutovariacao.estoqueminimo');
                    } else if ($filtro['minimo'] == 1) {
                        $q2->whereRaw('saldoquantidade >= tblestoquelocalprodutovariacao.estoqueminimo');

                    }
                }

                if (!empty($filtro['maximo'])) {
                    if ($filtro['maximo'] == -1) {
                        $q2->whereRaw('saldoquantidade <= tblestoquelocalprodutovariacao.estoquemaximo');
                    } else if ($filtro['maximo'] == 1) {
                        $q2->whereRaw('saldoquantidade > tblestoquelocalprodutovariacao.estoquemaximo');
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

    public static function relatorioAnalise($filtro)
    {

        // Monta tabelas da Query
        $qry = DB::table('tblproduto as p');
        $qry->join('tblprodutovariacao as pv', 'pv.codproduto', '=', 'p.codproduto');
        $qry->leftJoin('tblmarca as m', function($join) {
            $join->on('m.codmarca', '=', DB::raw('coalesce(pv.codmarca, p.codmarca)'));

        });
        $qry->leftJoin('tblunidademedida as um', 'um.codunidademedida', '=', 'p.codunidademedida');
        $qry->leftJoin('tblsubgrupoproduto as sgp', 'sgp.codsubgrupoproduto', '=', 'p.codsubgrupoproduto');
        $qry->leftJoin('tblgrupoproduto as gp', 'gp.codgrupoproduto', '=', 'sgp.codgrupoproduto');
        $qry->leftJoin('tblfamiliaproduto as fp', 'fp.codfamiliaproduto', '=', 'gp.codfamiliaproduto');
        $qry->leftJoin('tblsecaoproduto as sp', 'sp.codsecaoproduto', '=', 'fp.codsecaoproduto');
        $qry->leftJoin('tblestoquelocalprodutovariacao as elpv', 'elpv.codprodutovariacao', '=', 'pv.codprodutovariacao');
        $qry->leftJoin('tblestoquelocal as el', 'el.codestoquelocal', '=', 'elpv.codestoquelocal');
        $qry->leftJoin('tblestoquesaldo as es', function($join) {
            $join->on('es.codestoquelocalprodutovariacao', '=', 'elpv.codestoquelocalprodutovariacao');
            $join->on('es.fiscal', '=', DB::RAW('false'));

        });

        // Monta campos Selecionados
        $qry->select([
            'p.codproduto',
            'p.produto',
            'p.preco',
            'p.referencia',
            'p.inativo',

            'pv.codprodutovariacao',
            'pv.variacao',
            'pv.referencia as referenciavariacao',
            'pv.dataultimacompra',
            'pv.quantidadeultimacompra',
            'pv.custoultimacompra',

            'm.codmarca',
            'm.marca',

            'um.codunidademedida',
            'um.sigla as siglaunidademedida',

            'sgp.codsubgrupoproduto',
            'sgp.subgrupoproduto',

            'gp.codgrupoproduto',
            'gp.grupoproduto',

            'fp.codfamiliaproduto',
            'fp.familiaproduto',

            'sp.codsecaoproduto',
            'sp.secaoproduto',

            'elpv.codestoquelocalprodutovariacao',
            'elpv.corredor',
            'elpv.prateleira',
            'elpv.coluna',
            'elpv.bloco',
            'elpv.estoqueminimo',
            'elpv.estoquemaximo',
            'elpv.vendabimestrequantidade',
            'elpv.vendasemestrequantidade',
            'elpv.vendaanoquantidade',
            'elpv.vendadiaquantidadeprevisao',
            'elpv.vencimento',

            'el.codestoquelocal',
            'el.sigla as siglaestoquelocal',

            'es.codestoquesaldo',
            'es.dataentrada',
            'es.saldoquantidade',
            'es.saldovalor',
            'es.customedio',
        ]);

        // Aplica Filtro
        if (!empty($filtro['codproduto'])) {
            $qry->where('p.codproduto', $filtro['codproduto']);
        }

        if (!empty($filtro['codmarca'])) {
            $qry->where('m.codmarca', $filtro['codmarca']);
        }
        
        switch (isset($filtro['marcacontrolada']) ? $filtro['marcacontrolada'] : '9')
        {
            case 1:
                $qry->where('m.controlada', true);
                break;
            case 2:
                $qry->where('m.controlada', false);
                break;
            case 9;
            default:
        }
        
        if (!empty($filtro['codsubgrupoproduto'])) {
            $qry->where('p.codsubgrupoproduto', $filtro['codsubgrupoproduto']);
        }

        if (!empty($filtro['codgrupoproduto'])) {
            $qry->where('sgp.codgrupoproduto', $filtro['codgrupoproduto']);
        }

        if (!empty($filtro['codfamiliaproduto'])) {
            $qry->where('gp.codfamiliaproduto', $filtro['codfamiliaproduto']);
        }

        if (!empty($filtro['codsecaoproduto'])) {
            $qry->where('fp.codsecaoproduto', $filtro['codsecaoproduto']);
        }

        switch (isset($filtro['ativo'])?$filtro['ativo']:'9') {
            case 1: //Ativos
                $qry->whereNull('p.inativo');
                break;
            case 2: //Inativos
                $qry->whereNotNull('p.inativo');
                break;
            case 9; //Todos
            default:
        }

        if (!empty($filtro['codestoquelocal'])) {
            $qry->where('elpv.codestoquelocal', '=', $filtro['codestoquelocal']);
        }

        if (!empty($filtro['corredor'])) {
            $qry->where('elpv.corredor', '=', $filtro['corredor']);
        }

        if (!empty($filtro['prateleira'])) {
            $qry->where('elpv.prateleira', '=', $filtro['prateleira']);
        }

        if (!empty($filtro['coluna'])) {
            $qry->where('elpv.coluna', '=', $filtro['coluna']);
        }

        if (!empty($filtro['bloco'])) {
            $qry->where('elpv.bloco', '=', $filtro['bloco']);
        }

        if (!empty($filtro['minimo'])) {
            if ($filtro['minimo'] == -1) {
                $qry->whereRaw('es.saldoquantidade < elpv.estoqueminimo');
            } else if ($filtro['minimo'] == 1) {
                $qry->whereRaw('es.saldoquantidade >= elpv.estoqueminimo');

            }
        }

        if (!empty($filtro['maximo'])) {
            if ($filtro['maximo'] == -1) {
                $qry->whereRaw('es.saldoquantidade <= elpv.estoquemaximo');
            } else if ($filtro['maximo'] == 1) {
                $qry->whereRaw('es.saldoquantidade > elpv.estoquemaximo');
            }
        }

        if (!empty($filtro['saldo'])) {
            if ($filtro['saldo'] == -1) {
                $qry->whereRaw('es.saldoquantidade < 0');
            } else if ($filtro['saldo'] == 1) {
                $qry->whereRaw('es.saldoquantidade > 0');
            }
        }

        //$campo_codigo = 'codmarca';
        $qry->orderBy('m.marca', 'ASC');
        $qry->orderBy('m.codmarca', 'ASC');
        $qry->orderBy('sp.secaoproduto', 'ASC');
        $qry->orderBy('sp.codsecaoproduto', 'ASC');
        $qry->orderBy('fp.familiaproduto', 'ASC');
        $qry->orderBy('fp.codfamiliaproduto', 'ASC');
        $qry->orderBy('gp.grupoproduto', 'ASC');
        $qry->orderBy('gp.codgrupoproduto', 'ASC');
        $qry->orderBy('sgp.subgrupoproduto', 'ASC');
        $qry->orderBy('sgp.codsubgrupoproduto', 'ASC');
        $qry->orderBy('p.produto', 'ASC');
        $qry->orderBy('p.codproduto', 'ASC');
        $qry->orderByRaw('pv.variacao ASC NULLS FIRST');
        $qry->orderBy('pv.codprodutovariacao', 'ASC');
        $qry->orderBy('el.codestoquelocal', 'ASC');

        // Busca Registros
        $registros = collect($qry->get());

        $ret = [
            'filtro' => $filtro,
            'urlfiltro' => urlArrGet($filtro, 'estoque-saldo/relatorio-analise-filtro'),
            'agrupamentos' => [],
        ];

        foreach ($registros as $registro) {

            $codigo = "{$registro->codsubgrupoproduto}_{$registro->codmarca}";

            // Agrupamento Principal
            if (!isset($ret['agrupamentos'][$codigo])) {

                $filtro_detalhes = $filtro;
                unset($filtro_detalhes['codproduto']);
                unset($filtro_detalhes['codmarca']);
                unset($filtro_detalhes['marcacontrolada']); // <-- Marcas Controladas
                unset($filtro_detalhes['codgrupoproduto']);
                unset($filtro_detalhes['codsubgrupoproduto']);
                unset($filtro_detalhes['codfamiliaproduto']);
                unset($filtro_detalhes['codsecaoproduto']);

                $titulos = [];
                $filtro_detalhes['codsecaoproduto'] = $registro->codsecaoproduto;
                $titulos[] = [
                    'model' => 'secao-produto',
                    'codigo' => $registro->codsecaoproduto,
                    'descricao' => $registro->secaoproduto,
                    'urldetalhes' => urlArrGet($filtro_detalhes, 'estoque-saldo/relatorio-analise'),
                ];

                $filtro_detalhes['codfamiliaproduto'] = $registro->codfamiliaproduto;
                $titulos[] = [
                    'model' => 'familia-produto',
                    'codigo' => $registro->codfamiliaproduto,
                    'descricao' => $registro->familiaproduto,
                    'urldetalhes' => urlArrGet($filtro_detalhes, 'estoque-saldo/relatorio-analise'),
                ];

                $filtro_detalhes['codgrupoproduto'] = $registro->codgrupoproduto;
                $titulos[] = [
                    'model' => 'grupo-produto',
                    'codigo' => $registro->codgrupoproduto,
                    'descricao' => $registro->grupoproduto,
                    'urldetalhes' => urlArrGet($filtro_detalhes, 'estoque-saldo/relatorio-analise'),
                ];

                $filtro_detalhes['codsubgrupoproduto'] = $registro->codsubgrupoproduto;
                $titulos[] = [
                    'model' => 'sub-grupo-produto',
                    'codigo' => $registro->codsubgrupoproduto,
                    'descricao' => $registro->subgrupoproduto,
                    'urldetalhes' => urlArrGet($filtro_detalhes, 'estoque-saldo/relatorio-analise'),
                ];

                unset($filtro_detalhes['codgrupoproduto']);
                unset($filtro_detalhes['codsubgrupoproduto']);
                unset($filtro_detalhes['codfamiliaproduto']);
                unset($filtro_detalhes['codsecaoproduto']);
                $filtro_detalhes['codmarca'] = $registro->codmarca;
                $titulos[] = [
                    'model' => 'marca',
                    'codigo' => $registro->codmarca,
                    'descricao' => $registro->marca,
                    'urldetalhes' => urlArrGet($filtro_detalhes, 'estoque-saldo/relatorio-analise'),
                ];

                $ret['agrupamentos'][$codigo] = [
                    'titulos' => $titulos,
                ];
            }

            // Agrupamento Produto
            if (!isset($ret['agrupamentos'][$codigo]['produtos'][$registro->codproduto])) {

                $filtro_detalhes = $filtro;
                unset($filtro_detalhes['codmarca']);
                unset($filtro_detalhes['codgrupoproduto']);
                unset($filtro_detalhes['codsubgrupoproduto']);
                unset($filtro_detalhes['codfamiliaproduto']);
                unset($filtro_detalhes['codsecaoproduto']);
                $filtro_detalhes['codproduto'] = $registro->codproduto;

                $ret['agrupamentos'][$codigo]['produtos'][$registro->codproduto] = [
                    'codproduto' => $registro->codproduto,
                    'produto' => $registro->produto,
                    'preco' => $registro->preco,
                    'siglaunidademedida' => $registro->siglaunidademedida,
                    'codmarca' => $registro->codmarca,
                    'marca' => $registro->marca,
                    'inativo' => !empty($registro->inativo)?new Carbon($registro->inativo):null,
                    'urldetalhes' => urlArrGet($filtro_detalhes, 'estoque-saldo/relatorio-analise'),
                ];
            }

            // Agrupamento Variacao
            if (!isset($ret['agrupamentos'][$codigo]['produtos'][$registro->codproduto]['variacoes'][$registro->codprodutovariacao])) {
                $ret['agrupamentos'][$codigo]['produtos'][$registro->codproduto]['variacoes'][$registro->codprodutovariacao] = [
                    'codprodutovariacao' => $registro->codprodutovariacao,
                    'variacao' => $registro->variacao,
                    'referencia' => $registro->referencia,
                    'dataultimacompra' => !empty($registro->dataultimacompra)?new Carbon($registro->dataultimacompra):null,
                    'custoultimacompra' => $registro->custoultimacompra,
                    'quantidadeultimacompra' => $registro->quantidadeultimacompra,
                    'locais' => [],
                ];
            }

            // Agrupamento Local Estoque
            if (!empty($registro->codestoquelocal)) {
                //dd($registro);
                $saldodias = null;
                $vendaprevisaoquinzena = null;
                if (!empty($registro->vendadiaquantidadeprevisao)) {
                    $saldodias = floor($registro->saldoquantidade / $registro->vendadiaquantidadeprevisao);
                    $vendaprevisaoquinzena = round($registro->vendadiaquantidadeprevisao * 15, 0);
                }
                $ret['agrupamentos'][$codigo]['produtos'][$registro->codproduto]['variacoes'][$registro->codprodutovariacao]['locais'][$registro->codestoquelocal] = [
                    'codestoquelocal' => $registro->codestoquelocal,
                    'siglaestoquelocal' => $registro->siglaestoquelocal,
                    'corredor' => $registro->corredor,
                    'prateleira' => $registro->prateleira,
                    'coluna' => $registro->coluna,
                    'bloco' => $registro->bloco,
                    'codestoquesaldo' => $registro->codestoquesaldo,
                    'saldoquantidade' => $registro->saldoquantidade,
                    'saldovalor' => $registro->saldovalor,
                    'saldodias' => $saldodias,
                    'customedio' => $registro->customedio,
                    'dataentrada' => !empty($registro->dataentrada)?new Carbon($registro->dataentrada):null,
                    'estoqueminimo' => $registro->estoqueminimo,
                    'estoquemaximo' => $registro->estoquemaximo,
                    'vendabimestrequantidade' => $registro->vendabimestrequantidade,
                    'vendasemestrequantidade' => $registro->vendasemestrequantidade,
                    'vendaanoquantidade' => $registro->vendaanoquantidade,
                    'vendaprevisaoquinzena' => $vendaprevisaoquinzena,
                    'vendadiaquantidadeprevisao' => $registro->vendadiaquantidadeprevisao,
                    'vencimento' => !empty($registro->vencimento)?new Carbon($registro->vencimento):null,
                ];
            }

        }

        foreach ($ret['agrupamentos'] as $codigo => $agrupamento) {

            foreach ($agrupamento['produtos'] as $codproduto => $produto) {

                foreach ($produto['variacoes'] as $codprodutovariacao => $variacao) {

                    // Totaliza Variacao
                    $ret['agrupamentos'][$codigo]['produtos'][$codproduto]['variacoes'][$codprodutovariacao]['saldoquantidade'] =
                        array_sum(array_column($ret['agrupamentos'][$codigo]['produtos'][$codproduto]['variacoes'][$codprodutovariacao]['locais'], 'saldoquantidade'));

                    $ret['agrupamentos'][$codigo]['produtos'][$codproduto]['variacoes'][$codprodutovariacao]['saldovalor'] =
                        array_sum(array_column($ret['agrupamentos'][$codigo]['produtos'][$codproduto]['variacoes'][$codprodutovariacao]['locais'], 'saldovalor'));

                    $ret['agrupamentos'][$codigo]['produtos'][$codproduto]['variacoes'][$codprodutovariacao]['customedio'] = null;
                    if (!empty($ret['agrupamentos'][$codigo]['produtos'][$codproduto]['variacoes'][$codprodutovariacao]['saldoquantidade'])) {
                        $ret['agrupamentos'][$codigo]['produtos'][$codproduto]['variacoes'][$codprodutovariacao]['customedio'] =
                            $ret['agrupamentos'][$codigo]['produtos'][$codproduto]['variacoes'][$codprodutovariacao]['saldovalor'] /
                            $ret['agrupamentos'][$codigo]['produtos'][$codproduto]['variacoes'][$codprodutovariacao]['saldoquantidade'];
                    }

                    $vendadiaquantidadeprevisao =
                        array_sum(array_column($ret['agrupamentos'][$codigo]['produtos'][$codproduto]['variacoes'][$codprodutovariacao]['locais'], 'vendadiaquantidadeprevisao'));
                    $saldodias = null;
                    $vendaprevisaoquinzena = null;
                    if (!empty($vendadiaquantidadeprevisao)) {
                        $saldodias = floor($ret['agrupamentos'][$codigo]['produtos'][$codproduto]['variacoes'][$codprodutovariacao]['saldoquantidade'] / $vendadiaquantidadeprevisao);
                        $vendaprevisaoquinzena = round($vendadiaquantidadeprevisao * 15, 0);
                    }
                    $ret['agrupamentos'][$codigo]['produtos'][$codproduto]['variacoes'][$codprodutovariacao]['vendadiaquantidadeprevisao'] = $vendadiaquantidadeprevisao;
                    $ret['agrupamentos'][$codigo]['produtos'][$codproduto]['variacoes'][$codprodutovariacao]['saldodias'] = $saldodias;
                    $ret['agrupamentos'][$codigo]['produtos'][$codproduto]['variacoes'][$codprodutovariacao]['vendaprevisaoquinzena'] = $vendaprevisaoquinzena;

                    $ret['agrupamentos'][$codigo]['produtos'][$codproduto]['variacoes'][$codprodutovariacao]['estoqueminimo'] =
                        array_sum(array_column($ret['agrupamentos'][$codigo]['produtos'][$codproduto]['variacoes'][$codprodutovariacao]['locais'], 'estoqueminimo'));

                    $ret['agrupamentos'][$codigo]['produtos'][$codproduto]['variacoes'][$codprodutovariacao]['estoquemaximo'] =
                        array_sum(array_column($ret['agrupamentos'][$codigo]['produtos'][$codproduto]['variacoes'][$codprodutovariacao]['locais'], 'estoquemaximo'));

                    $ret['agrupamentos'][$codigo]['produtos'][$codproduto]['variacoes'][$codprodutovariacao]['vendabimestrequantidade'] =
                        array_sum(array_column($ret['agrupamentos'][$codigo]['produtos'][$codproduto]['variacoes'][$codprodutovariacao]['locais'], 'vendabimestrequantidade'));

                    $ret['agrupamentos'][$codigo]['produtos'][$codproduto]['variacoes'][$codprodutovariacao]['vendasemestrequantidade'] =
                        array_sum(array_column($ret['agrupamentos'][$codigo]['produtos'][$codproduto]['variacoes'][$codprodutovariacao]['locais'], 'vendasemestrequantidade'));

                    $ret['agrupamentos'][$codigo]['produtos'][$codproduto]['variacoes'][$codprodutovariacao]['vendaanoquantidade'] =
                        array_sum(array_column($ret['agrupamentos'][$codigo]['produtos'][$codproduto]['variacoes'][$codprodutovariacao]['locais'], 'vendaanoquantidade'));

                }

                // Totaliza Produto
                $ret['agrupamentos'][$codigo]['produtos'][$codproduto]['saldoquantidade'] =
                    array_sum(array_column($ret['agrupamentos'][$codigo]['produtos'][$codproduto]['variacoes'], 'saldoquantidade'));

                $ret['agrupamentos'][$codigo]['produtos'][$codproduto]['saldovalor'] =
                    array_sum(array_column($ret['agrupamentos'][$codigo]['produtos'][$codproduto]['variacoes'], 'saldovalor'));

                $ret['agrupamentos'][$codigo]['produtos'][$codproduto]['customedio'] = null;
                if (!empty($ret['agrupamentos'][$codigo]['produtos'][$codproduto]['saldoquantidade'])) {
                    $ret['agrupamentos'][$codigo]['produtos'][$codproduto]['customedio'] =
                        $ret['agrupamentos'][$codigo]['produtos'][$codproduto]['saldovalor'] /
                        $ret['agrupamentos'][$codigo]['produtos'][$codproduto]['saldoquantidade'];
                }

                $vendadiaquantidadeprevisao =
                    array_sum(array_column($ret['agrupamentos'][$codigo]['produtos'][$codproduto]['variacoes'], 'vendadiaquantidadeprevisao'));
                $saldodias = null;
                $vendaprevisaoquinzena = null;
                if (!empty($vendadiaquantidadeprevisao)) {
                    $saldodias = floor($ret['agrupamentos'][$codigo]['produtos'][$codproduto]['saldoquantidade'] / $vendadiaquantidadeprevisao);
                    $vendaprevisaoquinzena = round($vendadiaquantidadeprevisao * 15, 0);
                }
                $ret['agrupamentos'][$codigo]['produtos'][$codproduto]['vendadiaquantidadeprevisao'] = $vendadiaquantidadeprevisao;
                $ret['agrupamentos'][$codigo]['produtos'][$codproduto]['saldodias'] = $saldodias;
                $ret['agrupamentos'][$codigo]['produtos'][$codproduto]['vendaprevisaoquinzena'] = $vendaprevisaoquinzena;

                $ret['agrupamentos'][$codigo]['produtos'][$codproduto]['estoqueminimo'] =
                    array_sum(array_column($ret['agrupamentos'][$codigo]['produtos'][$codproduto]['variacoes'], 'estoqueminimo'));

                $ret['agrupamentos'][$codigo]['produtos'][$codproduto]['estoquemaximo'] =
                    array_sum(array_column($ret['agrupamentos'][$codigo]['produtos'][$codproduto]['variacoes'], 'estoquemaximo'));

                $ret['agrupamentos'][$codigo]['produtos'][$codproduto]['vendabimestrequantidade'] =
                    array_sum(array_column($ret['agrupamentos'][$codigo]['produtos'][$codproduto]['variacoes'], 'vendabimestrequantidade'));

                $ret['agrupamentos'][$codigo]['produtos'][$codproduto]['vendasemestrequantidade'] =
                    array_sum(array_column($ret['agrupamentos'][$codigo]['produtos'][$codproduto]['variacoes'], 'vendasemestrequantidade'));

                $ret['agrupamentos'][$codigo]['produtos'][$codproduto]['vendaanoquantidade'] =
                    array_sum(array_column($ret['agrupamentos'][$codigo]['produtos'][$codproduto]['variacoes'], 'vendaanoquantidade'));

            }

            // Totaliza Agrupamento
            $ret['agrupamentos'][$codigo]['saldoquantidade'] =
                array_sum(array_column($ret['agrupamentos'][$codigo]['produtos'], 'saldoquantidade'));

            $ret['agrupamentos'][$codigo]['saldovalor'] =
                array_sum(array_column($ret['agrupamentos'][$codigo]['produtos'], 'saldovalor'));

            $ret['agrupamentos'][$codigo]['customedio'] = null;
            if (!empty($ret['agrupamentos'][$codigo]['saldoquantidade'])) {
                $ret['agrupamentos'][$codigo]['customedio'] =
                    $ret['agrupamentos'][$codigo]['saldovalor'] /
                    $ret['agrupamentos'][$codigo]['saldoquantidade'];
            }

            $vendadiaquantidadeprevisao =
                array_sum(array_column($ret['agrupamentos'][$codigo]['produtos'], 'vendadiaquantidadeprevisao'));
            $saldodias = null;
            $vendaprevisaoquinzena = null;
            if (!empty($vendadiaquantidadeprevisao)) {
                $saldodias = floor($ret['agrupamentos'][$codigo]['saldoquantidade'] / $vendadiaquantidadeprevisao);
                $vendaprevisaoquinzena = round($vendadiaquantidadeprevisao * 15, 0);
            }
            $ret['agrupamentos'][$codigo]['vendadiaquantidadeprevisao'] = $vendadiaquantidadeprevisao;
            $ret['agrupamentos'][$codigo]['saldodias'] = $saldodias;
            $ret['agrupamentos'][$codigo]['vendaprevisaoquinzena'] = $vendaprevisaoquinzena;

            $ret['agrupamentos'][$codigo]['estoqueminimo'] =
                array_sum(array_column($ret['agrupamentos'][$codigo]['produtos'], 'estoqueminimo'));

            $ret['agrupamentos'][$codigo]['estoquemaximo'] =
                array_sum(array_column($ret['agrupamentos'][$codigo]['produtos'], 'estoquemaximo'));

            $ret['agrupamentos'][$codigo]['vendabimestrequantidade'] =
                array_sum(array_column($ret['agrupamentos'][$codigo]['produtos'], 'vendabimestrequantidade'));

            $ret['agrupamentos'][$codigo]['vendasemestrequantidade'] =
                array_sum(array_column($ret['agrupamentos'][$codigo]['produtos'], 'vendasemestrequantidade'));

            $ret['agrupamentos'][$codigo]['vendaanoquantidade'] =
                array_sum(array_column($ret['agrupamentos'][$codigo]['produtos'], 'vendaanoquantidade'));

        }

        // Totaliza Relatorio
        $ret['saldoquantidade'] =
            array_sum(array_column($ret['agrupamentos'], 'saldoquantidade'));

        $ret['saldovalor'] =
            array_sum(array_column($ret['agrupamentos'], 'saldovalor'));

        $ret['customedio'] = null;
        if (!empty($ret['saldoquantidade'])) {
            $ret['customedio'] =
                $ret['saldovalor'] /
                $ret['saldoquantidade'];
        }

        $vendadiaquantidadeprevisao =
            array_sum(array_column($ret['agrupamentos'], 'vendadiaquantidadeprevisao'));
        $saldodias = null;
        $vendaprevisaoquinzena = null;
        if (!empty($vendadiaquantidadeprevisao)) {
            $saldodias = floor($ret['saldoquantidade'] / $vendadiaquantidadeprevisao);
            $vendaprevisaoquinzena = round($vendadiaquantidadeprevisao * 15, 0);
        }
        $ret['vendadiaquantidadeprevisao'] = $vendadiaquantidadeprevisao;
        $ret['saldodias'] = $saldodias;
        $ret['vendaprevisaoquinzena'] = $vendaprevisaoquinzena;

        $ret['estoqueminimo'] =
            array_sum(array_column($ret['agrupamentos'], 'estoqueminimo'));

        $ret['estoquemaximo'] =
            array_sum(array_column($ret['agrupamentos'], 'estoquemaximo'));

        $ret['vendabimestrequantidade'] =
            array_sum(array_column($ret['agrupamentos'], 'vendabimestrequantidade'));

        $ret['vendasemestrequantidade'] =
            array_sum(array_column($ret['agrupamentos'], 'vendasemestrequantidade'));

        $ret['vendaanoquantidade'] =
            array_sum(array_column($ret['agrupamentos'], 'vendaanoquantidade'));

        return $ret;
    }

    public static function relatorioComparativoVendas ($filtro)
    {
        $sql = "
            select
                m.codmarca
                , m.marca
                , coalesce(pv.referencia, p.referencia) as referencia
                , (select array_to_json(array(
                    select pb.barras
                    from tblprodutobarra pb
                    --left join tblprodutoembalagem pe on (pe.codprodutoembalagem = pb.codprodutoembalagem)
                    where pb.codprodutovariacao = pv.codprodutovariacao
                    and pb.codprodutoembalagem is null
                    --order by pe.quantidade nulls first, barras
                    order by barras
                    limit 5
                    ))) as json_barras
                , p.codproduto
                , p.produto
                , p.inativo
                , pv.codprodutovariacao
                , pv.variacao
                , iq.quantidade_vendida
                , es_filial.codestoquesaldo as codestoquesaldo_filial
                , es_filial.saldoquantidade as saldoquantidade_filial
                , elpv_filial.estoqueminimo
                , elpv_filial.estoquemaximo
                , es_deposito.codestoquesaldo as codestoquesaldo_deposito
                , es_deposito.saldoquantidade as saldoquantidade_deposito
                , elpv_deposito.corredor
                , elpv_deposito.prateleira
                , elpv_deposito.coluna
                , elpv_deposito.bloco
                , elpv_filial.vendadiaquantidadeprevisao * {$filtro['dias_previsao']} as previsao_vendas
            from (
                select
                    iq_pb.codprodutovariacao
                    ,  sum(iq_npb.quantidade * coalesce(iq_pe.quantidade, 1)) as quantidade_vendida
                from tblnegocio iq_n
                inner join tblnaturezaoperacao iq_no on (iq_no.codnaturezaoperacao = iq_n.codnaturezaoperacao)
                inner join tblnegocioprodutobarra iq_npb on (iq_npb.codnegocio = iq_n.codnegocio)
                inner join tblprodutobarra iq_pb on (iq_pb.codprodutobarra = iq_npb.codprodutobarra)
                inner join tblproduto iq_p on (iq_p.codproduto = iq_pb.codproduto)
                inner join tbltipoproduto iq_tp on (iq_tp.codtipoproduto = iq_p.codtipoproduto)
                left join tblprodutoembalagem iq_pe on (iq_pe.codprodutoembalagem = iq_pb.codprodutoembalagem)
                where iq_n.codnegociostatus = 2
                and iq_n.codestoquelocal = {$filtro['codestoquelocalfilial']}
                and iq_n.lancamento between '{$filtro['datainicial']->format('Y-m-d H:i')}' and '{$filtro['datafinal']->format('Y-m-d H:i')}'
                and iq_no.venda = true
                and iq_no.estoque = true
                and iq_tp.estoque = true
                group by iq_pb.codprodutovariacao
                ) iq
            left join tblprodutovariacao pv on (pv.codprodutovariacao = iq.codprodutovariacao)
            left join tblestoquelocalprodutovariacao elpv_deposito on (elpv_deposito.codprodutovariacao = iq.codprodutovariacao and elpv_deposito.codestoquelocal = {$filtro['codestoquelocaldeposito']})
            left join tblestoquesaldo es_deposito on (es_deposito.codestoquelocalprodutovariacao = elpv_deposito.codestoquelocalprodutovariacao and es_deposito.fiscal = false)
            left join tblestoquelocalprodutovariacao elpv_filial on (elpv_filial.codprodutovariacao = iq.codprodutovariacao and elpv_filial.codestoquelocal = {$filtro['codestoquelocalfilial']})
            left join tblestoquesaldo es_filial on (es_filial.codestoquelocalprodutovariacao = elpv_filial.codestoquelocalprodutovariacao and es_filial.fiscal = false)
            left join tblproduto p on (p.codproduto = pv.codproduto)
            left join tblmarca m on (m.codmarca = coalesce(pv.codmarca, p.codmarca))
            ";

        $s_and = 'WHERE';
        if (!empty($filtro['codmarca'])) {
            $sql .= " $s_and m.codmarca = {$filtro['codmarca']}";
            $s_and = 'AND';
        }

        switch (isset($filtro['marcacontrolada']) ? $filtro['marcacontrolada'] : '9')
        {
            case 1:
                $sql .= " AND m.controlada = true";
                // $qry->where('m.controlada', true);
                break;
            case 2:
                $sql .= " AND m.controlada = false";
                // $qry->where('m.controlada', false);
                break;
            case 9;
            default:
        }

        switch ($filtro['saldo_deposito']) {
            case 1:
                $sql .= " $s_and es_deposito.saldoquantidade > 0";
                $s_and = 'AND';
                break;

            case -1:
                $sql .= " $s_and (es_deposito.saldoquantidade <= 0 or es_deposito.codestoquesaldo is null )";
                $s_and = 'AND';
                break;
        }

        switch ($filtro['saldo_filial']) {
            case 1:
                $sql .= " $s_and (es_filial.saldoquantidade > coalesce(elpv_filial.vendadiaquantidadeprevisao * {$filtro['dias_previsao']}, 0))";
                $s_and = 'AND';
                break;

            case -1:
                $sql .= " $s_and (es_filial.saldoquantidade <= coalesce(elpv_filial.vendadiaquantidadeprevisao * {$filtro['dias_previsao']}, 0))";
                $s_and = 'AND';
                break;
        }

        switch ($filtro['minimo']) {
            case 1:
                $sql .= " $s_and es_filial.saldoquantidade > coalesce(elpv_filial.estoqueminimo, 0)";
                $s_and = 'AND';
                break;

            case -1:
                $sql .= " $s_and es_filial.saldoquantidade <= coalesce(elpv_filial.estoqueminimo, 0)";
                $s_and = 'AND';
                break;
        }

        switch ($filtro['maximo']) {
            case 1:
                $sql .= " $s_and es_filial.saldoquantidade > coalesce(elpv_filial.estoquemaximo, es_filial.saldoquantidade)";
                $s_and = 'AND';
                break;

            case -1:
                $sql .= " $s_and es_filial.saldoquantidade <= coalesce(elpv_filial.estoquemaximo, es_filial.saldoquantidade)";
                $s_and = 'AND';
                break;
        }

        $sql .= "
            order by m.marca, p.produto, p.codproduto, pv.variacao nulls first, pv.codprodutovariacao
            ";

        $regs = DB::select($sql);

        $ret = [
            'filtro' => $filtro,
            'estoquelocal_deposito' => EstoqueLocal::findOrFail($filtro['codestoquelocaldeposito'])->estoquelocal,
            'estoquelocal_filial' => EstoqueLocal::findOrFail($filtro['codestoquelocalfilial'])->estoquelocal,
            'urlfiltro' => urlArrGet($filtro, 'estoque-saldo/relatorio-comparativo-vendas-filtro'),
            'itens' => $regs,
        ];

        return $ret;
    }

    public static function relatorioFisicoFiscal ($filtro)
    {
        $ultimodiadomes = Carbon::createFromDate($filtro['ano'], $filtro['mes'], 01)->daysInMonth;

        $sql = "
            select
                p.codproduto
                , p.produto
                , fiscal.saldoquantidade as fiscal_saldoquantidade
                , fiscal.saldovalor as fiscal_saldovalor
                , fiscal.customedio as fiscal_customedio
                , fisico.saldoquantidade as fisico_saldoquantidade
                , fisico.saldovalor as fisico_saldovalor
                , fisico.customedio as fisico_customedio
                , p.preco
                , m.codmarca
                , m.marca
                , sp.codsecaoproduto
                , sp.secaoproduto
                , fp.codfamiliaproduto
                , fp.familiaproduto
                , gp.codgrupoproduto
                , gp.grupoproduto
                , sgp.codsubgrupoproduto
                , sgp.subgrupoproduto
            from tblproduto p
            left join tblmarca m on (m.codmarca = p.codmarca)
            left join tblsubgrupoproduto sgp on (sgp.codsubgrupoproduto = p.codsubgrupoproduto)
            left join tblgrupoproduto gp on (gp.codgrupoproduto = sgp.codgrupoproduto)
            left join tblfamiliaproduto fp on (fp.codfamiliaproduto = gp.codfamiliaproduto)
            left join tblsecaoproduto sp on (sp.codsecaoproduto = fp.codsecaoproduto)
            left join (
                select pv.codproduto, sum(em.saldoquantidade) as saldoquantidade, sum(em.saldovalor) as saldovalor, avg(em.customedio) as customedio
                from tblestoquelocalprodutovariacao elpv
                inner join tblprodutovariacao pv on (pv.codprodutovariacao = elpv.codprodutovariacao)
                inner join tblestoquelocal el on (el.codestoquelocal = elpv.codestoquelocal)
                inner join tblfilial f on (f.codfilial = el.codfilial)
                inner join tblestoquesaldo es on (es.codestoquelocalprodutovariacao = elpv.codestoquelocalprodutovariacao and es.fiscal = false)
                inner join tblestoquemes em on (em.codestoquemes = (select em2.codestoquemes from tblestoquemes em2 where em2.codestoquesaldo = es.codestoquesaldo and em2.mes <= '{$filtro['ano']}-{$filtro['mes']}-{$ultimodiadomes}' order by mes desc limit 1))
                where f.codempresa = {$filtro['codempresa']}";
                
        if (!empty($filtro['codestoquelocal'])) {
            $sql .= " AND el.codestoquelocal = {$filtro['codestoquelocal']}";
        }
        
        $sql .= "
                group by pv.codproduto
                ) fisico on (fisico.codproduto = p.codproduto)
            left join (
                select pv.codproduto, sum(em.saldoquantidade) as saldoquantidade, sum(em.saldovalor) as saldovalor, avg(em.customedio) as customedio
                from tblestoquelocalprodutovariacao elpv
                inner join tblprodutovariacao pv on (pv.codprodutovariacao = elpv.codprodutovariacao)
                inner join tblestoquelocal el on (el.codestoquelocal = elpv.codestoquelocal)
                inner join tblfilial f on (f.codfilial = el.codfilial)
                inner join tblestoquesaldo es on (es.codestoquelocalprodutovariacao = elpv.codestoquelocalprodutovariacao and es.fiscal = true)
                inner join tblestoquemes em on (em.codestoquemes = (select em2.codestoquemes from tblestoquemes em2 where em2.codestoquesaldo = es.codestoquesaldo and em2.mes <= '{$filtro['ano']}-{$filtro['mes']}-{$ultimodiadomes}' order by mes desc limit 1))
                where f.codempresa = {$filtro['codempresa']}";
                
        if (!empty($filtro['codestoquelocal'])) {
            $sql .= " AND el.codestoquelocal = {$filtro['codestoquelocal']}";
        }
        
        $sql .= "
                group by pv.codproduto
                ) fiscal on (fiscal.codproduto = p.codproduto)
            where p.codtipoproduto = 0
            --and p.inativo is null
            ";

        if (!empty($filtro['codmarca'])) {
            $sql .= " AND m.codmarca = {$filtro['codmarca']}";
        }

        switch (isset($filtro['marcacontrolada']) ? $filtro['marcacontrolada'] : '9')
        {
            case 1:
                $sql .= " AND m.controlada = true";
                // $qry->where('m.controlada', true);
                break;
            case 2:
                $sql .= " AND m.controlada = false";
                // $qry->where('m.controlada', false);
                break;
            case 9;
            default:
        }

        if (!empty($filtro['codncm'])) {
            $sql .= " AND p.codncm = {$filtro['codncm']}";
        }

        if (!empty($filtro['preco_de'])) {
            $sql .= " AND p.preco >= {$filtro['preco_de']}";
        }

        if (!empty($filtro['preco_ate'])) {
            $sql .= " AND p.preco <= {$filtro['preco_ate']}";
        }
        
        if (!empty($filtro['produto'])) {
            $palavras = explode(' ', $filtro['produto']);
            foreach ($palavras as $palavra) {
                if (empty($palavra)) {
                    continue;
                }
                $sql .= " AND p.produto ilike '%{$palavra}%'";
            }
        }

        if (!empty($filtro['codsubgrupoproduto'])) {
            $sql .= " AND p.codsubgrupoproduto = {$filtro['codsubgrupoproduto']}";
        } elseif (!empty($filtro['codgrupoproduto'])) {
            $sql .= " AND sgp.codgrupoproduto = {$filtro['codgrupoproduto']}";
        } elseif (!empty($filtro['codfamiliaproduto'])) {
            $sql .= " AND gp.codfamiliaproduto = {$filtro['codfamiliaproduto']}";
        } elseif (!empty($filtro['codsecaoproduto'])) {
            $sql .= " AND fp.codsecaoproduto = {$filtro['codsecaoproduto']}";
        }

	if (!empty($filtro['codtributacao'])) {
	    $sql .= " AND p.codtributacao = {$filtro['codtributacao']}";
	}

        switch ($filtro['saldo_fisico']) {
            case -1:
                $sql .= " AND fisico.saldoquantidade < 0";
                break;
            case 1:
                $sql .= " AND fisico.saldoquantidade > 0";
                break;
            case 9:
                $sql .= " AND coalesce(fisico.saldoquantidade, 0) = 0";
                break;

        }

        switch ($filtro['saldo_fiscal']) {
            case -1:
                $sql .= " AND fiscal.saldoquantidade < 0";
                break;
            case 1:
                $sql .= " AND fiscal.saldoquantidade > 0";
                break;
	    case 9:
		$sql .= " AND coalesce(fiscal.saldoquantidade, 0) = 0";
		break;
        }

        switch ($filtro['saldo_fisico_fiscal']) {
            case -1:
                $sql .= " AND coalesce(fiscal.saldoquantidade, 0) < coalesce(fisico.saldoquantidade, 0)";
                break;
            case 1:
                $sql .= " AND coalesce(fiscal.saldoquantidade, 0) > coalesce(fisico.saldoquantidade, 0)";
                break;
        }

        $sql .= "
            order by p.produto, p.codproduto
            ";
        
        $regs = DB::select($sql);
        
        $totais['fisico_saldoquantidade'] = array_sum(array_column($regs, 'fisico_saldoquantidade'));
        $totais['fisico_saldovalor'] = array_sum(array_column($regs, 'fisico_saldovalor'));
        $totais['fiscal_saldoquantidade'] = array_sum(array_column($regs, 'fiscal_saldoquantidade'));
        $totais['fiscal_saldovalor'] = array_sum(array_column($regs, 'fiscal_saldovalor'));

        $ret = [
            'filtro' => $filtro,
            'urlfiltro' => urlArrGet($filtro, 'estoque-saldo/relatorio-fisico-fiscal-filtro'),
            'itens' => $regs,
            'totais' => $totais,
        ];

        return $ret;
    }

    public static function RelatorioTransferencias($filtro)
    {

        $sql = '
            with itens as (
                select 
                    m.marca, 
                    p.codproduto, 
                    p.produto, 
                    pv.variacao, 
                    elpv_dest.estoqueminimo, 
                    elpv_dest.estoquemaximo, 
                    sld_dest.saldoquantidade as saldodestino, 
                    sld_orig.saldoquantidade as saldoorigem, 
                    coalesce(pe.quantidade, 1) as quantidadeembalagem 
                from tblproduto p
                inner join tblmarca m on (m.codmarca = p.codmarca)
                inner join tblprodutovariacao pv on (pv.codproduto = p.codproduto)
                left join tblprodutoembalagem pe on (pe.codprodutoembalagem = p.codprodutoembalagemtransferencia)
                inner join tblestoquelocalprodutovariacao elpv_orig on (elpv_orig.codprodutovariacao = pv.codprodutovariacao and elpv_orig.codestoquelocal = :codestoquelocalorigem)
                inner join tblestoquelocalprodutovariacao elpv_dest on (elpv_dest.codprodutovariacao = pv.codprodutovariacao and elpv_dest.codestoquelocal = :codestoquelocaldestino)
                inner join tblestoquesaldo sld_orig on (sld_orig.codestoquelocalprodutovariacao = elpv_orig.codestoquelocalprodutovariacao and sld_orig.fiscal = false)
                inner join tblestoquesaldo sld_dest on (sld_dest.codestoquelocalprodutovariacao = elpv_dest.codestoquelocalprodutovariacao and sld_dest.fiscal = false)
                where sld_dest.saldoquantidade < elpv_dest.estoqueminimo
                and sld_orig.saldoquantidade > 0
        ';

        $params = [
            'codestoquelocalorigem' => $filtro['codestoquelocalorigem'],
            'codestoquelocaldestino' => $filtro['codestoquelocaldestino'],
        ];
        
        if (!empty($filtro['codmarca'])) {
            $sql .= '
                and p.codmarca = :codmarca 
            ';
            $params['codmarca']  = $filtro['codmarca'];
        }

        $sql .= '
                order by m.marca, p.produto, pv.variacao, pv.codprodutovariacao 
            )
            select 
                *,
                round((estoquemaximo - saldodestino)/quantidadeembalagem) * quantidadeembalagem as transferir
            from itens
        ';

        $result =  DB::select($sql, $params);
        

        $retorno =  ['filtro' => $filtro,
            'urlfiltro' => urlArrGet($filtro, 'estoque-saldo/relatorio-transferencias-filtro'),
            'itens' => $result
        ];


       return $retorno;
    }

    public function scopeFiscal($query, $fiscal)
    {
        if ($fiscal)
            $query->where('fiscal', true);
        else
            $query->where('fiscal', false);
    } 
}
