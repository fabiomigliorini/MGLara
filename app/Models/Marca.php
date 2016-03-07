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
        'marca',
        'site',
        'descricaosite',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
    ];

    public function validate() {
        
        $this->_regrasValidacao = [
            'marca' => 'required|min:10', 
            'descricaosite' => 'required|min:50', 
        ];
    
        $this->_mensagensErro = [
            'marca.required' => 'Marca nao pode ser vazio bla bla bla bla bla!',
        ];
        
        return parent::validate();
        
    }
    
    // Chaves Estrangeiras
    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuario', 'codusuarioalteracao');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuario', 'codusuariocriacao');
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

    // Buscas 
    public static function filterAndPaginate($marca)
    {
        return Marca::marca($marca)
            ->orderBy('marca', 'ASC')
            ->paginate(20);
    }
    
    public function scopeMarca($query, $marca)
    {
        if (trim($marca) != "")
        {
            $query->where('marca', "ILIKE", "%$marca%");
        }
    }
}