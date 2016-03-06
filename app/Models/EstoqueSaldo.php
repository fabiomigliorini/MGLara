<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MGLara\Models;

use Illuminate\Support\Facades\DB;

/**
 * Campos
 * @property  bigint                         $codestoquesaldo                    NOT NULL DEFAULT nextval('tblestoquesaldo_codestoquesaldo_seq'::regclass)
 * @property  bigint                         $codproduto                         NOT NULL
 * @property  boolean                        $fiscal                             NOT NULL
 * @property  numeric(14,3)                  $saldoquantidade                    
 * @property  numeric(14,2)                  $saldovalor                         
 * @property  numeric(14,6)                  $saldovalorunitario                 
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
 * @property  EstoqueLocal                   $EstoqueLocal                  
 *
 * Tabelas Filhas
 * @property  EstoqueMes[]                   $EstoqueMesS
 */

class EstoqueSaldo extends MGModel
{
    protected $table = 'tblestoquesaldo';
    protected $primaryKey = 'codestoquesaldo';
    protected $fillable = [
        'codproduto',
        'fiscal',
        'saldoquantidade',
        'saldovalor',
        'saldovalorunitario',
        'codestoquelocal',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
    ];
    
    // Chaves Estrangeiras
    public function Produto()
    {
        return $this->belongsTo(Produto::class, 'codproduto', 'codproduto');
    }

    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuarioalteracao', 'codusuario');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuariocriacao', 'codusuario');
    }

    public function EstoqueLocal()
    {
        return $this->belongsTo(EstoqueLocal::class, 'codestoquelocal', 'codestoquelocal');
    }


    // Tabelas Filhas
    public function EstoqueMesS()
    {
        return $this->hasMany(EstoqueMes::class, 'codestoquesaldo', 'codestoquesaldo')->orderBy('mes');
    }
    
    
    public static function buscaOuCria($codproduto, $codestoquelocal, $fiscal)
    {
        $es = self::where('codproduto', $codproduto)->where('codestoquelocal', $codestoquelocal)->where('fiscal', $fiscal)->first();
        if ($es == false)
        {
            $es = new EstoqueSaldo;
            $es->codproduto = $codproduto;
            $es->codestoquelocal = $codestoquelocal;
            $es->fiscal = $fiscal;
            $es->save();
        }
        return $es;
    }
    
    public static function saldoPorGrupoProduto()
    {
        
        $res = DB::select('
            select 
                  tblsubgrupoproduto.codgrupoproduto
                , tblestoquesaldo.codestoquelocal
                , tblestoquesaldo.fiscal
                , sum(tblestoquesaldo.saldoquantidade) as saldoquantidade
                , sum(tblestoquesaldo.saldovalor) as saldovalor
            from tblestoquesaldo
            left join tblproduto on (tblproduto.codproduto = tblestoquesaldo.codproduto)
            left join tblsubgrupoproduto on (tblsubgrupoproduto.codsubgrupoproduto = tblproduto.codsubgrupoproduto)
            group by 
                  tblsubgrupoproduto.codgrupoproduto
                , tblestoquesaldo.fiscal
                , tblestoquesaldo.codestoquelocal
        ');

        return $res;
    }
    
    public static function saldoPorMarca()
    {
        
        $res = DB::select('
            select 
                  tblmarca.codmarca
                , tblestoquesaldo.codestoquelocal
                , tblestoquesaldo.fiscal
                , sum(tblestoquesaldo.saldoquantidade) as saldoquantidade
                , sum(tblestoquesaldo.saldovalor) as saldovalor
            from tblestoquesaldo
            left join tblproduto on (tblproduto.codproduto = tblestoquesaldo.codproduto)
            left join tblmarca on (tblmarca.codmarca = tblproduto.codmarca)
            group by 
                  tblmarca.codmarca
                , tblestoquesaldo.fiscal
                , tblestoquesaldo.codestoquelocal
        ');

        return $res;
    }
    
    public static function saldoPorSubGrupoProduto($codgrupoproduto)
    {
        
        $res = DB::select("
            select 
                  tblsubgrupoproduto.codsubgrupoproduto
                , tblestoquesaldo.codestoquelocal
                , tblestoquesaldo.fiscal
                , sum(tblestoquesaldo.saldoquantidade) as saldoquantidade
                , sum(tblestoquesaldo.saldovalor) as saldovalor
            from tblestoquesaldo
            left join tblproduto on (tblproduto.codproduto = tblestoquesaldo.codproduto)
            left join tblsubgrupoproduto on (tblsubgrupoproduto.codsubgrupoproduto = tblproduto.codsubgrupoproduto)
            where codgrupoproduto = $codgrupoproduto
            group by 
                  tblsubgrupoproduto.codsubgrupoproduto
                , tblestoquesaldo.fiscal
                , tblestoquesaldo.codestoquelocal
        ");

        return $res;
    }

    public static function saldoPorProduto($codsubgrupoproduto)
    {
        
        $res = DB::select("
            select 
                  tblproduto.codproduto
                , tblestoquesaldo.codestoquelocal
                , tblestoquesaldo.fiscal
                , sum(tblestoquesaldo.saldoquantidade) as saldoquantidade
                , sum(tblestoquesaldo.saldovalor) as saldovalor
            from tblestoquesaldo
            left join tblproduto on (tblproduto.codproduto = tblestoquesaldo.codproduto)
            where tblproduto.codsubgrupoproduto = $codsubgrupoproduto
            group by 
                  tblproduto.codproduto
                , tblestoquesaldo.fiscal
                , tblestoquesaldo.codestoquelocal
        ");

        return $res;
    }

    public static function saldoPorProdutoMarca($codmarca)
    {
        
        $res = DB::select("
            select 
                  tblproduto.codproduto
                , tblestoquesaldo.codestoquelocal
                , tblestoquesaldo.fiscal
                , sum(tblestoquesaldo.saldoquantidade) as saldoquantidade
                , sum(tblestoquesaldo.saldovalor) as saldovalor
            from tblestoquesaldo
            left join tblproduto on (tblproduto.codproduto = tblestoquesaldo.codproduto)
            where tblproduto.codmarca = $codmarca
            group by 
                  tblproduto.codproduto
                , tblestoquesaldo.fiscal
                , tblestoquesaldo.codestoquelocal
        ");

        return $res;
    }
    
    public function calculaCustoMedio()
    {
        $inicialquantidade = 0;
        $inicialvalor = 0;
        foreach ($this->EstoqueMesS as $mes)
        {
            $sql = "
                select 
                    sum(entradaquantidade) entradaquantidade
                    , sum(entradavalor) entradavalor
                    , sum(saidaquantidade) saidaquantidade
                    , sum(saidavalor) saidavalor
                from tblestoquemovimento mov
                left join tblestoquemovimentotipo tipo on (tipo.codestoquemovimentotipo = mov.codestoquemovimentotipo)
                where mov.codestoquemes = {$mes->codestoquemes}
                and tipo.preco in (" . EstoqueMovimentoTipo::PRECO_INFORMADO . ", " . EstoqueMovimentoTipo::PRECO_ORIGEM . ")";
                
            $mov = DB::select($sql);
            $mov = $mov[0];
            
            $entradaquantidade = $mov->entradaquantidade;
            $entradavalor = $mov->entradavalor;
            $saidaquantidade = $mov->saidaquantidade;
            $saidavalor = $mov->saidavalor;
            $saldoquantidade = $inicialquantidade + $entradaquantidade - $saidaquantidade;
            $saldovalor = $inicialvalor + $entradavalor - $saidavalor;
            $saldovalorunitario = ($saldoquantidade>0)?$saldovalor/$saldoquantidade:0;
            
            
            foreach ($mes->EstoqueMovimentoS as $mov)
            {
                if ($mov->EstoqueMovimentoTipo->preco != EstoqueMovimentoTipo::PRECO_MEDIO)
                    continue;
                
                $mov->entradavalor = (!empty($mov->entradaquantidade))?round($mov->entradaquantidade * $saldovalorunitario, 2):null;
                $mov->saidavalor = (!empty($mov->saidaquantidade))?round($mov->saidaquantidade * $saldovalorunitario, 2):null;
                $mov->save();
                
                $entradaquantidade += $mov->entradaquantidade;
                $entradavalor += $mov->entradavalor;
                $saidaquantidade += $mov->saidaquantidade;
                $saidavalor += $mov->saidavalor;
                
                foreach ($mov->EstoqueMovimentoS as $movfilho)
                {
                    if ($movfilho->EstoqueMovimentoTipo->preco != EstoqueMovimentoTipo::PRECO_ORIGEM)
                        continue;
                    
                    $movfilho->entradavalor = (!empty($movfilho->entradaquantidade))?round($movfilho->entradaquantidade * $saldovalorunitario, 2):null;
                    $movfilho->saidavalor = (!empty($movfilho->saidaquantidade))?round($movfilho->saidaquantidade * $saldovalorunitario, 2):null;
                    $movfilho->save();
                }
            }
            
            
            $saldoquantidade = $inicialquantidade + $entradaquantidade - $saidaquantidade;
            $saldovalor = $inicialvalor + $entradavalor - $saidavalor;
            $saldovalorunitario = ($saldoquantidade>0)?$saldovalor/$saldoquantidade:0;

            $mes->inicialquantidade = $inicialquantidade;
            $mes->inicialvalor = $inicialvalor;
            $mes->entradaquantidade = $entradaquantidade;
            $mes->entradavalor = $entradavalor;
            $mes->saidaquantidade = $saidaquantidade;
            $mes->saidavalor = $saidavalor;
            $mes->saldoquantidade = $saldoquantidade;
            $mes->saldovalor = $saldovalor;
            $mes->saldovalorunitario = $saldovalorunitario;
            
            $mes->save();
            
            $inicialquantidade = $saldoquantidade;
            $inicialvalor = $saldovalor;
            
        }
        
        $this->saldoquantidade = $saldoquantidade;
        $this->saldovalor = $saldovalor;
        $this->saldovalorunitario = $saldovalorunitario;
        $this->save();
        
        return true;
    }

}
