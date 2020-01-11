<?php

namespace MGLara\Models;

use Carbon\Carbon;
use MGLara\Models\TributacaoNaturezaOperacao;

/**
 * Campos
 * @property  bigint                         $codnotafiscalprodutobarra          NOT NULL DEFAULT nextval('tblnotafiscalprodutobarra_codnotafiscalprodutobarra_seq'::regclass)
 * @property  bigint                         $codnotafiscal                      NOT NULL
 * @property  bigint                         $codprodutobarra                    NOT NULL
 * @property  bigint                         $codcfop                            NOT NULL
 * @property  varchar(100)                   $descricaoalternativa
 * @property  numeric(14,3)                  $quantidade                         NOT NULL
 * @property  numeric(14,3)                  $valorunitario                      NOT NULL
 * @property  numeric(14,2)                  $valortotal                         NOT NULL
 * @property  numeric(14,2)                  $icmsbase
 * @property  numeric(14,2)                  $icmspercentual
 * @property  numeric(14,2)                  $icmsvalor
 * @property  numeric(14,2)                  $ipibase
 * @property  numeric(14,2)                  $ipipercentual
 * @property  numeric(14,2)                  $ipivalor
 * @property  numeric(14,2)                  $icmsstbase
 * @property  numeric(14,2)                  $icmsstpercentual
 * @property  numeric(14,2)                  $icmsstvalor
 * @property  varchar(4)                     $csosn
 * @property  bigint                         $codnegocioprodutobarra
 * @property  timestamp                      $alteracao
 * @property  bigint                         $codusuarioalteracao
 * @property  timestamp                      $criacao
 * @property  bigint                         $codusuariocriacao
 * @property  numeric(3,0)                   $icmscst
 * @property  numeric(3,0)                   $ipicst
 * @property  numeric(3,0)                   $piscst
 * @property  numeric(14,2)                  $pisbase
 * @property  numeric(4,2)                   $pispercentual
 * @property  numeric(14,2)                  $pisvalor
 * @property  numeric(3,0)                   $cofinscst
 * @property  numeric(14,2)                  $cofinsbase
 * @property  numeric(14,2)                  $cofinsvalor
 * @property  numeric(14,2)                  $csllbase
 * @property  numeric(4,2)                   $csllpercentual
 * @property  numeric(14,2)                  $csllvalor
 * @property  numeric(14,2)                  $irpjbase
 * @property  numeric(4,2)                   $irpjpercentual
 * @property  numeric(14,2)                  $irpjvalor
 * @property  numeric(4,2)                   $cofinspercentual
 * @property  bigint                         $codnotafiscalprodutobarraorigem
 *
 * Chaves Estrangeiras
 * @property  Cfop                           $Cfop
 * @property  NegocioProdutoBarra            $NegocioProdutoBarra
 * @property  NotaFiscal                     $NotaFiscal
 * @property  NotaFiscalProdutoBarraOrigem   $NotaFiscalProdutoBarra
 * @property  ProdutoBarra                   $ProdutoBarra
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 * @property  EstoqueMovimento[]             $EstoqueMovimentoS
 * @property  NotaFiscalProdutoBarra[]       $NotaFiscalProdutoBarraS
 */


class NotaFiscalProdutoBarra extends MGModel
{
    protected $table = 'tblnotafiscalprodutobarra';
    protected $primaryKey = 'codnotafiscalprodutobarra';
    protected $fillable = [
        'codnotafiscal',
        'codprodutobarra',
        'codcfop',
        'descricaoalternativa',
        'quantidade',
        'valorunitario',
        'valortotal',
        'icmsbase',
        'icmspercentual',
        'icmsvalor',
        'ipibase',
        'ipipercentual',
        'ipivalor',
        'icmsstbase',
        'icmsstpercentual',
        'icmsstvalor',
        'csosn',
        'codnegocioprodutobarra',
        'icmscst',
        'ipicst',
        'piscst',
        'pisbase',
        'pispercentual',
        'pisvalor',
        'cofinscst',
        'cofinsbase',
        'cofinsvalor',
        'csllbase',
        'csllpercentual',
        'csllvalor',
        'irpjbase',
        'irpjpercentual',
        'irpjvalor',
        'cofinspercentual',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
    ];

