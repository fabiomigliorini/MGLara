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
        return $this->belongsTo(Usuario::class, 'codusuarioalteracao', 'codusuario');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuariocriacao', 'codusuario');
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
        return $this->hasMany(Ncm::class, 'codncmpai', 'codncm')->orderBy('ncm', 'ASC');
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
        if (sizeof($this->CestS) > 0) {
            $cests = array_merge($cests, $this->CestS->toArray());
        }

        if ($this->Ncm) {
            $cests = array_merge($cests, $this->Ncm->cestsDisponiveis());
        }

        return $cests;
    }

    /**
     * 
     * @return Cest[]
     */
    public function regulamentoIcmsStMtsDisponiveis()
    {
        $regs = [];

        // pega regulamentos do registro corrente
        if (sizeof($this->RegulamentoIcmsStMtS) > 0)
            $regs = array_merge ($regs, $this->RegulamentoIcmsStMtS->toArray());

        // pega regulamentos da arvore recursivamente
        if ($this->Ncm)
            $regs = array_merge ($regs, $this->Ncm->regulamentoIcmsStMtsDisponiveis());


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
        $sql = Ncm::buscancm($q)
            ->select('codncm as id', 'ncm', 'descricao')
            ->orderBy('ncm', 'ASC')
            ->paginate(10);
        
        $itens = [];
        foreach ($sql as $ncm)
        {
            $itens[] = [
                'id' => $ncm->id,
                'ncm' => formataNcm($ncm->ncm),
                'descricao' => $ncm->descricao
            ];
        }
        
        return response()->json(['data' => $itens]);
    }

    public static function search($parametros)
    {
        $query = Ncm::query();
        
        if (!empty($parametros['codncmpai'])) {
            $query->whereNull('codncmpai');
        }

        if (!empty($parametros['ncm'])) {

            $numero = numeroLimpo(trim($parametros['ncm']));
            $query->where('descricao', 'ILIKE', "%{$parametros['ncm']}%");

            if (!empty($numero)) {
                $query->orWhere('ncm', 'ILIKE', "%$numero%");
            }
        }

        return $query;
    }
    
    
    public function scopeBuscaNcm($query, $q)
    {
        if (trim($q) === '')
            return;

        $numero = NumeroLimpo(trim($q));
        $query->where('descricao', 'ILIKE', "%$q%");
        
        if (!empty($numero))
            $query->orWhere('ncm', 'ILIKE', "%$numero%");
    }   
}
