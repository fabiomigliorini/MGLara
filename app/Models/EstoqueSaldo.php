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
 * @property  bigint                         $codproduto                         NOT NULL
 * @property  boolean                        $fiscal                             NOT NULL
 * @property  numeric(14,3)                  $saldoquantidade                    
 * @property  numeric(14,2)                  $saldovalor                         
 * @property  numeric(14,6)                  $customedio                 
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
 * @property  EstoqueLocalProduto            $EstoqueLocalProduto
 *
 * Tabelas Filhas
 * @property  EstoqueMes[]                   $EstoqueMesS
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
        'codestoquelocalproduto',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
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

    public function EstoqueLocalProduto()
    {
        return $this->belongsTo(EstoqueLocalProduto::class, 'codestoquelocalproduto', 'codestoquelocalproduto');
    }


    // Tabelas Filhas
    public function EstoqueMesS()
    {
        return $this->hasMany(EstoqueMes::class, 'codestoquesaldo', 'codestoquesaldo');
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
    
    public function recalculaCustoMedio()
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
            
            $customedio = null;
            if (($entradaquantidade + $inicialquantidade) > 0)
                $customedio = ($entradavalor + $inicialvalor)/($entradaquantidade + $inicialquantidade);
            
            
            foreach ($mes->EstoqueMovimentoS as $mov)
            {
                if ($mov->EstoqueMovimentoTipo->preco != EstoqueMovimentoTipo::PRECO_MEDIO)
                    continue;
                
                $mov->entradavalor = (!empty($mov->entradaquantidade))?round($mov->entradaquantidade * $customedio, 2):null;
                $mov->saidavalor = (!empty($mov->saidaquantidade))?round($mov->saidaquantidade * $customedio, 2):null;
                $mov->save();
                
                $entradaquantidade += $mov->entradaquantidade;
                $entradavalor += $mov->entradavalor;
                $saidaquantidade += $mov->saidaquantidade;
                $saidavalor += $mov->saidavalor;
                
                foreach ($mov->EstoqueMovimentoS as $movfilho)
                {
                    if ($movfilho->EstoqueMovimentoTipo->preco != EstoqueMovimentoTipo::PRECO_ORIGEM)
                        continue;
                    
                    $movfilho->entradavalor = (!empty($movfilho->entradaquantidade))?round($movfilho->entradaquantidade * $customedio, 2):null;
                    $movfilho->saidavalor = (!empty($movfilho->saidaquantidade))?round($movfilho->saidaquantidade * $customedio, 2):null;
                    $movfilho->save();
                }
            }
            
            $saldoquantidade = $inicialquantidade + $entradaquantidade - $saidaquantidade;
            $saldovalor = $inicialvalor + $entradavalor - $saidavalor;
            
            $customedio = null;
            if (($entradaquantidade + $inicialquantidade) > 0)
                $customedio = ($entradavalor + $inicialvalor)/($entradaquantidade + $inicialquantidade);

            if ($saldoquantidade == 0)
                $saldovalor = 0;
            
            $mes->inicialquantidade = $inicialquantidade;
            $mes->inicialvalor = $inicialvalor;
            $mes->entradaquantidade = $entradaquantidade;
            $mes->entradavalor = $entradavalor;
            $mes->saidaquantidade = $saidaquantidade;
            $mes->saidavalor = $saidavalor;
            $mes->saldoquantidade = $saldoquantidade;
            $mes->saldovalor = $saldovalor;
            $mes->customedio = $customedio;
            
            $mes->save();
            
            $inicialquantidade = $saldoquantidade;
            $inicialvalor = $saldovalor;
            
        }
        
        $this->saldoquantidade = $saldoquantidade;
        $this->saldovalor = $saldovalor;
        $this->customedio = $customedio;
        $this->save();
        
        return true;
    }
    
    
    /**
     * 
     * @param EstoqueSaldo $destino
     * @param double $quantidade
     */
    public function transfere(EstoqueSaldo $destino, $quantidade)
    {
        $data = Carbon::create($year = 2015, $month = 12, $day = 31, $hour = 23, $minute = 59, $second = 59);
        $emOrigem = EstoqueMes::buscaOuCria($this->codproduto, $this->codestoquelocal, $this->fiscal, $data);
        $emDestino = EstoqueMes::buscaOuCria($destino->codproduto, $destino->codestoquelocal, $destino->fiscal, $data);
        $data = Carbon::create($year = 2015, $month = 12, $day = 31, $hour = 23, $minute = 59, $second = 59);
        
        $movOrigem = new EstoqueMovimento();
        $movOrigem->codestoquemes = $emOrigem->codestoquemes;
        $movOrigem->data = $data;
        $movOrigem->codestoquemovimentotipo = 4101; // Transferencia Saida
        $movOrigem->manual = true;
        $movOrigem->saidaquantidade = $quantidade;
        $movOrigem->saidavalor = $quantidade * $emOrigem->customedio;
        $ret = $movOrigem->save();
        
        if ($ret)
        {
            $movDestino = new EstoqueMovimento();
            $movDestino->codestoquemes = $emDestino->codestoquemes;
            $movDestino->data = $data;
            $movDestino->codestoquemovimentotipo = 4201; // Transferencia Entrada
            $movDestino->codestoquemovimentoorigem = $movOrigem->codestoquemovimento;
            $movDestino->manual = true;
            $movDestino->entradaquantidade = $quantidade;
            $movDestino->entradavalor = $quantidade * $emOrigem->customedio;
            $ret = $movDestino->save();
        }
        
        return ($ret);

    }
    
    public function zera()
    {
        
        if ($this->saldoquantidade == 0 && $this->saldovalor == 0)
            return false;
        
        $data = Carbon::create($year = 2015, $month = 12, $day = 31, $hour = 23, $minute = 59, $second = 59);
        $mes = EstoqueMes::buscaOuCria($this->codproduto, $this->codestoquelocal, $this->fiscal, $data);
        $mov = new EstoqueMovimento();
        $data = Carbon::create($year = 2015, $month = 12, $day = 31, $hour = 23, $minute = 59, $second = 59);
        
        $mov->codestoquemes = $mes->codestoquemes;
        $mov->data = $data;
        $mov->codestoquemovimentotipo = 1002; //"Ajuste"
        $mov->manual = true;
        
        if ($this->saldoquantidade > 0)
            $mov->saidaquantidade = $this->saldoquantidade;
        elseif ($this->saldoquantidade < 0)
            $mov->entradaquantidade = abs($this->saldoquantidade);
        
        if ($this->saldovalor > 0)
            $mov->saidavalor = $this->saldovalor;
        elseif ($this->saldovalor < 0)
            $mov->entradavalor = abs($this->saldovalor);
        
        $ret = $mov->save();
        
        $this->recalculaCustoMedio();
        
        return $ret;
        
    }

    public function scopeFiscal($query, $fiscal)
    {
        if ($fiscal)
            $query->where('fiscal', true);
        else
            $query->where('fiscal', false);
    } 

    public function scopeLocal($query, $EstoqueLocal)
    {
        if (gettype($EstoqueLocal) == 'integer')
            $query->where('codestoquelocal', $EstoqueLocal);
        else
            $query->where('codestoquelocal', $EstoqueLocal->codestoquelocal);
    } 
    
}
