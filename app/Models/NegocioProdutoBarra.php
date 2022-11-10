<?php

namespace MGLara\Models;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Campos
 * @property  bigint                         $codnegocioprodutobarra             NOT NULL DEFAULT nextval('tblnegocioprodutobarra_codnegocioprodutobarra_seq'::regclass)
 * @property  bigint                         $codnegocio                         NOT NULL
 * @property  numeric(14,3)                  $quantidade                         NOT NULL
 * @property  numeric(14,3)                  $valorunitario                      NOT NULL
 * @property  numeric(14,2)                  $valortotal                         NOT NULL
 * @property  bigint                         $codprodutobarra                    NOT NULL
 * @property  timestamp                      $alteracao
 * @property  bigint                         $codusuarioalteracao
 * @property  timestamp                      $criacao
 * @property  bigint                         $codusuariocriacao
 * @property  bigint                         $codnegocioprodutobarradevolucao
 *
 * Chaves Estrangeiras
 * @property  Negocio                        $Negocio
 * @property  NegocioProdutoBarra            $NegocioProdutoBarra
 * @property  ProdutoBarra                   $ProdutoBarra
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 * @property  NegocioProdutoBarra[]          $NegocioProdutoBarraS
 * @property  CupomFiscalProdutoBarra[]      $CupomFiscalProdutoBarraS
 * @property  EstoqueMovimento[]             $EstoqueMovimentoS
 * @property  NotaFiscalProdutoBarra[]       $NotaFiscalProdutoBarraS
 */

class NegocioProdutoBarra extends MGModel
{
    protected $table = 'tblnegocioprodutobarra';
    protected $primaryKey = 'codnegocioprodutobarra';
    protected $fillable = [
        'codnegocio',
        'quantidade',
        'valorunitario',
        'valortotal',
        'codprodutobarra',
        'codnegocioprodutobarradevolucao',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
    ];


    // Chaves Estrangeiras
    public function Negocio()
    {
        return $this->belongsTo(Negocio::class, 'codnegocio', 'codnegocio');
    }

    public function NegocioProdutoBarra()
    {
        return $this->belongsTo(NegocioProdutoBarra::class, 'codnegocioprodutobarra', 'codnegocioprodutobarradevolucao');
    }

    public function ProdutoBarra()
    {
        return $this->belongsTo(ProdutoBarra::class, 'codprodutobarra', 'codprodutobarra');
    }

    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuarioalteracao', 'codusuario');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuariocriacao', 'codusuario');
    }

    public function NegocioProdutoBarraDevolucao()
    {
        return $this->belongsTo(NegocioProdutoBarra::class, 'codnegocioprodutobarra', 'codnegocioprodutobarradevolucao');
    }


    // Tabelas Filhas
    public function NegocioProdutoBarraS()
    {
        return $this->hasMany(NegocioProdutoBarra::class, 'codnegocioprodutobarra', 'codnegocioprodutobarradevolucao');
    }

    public function CupomFiscalProdutoBarraS()
    {
        return $this->hasMany(CupomFiscalProdutoBarra::class, 'codnegocioprodutobarra', 'codnegocioprodutobarra');
    }

    public function MercosPedidoItemS()
    {
        return $this->hasMany(MercosPedidoItem::class, 'codnegocioprodutobarra', 'codnegocioprodutobarra');
    }

    /**
     *
     * @return EstoqueMovimento[]
     */
    public function EstoqueMovimentoS()
    {
        return $this->hasMany(EstoqueMovimento::class, 'codnegocioprodutobarra', 'codnegocioprodutobarra');
    }

    public function NotaFiscalProdutoBarraS()
    {
        return $this->hasMany(NotaFiscalProdutoBarra::class, 'codnegocioprodutobarra', 'codnegocioprodutobarra');
    }

    public function NegocioProdutoBarraDevolucaoS()
    {
        return $this->hasMany(NegocioProdutoBarra::class, 'codnegocioprodutobarradevolucao', 'codnegocioprodutobarra');
    }

    public static function search($parametros, $registros = 20)
    {
        $query = NegocioProdutoBarra::orderBy('tblnegocio.lancamento', 'DESC');

        $query = $query->join('tblnegocio', function($join) use ($parametros) {
            $join->on('tblnegocio.codnegocio', '=', 'tblnegocioprodutobarra.codnegocio');
        });

        if (!empty($parametros['negocio_codpessoa']))
            $query = $query->where('tblnegocio.codpessoa', '=', $parametros['negocio_codpessoa']);

        if (!empty($parametros['negocio_codnaturezaoperacao']))
            $query = $query->where('tblnegocio.codnaturezaoperacao', '=', $parametros['negocio_codnaturezaoperacao']);

        if (!empty($parametros['negocio_codfilial']))
            $query = $query->where('tblnegocio.codfilial', '=', $parametros['negocio_codfilial']);

        if (!empty($parametros['negocio_lancamento_de']))
            $query = $query->where('tblnegocio.lancamento', '>=', $parametros['negocio_lancamento_de']);

        if (!empty($parametros['negocio_lancamento_ate']))
            $query = $query->where('tblnegocio.lancamento', '<=', $parametros['negocio_lancamento_ate']);

        if (!empty($parametros['negocio_codproduto']))
        {
            $query = $query->join('tblprodutobarra', function($join) use ($parametros) {
                $join->on('tblprodutobarra.codprodutobarra', '=', 'tblnegocioprodutobarra.codprodutobarra');
            });
            $query = $query->join('tblprodutovariacao', function($join) use ($parametros) {
                $join->on('tblprodutovariacao.codprodutovariacao', '=', 'tblprodutobarra.codprodutovariacao');
            });
            $query = $query->where('tblprodutovariacao.codproduto', '=', $parametros['negocio_codproduto']);
        }

        if (!empty($parametros['negocio_codprodutovariacao']))
            $query->where('tblprodutovariacao.codprodutovariacao', '=', $parametros['negocio_codprodutovariacao']);

        //dd($query->toSql());
        return $query->paginate($registros);

    }

}
