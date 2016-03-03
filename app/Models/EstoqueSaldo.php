<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MGLara\Models;

use Illuminate\Support\Facades\DB;

/**
 * Description of EstoqueSaldo
 *
 * @author escmig05
 */
class EstoqueSaldo extends MGModel
{
    protected $table = 'tblestoquesaldo';
    protected $primaryKey = 'codestoquesaldo';
    protected $fillable = [
      'codproduto',
      'codestoquelocal',
      'fiscal',
    ];
    
    public function EstoqueMesS()
    {
        return $this->hasMany(EstoqueMes::class, 'codestoquesaldo', 'codestoquesaldo');
    }
	
	public function EstoqueLocal()
    {
        return $this->belongsTo(EstoqueLocal::class, 'codestoquelocal', 'codestoquelocal');
    }      

	public function Produto()
    {
        return $this->belongsTo(Produto::class, 'codproduto', 'codproduto');
    }      

    public function validate() {
        
        $this->_regrasValidacao = [
            //'field' => 'required|min:2', 
        ];
    
        $this->_mensagensErro = [
            //'field.required' => 'Preencha o campo',
        ];
        
        return parent::validate();
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

}
