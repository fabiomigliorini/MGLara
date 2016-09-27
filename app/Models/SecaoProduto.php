<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codsecaoproduto                    NOT NULL DEFAULT nextval('tblsecaoproduto_codsecaoproduto_seq'::regclass)
 * @property  varchar(50)                    $secaoproduto                       NOT NULL
 * @property  timestamp                      $inativo                            
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 * @property  bigint                         $codimagem                          
 *
 * Chaves Estrangeiras
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 * @property  Imagem                         $Imagem                        
 *
 * Tabelas Filhas
 * @property  FamiliaProduto[]               $FamiliaProdutoS
 */

class SecaoProduto extends MGModel
{
    protected $table = 'tblsecaoproduto';
    protected $primaryKey = 'codsecaoproduto';
    protected $fillable = [
        'secaoproduto',
        'inativo',
        'codimagem',
    ];
    protected $dates = [
        'inativo',
        'alteracao',
        'criacao',
    ];

    public function validate() {
        if ($this->codsecaoproduto) {
            $unique_secaoproduto = 'unique:tblsecaoproduto,secaoproduto,'.$this->codsecaoproduto.',codsecaoproduto';
        } else {
            $unique_secaoproduto = 'unique:tblsecaoproduto,secaoproduto';
        }           
        
        $this->_regrasValidacao = [
            'secaoproduto' => "required|$unique_secaoproduto",  
        ];
    
        $this->_mensagensErro = [
            'secaoproduto.required' => 'O campo Seção não pode ser vazio',
            'secaoproduto.unique' => 'Esta Seção já esta cadastrada',
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

    public function Imagem()
    {
        return $this->belongsTo(Imagem::class, 'codimagem', 'codimagem');
    }


    // Tabelas Filhas
    public function FamiliaProdutoS()
    {
        return $this->hasMany(FamiliaProduto::class, 'codsecaoproduto', 'codsecaoproduto');
    }
    
    public static function search($parametros)
    {
        $query = SecaoProduto::orderBy('secaoproduto', 'ASC');
        
        if(!empty($parametros['codsecaoproduto']))
            $query->id($parametros['codsecaoproduto']);
        
        if(!empty($parametros['secaoproduto']))
            $query->secaoProduto($parametros['secaoproduto']);
        
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

        
        return $query;
    }

    public function scopeId($query, $id)
    {
        if (trim($id) === '')
            return;
        
        $query->where('codsecaoproduto', $id);
    }
    
    public function scopeSecaoProduto($query, $secaoproduto)
    {
        if (trim($secaoproduto) === '')
            return;
        
        $secaoproduto = explode(' ', removeAcentos($secaoproduto));
        foreach ($secaoproduto as $str)
            $query->where('secaoproduto', 'ILIKE', "%$str%");
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
