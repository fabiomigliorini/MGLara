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
            'marca.required' => 'O campo Marca não pode ser vazio',
            'marca.min' => 'O campo Marca deve ter mais de 9 caracteres',
            'descricaosite.required' => 'O campo Descrição não pode ser vazio',
            'descricaosite.min' => 'O campo Descrição deve ter mais de 39 caracteres',
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
    public static function filterAndPaginate($codmarca, $marca)
    {
        return Marca::codmarca(numeroLimpo($codmarca))
            ->marca($marca)
            ->orderBy('marca', 'ASC')
            ->paginate(20);
    }
    
    public function scopeCodmarca($query, $codmarca)
    {
        if ($codmarca)
        {
            $query->where('codmarca', "$codmarca");
        }
    }
    
    public function scopeMarca($query, $marca)
    {
        if (trim($marca) != "")
        {
            $query->where('marca', "ILIKE", "%$marca%");
        }
    }
}