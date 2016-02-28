<?php

namespace MGLara\Models;

class EstoqueMes extends MGModel
{
    protected $table = 'tblestoquemes';
    protected $primaryKey = 'codestoquemes';
    protected $fillable = [
      'codestoquesaldo',
      'mes',
    ];
    protected $dates = ['mes'];
    
    public function EstoqueMovimentoS()
    {
        return $this->hasMany(EstoqueMovimento::class, 'codestoquemes', 'codestoquemes');
    }    
    
    public function EstoqueSaldo()
    {
        return $this->belongsTo(EstoqueSaldo::class, 'codestoquesaldo', 'codestoquesaldo');
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
    
    public static function buscaOuCria($codproduto, $codestoquelocal, $fiscal, $data)
    {
        $es = EstoqueSaldo::buscaOuCria($codproduto, $codestoquelocal, $fiscal);
        $data->day = 1;
        $em = self::where('codestoquesaldo', $es->codestoquesaldo)->where('mes', $data)->first();
        if ($em == false)
        {
            $em = new EstoqueMes;
            $em->codestoquesaldo = $es->codestoquesaldo;
            $em->mes = $data;
            $em->save();
        }
        return $em;
        
    }
}
