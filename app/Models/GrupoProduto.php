<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codgrupoproduto                    NOT NULL DEFAULT nextval('tblgrupoproduto_codgrupoproduto_seq'::regclass)
 * @property  varchar(50)                    $grupoproduto                       
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 *
 * Chaves Estrangeiras
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 * @property  FamiliaProduto                 $FamiliaProduto                
 * @property  Imagem                         $Imagem     
 *
 * Tabelas Filhas
 * @property  SubGrupoProduto[]              $SubGrupoProdutoS
 */

class GrupoProduto extends MGModel
{
    protected $table = 'tblgrupoproduto';
    protected $primaryKey = 'codgrupoproduto';
    protected $fillable = [
        'grupoproduto',
    ];
    protected $dates = [
        'inativo',
        'alteracao',
        'criacao',
    ];

    public function validate() {
        
        $this->_regrasValidacao = [
            'grupoproduto' => "required|min:3|uniqueMultiple:tblgrupoproduto,codgrupoproduto,$this->codgrupoproduto,grupoproduto,codfamiliaproduto,$this->codfamiliaproduto",
        ];    
        $this->_mensagensErro = [
            'grupoproduto.required' => 'Grupo de produto nao pode ser vazio!',
            'grupoproduto.unique_multiple' => 'Este Grupo de produto ja esta cadastrado nessa família!',
            'grupoproduto.min' => 'Grupo de produto deve ter mais de 2 caracteres!',
        ];

        return parent::validate();
    }    

    // Chaves Estrangeiras
    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuarioalteracao', 'codusuario');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuariocriacao', 'codusuario');
    }

    public function FamiliaProduto()
    {
        return $this->belongsTo(FamiliaProduto::class, 'codfamiliaproduto', 'codfamiliaproduto');
    }

    public function Imagem()
    {
        return $this->belongsTo(Imagem::class, 'codimagem', 'codimagem');
    }

    // Tabelas Filhas
    public function SubGrupoProdutoS()
    {
        return $this->hasMany(SubGrupoProduto::class, 'codgrupoproduto', 'codgrupoproduto')->orderBy('subgrupoproduto');
    }
    
    public static function search($parametros, $registros = 20)
    {
        $query = GrupoProduto::orderBy('grupoproduto', 'ASC');

        if(isset($parametros['codfamiliaproduto']))
            $query->where('codfamiliaproduto', $parametros['codfamiliaproduto']);
        
        if(isset($parametros['codgrupoproduto']))
            $query->id($parametros['codgrupoproduto']);
        
        if(isset($parametros['grupoproduto']))
            $query->grupoProduto($parametros['grupoproduto']);
        
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
        
        $query->where('codgrupoproduto', $id);
    }
    
    public function scopeGrupoProduto($query, $grupoproduto)
    {
        if (trim($grupoproduto) === '')
            return;
        
        $grupoproduto = explode(' ', $grupoproduto);
        foreach ($grupoproduto as $str)
            $query->where('grupoproduto', 'ILIKE', "%$str%");
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