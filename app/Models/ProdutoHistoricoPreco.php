<?php

namespace MGLara\Models;
use Carbon\Carbon;

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
        return $this->belongsTo(Produtoembalagem::class, 'codprodutoembalagem', 'codprodutoembalagem');
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
    
    // Buscas 
    public static function filterAndPaginate($codprodutohistoricopreco, $produto, $referencia, $de, $ate, $codmarca, $codusuario)
    {
        return ProdutoHistoricoPreco::codprodutohistoricopreco(numeroLimpo($codprodutohistoricopreco))
            ->produto($produto)
            ->referencia($referencia)
            ->alteracao($de, $ate)                
            ->marca($codmarca)
            ->usuario($codusuario)
            ->orderBy('alteracao', 'DESC')
            ->paginate(20);
    }
    
    public function scopeCodprodutohistoricopreco($query, $codprodutohistoricopreco)
    {
        if (trim($codprodutohistoricopreco) === '')
            return;
        
        $query->where('codprodutohistoricopreco', $codprodutohistoricopreco);
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
    
    public function scopeReferencia($query, $referencia) 
    {
        if (trim($referencia) === '')
            return;

        return $query->whereHas('Produto', function($q) use ($referencia) {
            $q->where('referencia', 'ILIKE', "%$referencia%");
       });
    }    
    
    public function scopeAlteracao($query, $de, $ate)
    {
        if ( (trim($de) === '') && (trim($ate) === '') )
            return;
        
        if(!empty($de))
            $de = Carbon::createFromFormat('d/m/y', $de)->toDateTimeString();
        
        if(!empty($ate))
            $ate = Carbon::createFromFormat('d/m/y', $ate)->toDateTimeString();
        
        if( (!empty($de)) && (empty($ate)) )
            $ate = Carbon::now();
        
        if( (empty($de)) && (!empty($ate)) )
            $de = '1900-01-01 00:00:00.0';        

        $query->whereBetween('alteracao', [$de, $ate]);    
    }    
        
    public function scopeMarca($query, $codmarca)
    {
        if (trim($codmarca) === '')
            return;
        
        return $query->whereHas('Produto', function($q) use ($codmarca) {
            $q->where('codmarca', $codmarca);
       });        
        
    }
        
    public function scopeUsuario($query, $codusuario)
    {
        if (trim($codusuario) === '')
            return;
        
        $query->where('codusuariocriacao', $codusuario);
    }
}
