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
        return $this->belongsTo(Ncm::class, 'codncmpai', 'codncm');
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
        $cests = [];
        if ($this->Ncm)
            $cests = array_merge ($cests, $this->Ncm->cestsDisponiveis());

        if (sizeof($this->CestS) > 0)
            array_push ($cests, $this->CestS);
        
        return $cests;
    }

    /**
     * 
     * @return Cest[]
     */
    public function regulamentoIcmsStMtsDisponiveis()
    {
        $regs = [];

        // pega regulamentos da arvore recursivamente
        if ($this->Ncm)
            $regs = array_merge ($regs, $this->Ncm->regulamentoIcmsStMtsDisponiveis());

        // pega regulamentos do registro corrente
        if (sizeof($this->RegulamentoIcmsStMtS) > 0)
            array_push ($regs, $this->RegulamentoIcmsStMtS);

        // apaga os Excetos
        /*
        $i = 0;
        $apagar = array();
        foreach($regs as $reg)
        {
            //dd($reg);
            if (strstr($reg->ncmexceto, $this->ncm))
                $apagar[] = $i;
            $i++;
        }

        foreach ($apagar as $i)
            unset($regs[$i]);
        */
        
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
