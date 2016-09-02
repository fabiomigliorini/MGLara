<?php

namespace MGLara\Models;


/**
 * Campos
 * @property  bigint                         $codprodutohistoricopreco           NOT NULL DEFAULT nextval('tblprodutohistoricopreco_codprodutohistoricopreco_seq'::regclass)
 * @property  bigint                         $codproduto                         NOT NULL
 * @property  bigint                         $codprodutoembalagem                
 * @property  numeric(14,2)                  $precoantigo                        
 * @property  numeric(14,2)                  $preconovo                          
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 *
 * Chaves Estrangeiras
 * @property  Produto                        $Produto                       
 * @property  Produtoembalagem               $Produtoembalagem              
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 */

class ProdutoHistoricoPreco extends MGModel
{
    protected $table = 'tblprodutohistoricopreco';
    protected $primaryKey = 'codprodutohistoricopreco';
    protected $fillable = [
        'codproduto',
        'codprodutoembalagem',
        'precoantigo',
        'preconovo',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
    ];


    // Chaves Estrangeiras
    public function Produto()
    {
        return $this->belongsTo(Produto::class, 'codproduto', 'codproduto');
    }

    public function ProdutoEmbalagem()
    {
        return $this->belongsTo(ProdutoEmbalagem::class, 'codprodutoembalagem', 'codprodutoembalagem');
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
        // ...


    public static function search($parametros)
    {
        $query = ProdutoHistoricoPreco::query();
            
        if(isset($parametros['id']) and !empty($parametros['id']))
            $query->whereHas('Produto', function($q) use ($parametros) {
                $q->where('codproduto',  $parametros['id']);
            });
        
        if(isset($parametros['produto']))
            $query->produto(removeAcentos ($parametros['produto']));

        if(isset($parametros['referencia']) and !empty($parametros['referencia']))
            $query->whereHas('Produto', function($q) use ($parametros) {
                $referencia = $parametros['referencia'];
                $q->where('referencia', 'ILIKE', "%$referencia%");
            });
            
        if (!empty($parametros['alteracao_de'])) {
            $query->where('criacao', '>=', $parametros['alteracao_de']);
        }

        if (!empty($parametros['alteracao_ate'])) {
            $query->where('criacao', '<=', $parametros['alteracao_ate']);
        }

        if(isset($parametros['codmarca']) and !empty($parametros['codmarca']))
            $query->whereHas('Produto', function($q) use ($parametros) {
                $codmarca = $parametros['codmarca'];
                $q->where('codmarca', $codmarca);
        });        
        
        if(isset($parametros['codusuario']) and !empty($parametros['codusuario']))
            $query->where('codusuariocriacao', $parametros['codusuario']);
        
        return $query;
    }

    public function scopeProduto($query, $produto) 
    {
        if (trim($produto) === '')
            return;

        return $query->whereHas('Produto', function($q) use ($produto) {
            $produto = explode(' ', $produto);
            foreach ($produto as $str)
                $q->where('produto', 'ILIKE', "%$str%");
       });
    }

}
