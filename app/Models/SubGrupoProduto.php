<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codsubgrupoproduto                 NOT NULL DEFAULT nextval('tblsubgrupoproduto_codsubgrupoproduto_seq'::regclass)
 * @property  bigint                         $codgrupoproduto                    
 * @property  varchar(50)                    $subgrupoproduto                    
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 *
 * Chaves Estrangeiras
 * @property  GrupoProduto                   $GrupoProduto                  
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 * @property  Imagem                         $Imagem  
 *
 * Tabelas Filhas
 * @property  Produto[]                      $ProdutoS
 */

class SubGrupoProduto extends MGModel
{
    protected $table = 'tblsubgrupoproduto';
    protected $primaryKey = 'codsubgrupoproduto';
    protected $fillable = [
        'codgrupoproduto',
        'subgrupoproduto',
    ];
    protected $dates = [
        'inativo',
        'alteracao',
        'criacao',
    ];

    public function validate() {

        $this->_regrasValidacao = [
            'codgrupoproduto' => "required|numeric", 
            'subgrupoproduto' => "required|min:3|uniqueMultiple:tblsubgrupoproduto,codsubgrupoproduto,$this->codsubgrupoproduto,subgrupoproduto,codgrupoproduto,$this->codgrupoproduto",
        ];    
        $this->_mensagensErro = [
            'subgrupoproduto.required' => 'Sub grupo de produto nao pode ser vazio!',
            'subgrupoproduto.unique_multiple' => 'Este Sub grupo de produto já esta cadastrado nesse grupo!',
            'subgrupoproduto.min' => 'Sub grupo de produto nao pode ter menos de 2 caracteres!',
        ];
        return parent::validate();
    }    


    // Chaves Estrangeiras
    public function GrupoProduto()
    {
        return $this->belongsTo(GrupoProduto::class, 'codgrupoproduto', 'codgrupoproduto');
    }

    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuarioalteracao', 'codusuario');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuariocriacao', 'codusuario');
    }
    
    public function Imagem()
    {
        return $this->belongsTo(Imagem::class, 'codimagem', 'codimagem');
    }

    // Tabelas Filhas
    public function ProdutoS()
    {
        return $this->hasMany(Produto::class, 'codsubgrupoproduto', 'codsubgrupoproduto')->orderBy('produto');
    }

    public static function select2($codfamiliaproduto)
    {
        $subgrupos = SubGrupoProduto::join('tblgrupoproduto', 'tblgrupoproduto.codgrupoproduto', '=', 'tblsubgrupoproduto.codgrupoproduto')
            ->where('tblgrupoproduto.codfamiliaproduto', $codfamiliaproduto)
                ->orderBy('tblsubgrupoproduto.codgrupoproduto')
            ->paginate(20);
        
        $retorno = [];
        foreach ($subgrupos as $subgrupo)
        {
            $retorno[] =  [
                'id' => $subgrupo->codsubgrupoproduto,
                'subgrupoproduto' => $subgrupo->GrupoProduto->grupoproduto .' » '. $subgrupo->subgrupoproduto
            ];
        }

        return $retorno;
    }
    
    public static function search($parametros, $registros = 20)
    {
        $query = SubGrupoProduto::orderBy('subgrupoproduto', 'ASC');

        if(isset($parametros['codgrupoproduto']))
            $query->where('codgrupoproduto', $parametros['codgrupoproduto']);
        
        if(isset($parametros['codsubgrupoproduto']))
            $query->id($parametros['codsubgrupoproduto']);
        
        if(isset($parametros['subgrupoproduto']))
            $query->subGrupoProduto($parametros['subgrupoproduto']);
        
        switch (isset($parametros['ativo'])?$parametros['ativo']:'9')
        {
            case 1: //Ativos
                $query->ativo();
                break;
            case 2: //Inativos
                $query->inativo();
                break;
            case 9; //Todos
            default:
        }

        
        return $query->paginate($registros);
    }

    public function scopeId($query, $id)
    {
        if (trim($id) === '')
            return;
        
        $query->where('codsubgrupoproduto', $id);
    }
    
    public function scopeSubGrupoProduto($query, $subgrupoproduto)
    {
        if (trim($subgrupoproduto) === '')
            return;
        
        $subgrupoproduto = explode(' ', $subgrupoproduto);
        foreach ($subgrupoproduto as $str)
            $query->where('subgrupoproduto', 'ILIKE', "%$str%");
    }    

    public function scopeInativo($query)
    {
        $query->whereNotNull('inativo');
    }

    public function scopeAtivo($query)
    {
        $query->whereNull('inativo');
    }
    
}
