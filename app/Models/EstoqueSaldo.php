<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MGLara\Models;

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

}