    // Chaves Estrangeiras
    public function Cfop()
    {
        return $this->belongsTo(Cfop::class, 'codcfop', 'codcfop');
    }

    public function NegocioProdutoBarra()
    {
        return $this->belongsTo(NegocioProdutoBarra::class, 'codnegocioprodutobarra', 'codnegocioprodutobarra');
    }

    public function NotaFiscal()
    {
        return $this->belongsTo(NotaFiscal::class, 'codnotafiscal', 'codnotafiscal');
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

    public function NotaFiscalProdutoBarraOrigem()
    {
        return $this->belongsTo(NotaFiscalProdutoBarra::class, 'codnotafiscalprodutobarraorigem', 'codnotafiscalprodutobarra');
    }


    // Tabelas Filhas
    public function EstoqueMovimentoS()
    {
        return $this->hasMany(EstoqueMovimento::class, 'codnotafiscalprodutobarra', 'codnotafiscalprodutobarra');
    }

    public function NotaFiscalProdutoBarraS()
    {
        return $this->hasMany(NotaFiscalProdutoBarra::class, 'codnotafiscalprodutobarraorigem');
    }

    public static function search($parametros, $registros = 20)
    {
        //dd($parametros);
        $query = NotaFiscalProdutoBarra::orderBy('tblnotafiscal.saida', 'DESC');

        $query = $query->join('tblnotafiscal', function ($join) use ($parametros) {
            $join->on('tblnotafiscal.codnotafiscal', '=', 'tblnotafiscalprodutobarra.codnotafiscal');
        });

        if (!empty($parametros['notasfiscais_codnaturezaoperacao'])) {
            $query = $query->where('tblnotafiscal.codnaturezaoperacao', '=', $parametros['notasfiscais_codnaturezaoperacao']);
        }

        if (!empty($parametros['notasfiscais_codpessoa'])) {
            $query = $query->where('tblnotafiscal.codpessoa', '=', $parametros['notasfiscais_codpessoa']);
        }

        if (!empty($parametros['notasfiscais_codfilial'])) {
            $query = $query->where('tblnotafiscal.codfilial', '=', $parametros['notasfiscais_codfilial']);
        }

        if (!empty($parametros['notasfiscais_lancamento_de'])) {
            $query = $query->where('tblnotafiscal.saida', '>=', $parametros['notasfiscais_lancamento_de']);
        }

        if (!empty($parametros['notasfiscais_lancamento_ate'])) {
            $query = $query->where('tblnotafiscal.saida', '<=', $parametros['notasfiscais_lancamento_ate']);
        }

        if (!empty($parametros['notasfiscais_codproduto'])) {
            $query = $query->join('tblprodutobarra', function ($join) use ($parametros) {
                $join->on('tblprodutobarra.codprodutobarra', '=', 'tblnotafiscalprodutobarra.codprodutobarra');
            });

            $query = $query->join('tblprodutovariacao', function ($join) use ($parametros) {
                $join->on('tblprodutovariacao.codprodutovariacao', '=', 'tblprodutobarra.codprodutovariacao');
            });

            $query = $query->where('tblprodutovariacao.codproduto', '=', $parametros['notasfiscais_codproduto']);
        }

        if (!empty($parametros['notasfiscais_codprodutovariacao'])) {
            $query->where('tblprodutovariacao.codprodutovariacao', '=', $parametros['notasfiscais_codprodutovariacao']);
        }

        //dd($query->toSql());

        return $query->paginate($registros);
    }

    public function getValortotalfinalAttribute()
    {
        return $this->valortotal
          - $this->valordesconto
          + $this->valorfrete
          + $this->valorseguro
          + $this->valoroutras;
    }

    public function calculaTributacao()
    {
        $trib = TributacaoNaturezaOperacao
                ::where('codtributacao', $this->ProdutoBarra->Produto->codtributacao)
                ->where('codtipoproduto', $this->ProdutoBarra->Produto->codtipoproduto)
                ->where('bit', $this->ProdutoBarra->Produto->bit)
                ->where('codnaturezaoperacao', $this->NotaFiscal->codnaturezaoperacao)
                ->whereRaw("('{$this->ProdutoBarra->Produto->Ncm->ncm}' ilike ncm || '%' or ncm is null)");

        if ($this->NotaFiscal->Pessoa->Cidade->codestado == $this->NotaFiscal->Filial->Pessoa->Cidade->codestado) {
            $trib->where('codestado', $this->NotaFiscal->Pessoa->Cidade->codestado);
            $filtroEstado = 'codestado = :codestado';
        } else {
            $trib->whereNull('codestado');
        }

        if (!($trib = $trib->first())) {
            echo '<h1>Erro Ao Calcular Tributacao</h1>';
            dd($this);
            return false;
        }

        //Traz codigos de tributacao
        $this->codcfop = $trib->codcfop;

        if ($this->NotaFiscal->Filial->crt == Filial::CRT_REGIME_NORMAL) {

            //CST's
            $this->icmscst = $trib->icmscst;
            $this->ipicst = $trib->ipicst;
            $this->piscst = $trib->piscst;
            $this->cofinscst = $trib->cofinscst;

            if (!empty($this->valortotalfinal) && ($this->NotaFiscal->emitida)) {
                //Calcula ICMS
                if (!empty($trib->icmslpbase)) {
                    $this->icmsbasepercentual = $trib->icmslpbase;
                    $this->icmsbase = round(($this->icmsbasepercentual * $this->valortotalfinal)/100, 2);
                }

                $this->icmspercentual = $trib->icmslppercentual;

                if ((!empty($this->icmsbase)) and (!empty($this->icmspercentual))) {
                    $this->icmsvalor = round(($this->icmsbase * $this->icmspercentual)/100, 2);
                }

                //Calcula PIS
                if ($trib->pispercentual > 0) {
                    $this->pisbase = $this->valortotalfinal;
                    $this->pispercentual = $trib->pispercentual;
                    $this->pisvalor = round(($this->pisbase * $this->pispercentual)/100, 2);
                }

                //Calcula Cofins
                if ($trib->cofinspercentual > 0) {
                    $this->cofinsbase = $this->valortotalfinal;
                    $this->cofinspercentual = $trib->cofinspercentual;
                    $this->cofinsvalor = round(($this->cofinsbase * $this->cofinspercentual)/100, 2);
                }

                //Calcula CSLL
                if ($trib->csllpercentual > 0) {
                    $this->csllbase = $this->valortotalfinal;
                    $this->csllpercentual = $trib->csllpercentual;
                    $this->csllvalor = round(($this->csllbase * $this->csllpercentual)/100, 2);
                }

                //Calcula IRPJ
                if ($trib->irpjpercentual > 0) {
                    $this->irpjbase = $this->valortotalfinal;
                    $this->irpjpercentual = $trib->irpjpercentual;
                    $this->irpjvalor = round(($this->irpjbase * $this->irpjpercentual)/100, 2);
                }
            }
        } else {
            $this->csosn = $trib->csosn;

            //Calcula ICMSs
            if (!empty($this->valortotalfinal) && ($this->NotaFiscal->emitida)) {
                if (!empty($trib->icmsbase)) {
                    $this->icmsbase = round(($trib->icmsbase * $this->valortotalfinal)/100, 2);
                }

                $this->icmspercentual = $trib->icmspercentual;

                if ((!empty($this->icmsbase)) and (!empty($this->icmspercentual))) {
                    $this->icmsvalor = round(($this->icmsbase * $this->icmspercentual)/100, 2);
                }
            }
        }
    }
}
