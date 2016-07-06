<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codestoquelocal                    NOT NULL DEFAULT nextval('tblestoquelocal_codestoquelocal_seq'::regclass)
 * @property  varchar(50)                    $estoquelocal                       NOT NULL
 * @property  bigint                         $codfilial                          NOT NULL
 * @property  timestamp                      $inativo                            
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 *
 * Chaves Estrangeiras
 * @property  Filial                         $Filial                        
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 * @property  EstoqueLocalProdutoVariacao[]          $EstoqueLocalProdutoVariacaoS
 * @property  Negocio[]                      $NegocioS
 * @property  NotaFiscal[]                   $NotaFiscalS
 */

class EstoqueLocal extends MGModel
{
    protected $table = 'tblestoquelocal';
    protected $primaryKey = 'codestoquelocal';
    protected $fillable = [
        'estoquelocal',
        'codfilial',
        'inativo',
    ];
    protected $dates = [
        'inativo',
        'alteracao',
        'criacao',
    ];

    public function validate() {
        
        $this->_regrasValidacao = [
            //'field' => 'required|min:2', 
        ];
    
        $this->_mensagensErro = [
            //'field.required' => 'Preencha o campo',
        ];
        
        return parent::validate();
    } 
    
    // Chaves Estrangeiras
    public function Filial()
    {
        return $this->belongsTo(Filial::class, 'codfilial', 'codfilial');
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
    public function EstoqueLocalProdutoVariacaoS()
    {
        return $this->hasMany(EstoqueLocalProdutoVariacao::class, 'codestoquelocal', 'codestoquelocal');
    }

    public function NegocioS()
    {
        return $this->hasMany(Negocio::class, 'codestoquelocal', 'codestoquelocal');
    }

    public function NotafiscalS()
    {
        return $this->hasMany(NotaFiscal::class, 'codestoquelocal', 'codestoquelocal');
    }

    // Buscas
    public static function filterAndPaginate($codestoquelocal)
    {
        return EstoqueMes::codestoquelocal($codestoquelocal)
            ->orderBy('estoquelocal', 'ASC')
            ->paginate(20);
    }
    
    public function scopeCodestoquelocal($query, $codestoquelocal)
    {
        if ($codestoquelocal)
        {
            $query->where('codestoquelocal', "$codestoquelocal");
        }
    }     

}