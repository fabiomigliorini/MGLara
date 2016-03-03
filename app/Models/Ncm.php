<?php

namespace MGLara\Models;

class Ncm extends MGModel
{
    protected $table = 'tblncm';
    protected $primaryKey = 'codncm';
    protected $fillable = [
      'ncm',
    ];
       
    public function ProdutoS()
    {
        return $this->hasMany(Produto::class, 'codncm', 'codncm');
    }  
    
    public function Cests()
    {
        return $this->hasMany(Cest::class, 'codncm', 'codncm');
    } 
    
    public function IbptaxsS()
    {
        return $this->hasMany(Ibptax::class, 'codncm', 'codncm');
    }  

    public function NcmS()
    {
        return $this->hasMany(Ncm::class, 'codncmpai', 'codncmpai');
    }  

    public function NcmPai()
    {
        return $this->belongsTo(Ncm::class, 'codncmpai', 'codncmpai');
    }  
    
    public function RegulamentoIcmsStMtsS()
    {
        return $this->hasMany(RegulamentoIcmsStMt::class, 'codncm', 'codncm');
    } 
    
    
    /* Fim relacionamentos */
    
    /**
     * 
     * @return Cest[]
     */
    public function cestsDisponiveis()
    {
        $cests = array();
        if (sizeof($this->Cests) > 0)
            $cests = array_merge ($cests, $this->Cests);
        if (isset($this->NcmPai))
            $cests = array_merge ($cests, $this->NcmPai->cestsDisponiveis());
        return $cests;
    }

    /**
     * 
     * @return Cest[]
     */
    public function regulamentoIcmsStMtsDisponiveis()
    {
        $regs = array();
        // pega regulamentos do registro corrente
        if (sizeof($this->RegulamentoIcmsStMtsS) > 0)
            $regs = array_merge ($regs, [$this->RegulamentoIcmsStMtsS]);
        // pega regulamentos da arvore recursivamente
        if (isset($this->NcmPai))
            $regs = array_merge ($regs, $this->NcmPai->regulamentoIcmsStMtsDisponiveis());

        // apaga os Excetos
        $i = 0;
        $apagar = array();

        foreach($regs as $reg)
        {
            if (strstr($reg[$i]['ncmexceto'], $this->ncm))
                $apagar[] = $i;
            $i++;
        }

        foreach ($apagar as $i)
            unset($regs[$i]);

        return $regs;
    }
	   
    
    public function validate() {
        
        $this->_regrasValidacao = [
            'ncm' => 'required|min:2', 
        ];
    
        $this->_mensagensErro = [
            'ncm.required' => 'NCM não pode ser vazio',
        ];
        
        return parent::validate();
        
    }   
    
    
    public function scopeTributacao($query, $ncm)
    {
        if (trim($ncm) != "")
        {
            $query->where('ncm', "ILIKE", "%$ncm%");
        }
    }    
}
