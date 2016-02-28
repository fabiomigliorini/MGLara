<?php

namespace MGLara\Models;

class EstoqueLocal extends MGModel
{
    protected $table = 'tblestoquelocal';
    protected $primaryKey = 'codestoquelocal';
    protected $fillable = [
		'estoquelocal',
		'codfilial',
		'inativo'
    ];
    
    public function Filial()
    {
		return $this->belongsTo(Filial::class, 'codfilial', 'codfilial');
    }   

	public function EstoqueSaldoS()
    {
        return $this->hasMany(EstoqueSaldo::class, 'codestoquelocal', 'codestoquelocal');
    }  
	
	public function NegocioS()
    {
        return $this->hasMany(Negocio::class, 'codestoquelocal', 'codestoquelocal');
    }  
	
	public function NotaFiscalS()
    {
        return $this->hasMany(NotaFiscal::class, 'codestoquelocal', 'codestoquelocal');
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
    public static function filterAndPaginate($codestoquelocal)
    {
        return EstoqueMes::codestoquelocal($codestoquelocal)
            ->orderBy('estoquelocal', 'ASC')
            ->paginate(20);
    }
    
    public function scopeCodestoquelocal($query, $codestoquelocal)
    {
        if ($codestoquelocal)
        {
            $query->where('codestoquelocal', "$codestoquelocal");
        }
    }   
}
