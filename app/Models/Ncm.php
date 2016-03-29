<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codncm                             NOT NULL DEFAULT nextval('tblncm_codncm_seq'::regclass)
 * @property  varchar(10)                    $ncm                                NOT NULL
 * @property  varchar(1500)                  $descricao                          NOT NULL
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 * @property  bigint                         $codncmpai                          
 *
 * Chaves Estrangeiras
 * @property  Ncm                            $Ncm                           
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 * @property  Cest[]                         $CestS
 * @property  Ibptax[]                       $IbptaxS
 * @property  Ncm[]                          $NcmS
 * @property  Produto[]                      $ProdutoS
 * @property  RegulamentoIcmsStMt[]          $RegulamentoIcmsStMtS
 */

class Ncm extends MGModel
{
    protected $table = 'tblncm';
    protected $primaryKey = 'codncm';
    protected $fillable = [
        'ncm',
        'descricao',
        'codncmpai',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
    ];


    // Chaves Estrangeiras
    public function Ncm()
    {
        return $this->belongsTo(Ncm::class, 'codncm', 'codncmpai');
    }

    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuario', 'codusuarioalteracao');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuario', 'codusuariocriacao');
    }


    // Tabelas Filhas
    public function CestS()
    {
        return $this->hasMany(Cest::class, 'codncm', 'codncm');
    }

    public function IbptaxS()
    {
        return $this->hasMany(Ibptax::class, 'codncm', 'codncm');
    }

    public function NcmS()
    {
        return $this->hasMany(Ncm::class, 'codncm', 'codncmpai');
    }

    public function ProdutoS()
    {
        return $this->hasMany(Produto::class, 'codncm', 'codncm');
    }

    public function RegulamentoIcmsStMtS()
    {
        return $this->hasMany(RegulamentoIcmsStMt::class, 'codncm', 'codncm');
    }
       
    
    /*
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
    */
    
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
    
    public static function select2($q)
    {
        $sql = Ncm::q($q)
            ->select('codncm as id', 'ncm', 'descricao')
            ->orderBy('ncm', 'ASC')
            ->paginate(10);
        return response()->json($sql);
    }    
    
    public function scopeQ($query, $q)
    {
        if (trim($q) === '')
            return;

        $query->where('descricao', 'ILIKE', "%$q%");
    }   
}
