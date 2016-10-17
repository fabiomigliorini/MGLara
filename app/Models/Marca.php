<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codmarca                           NOT NULL DEFAULT nextval('tblmarca_codmarca_seq'::regclass)
 * @property  varchar(50)                    $marca                              
 * @property  boolean                        $site                               NOT NULL DEFAULT false
 * @property  varchar(1024)                  $descricaosite                      
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 *
 * Chaves Estrangeiras
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 * @property  ProdutoBarra[]                 $ProdutoBarraS
 * @property  Produto[]                      $ProdutoS
 */

class Marca extends MGModel
{
    protected $table = 'tblmarca';
    protected $primaryKey = 'codmarca';
    protected $fillable = [
        'codimagem',
        'marca',
        'site',
        'descricaosite',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
        'inativo'
    ];

    public function validate() {
        
        $this->_regrasValidacao = [
            'marca' => 'required|min:1', 
        ];
    
        $this->_mensagensErro = [
            'marca.required' => 'O campo Marca não pode ser vazio',
            'marca.min' => 'O campo Marca deve ter mais de 1 caracteres',
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
    public function ProdutoBarraS()
    {
        return $this->hasMany(ProdutoBarra::class, 'codmarca', 'codmarca');
    }

    public function ProdutoS()
    {
        return $this->hasMany(Produto::class, 'codmarca', 'codmarca')->orderBy('produto');
    }

    public static function search($parametros)
    {
        $query = Marca::query();
        
        if (!empty($parametros['codmarca'])) {
            $query->where('codmarca', $parametros['codmarca']);
        }

        if (!empty($parametros['marca'])) {
            $query->marca($parametros['marca']);
        }

        switch (isset($parametros['ativo']) ? $parametros['ativo']:'9')
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

    public function scopeMarca($query, $marca)
    {
        if (trim($marca) === '')
            return;
        
        $marca = explode(' ', $marca);
        foreach ($marca as $str) {
            $query->where('marca', 'ILIKE', "%$str%");
        }
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