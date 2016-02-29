<?php

namespace MGLara\Models;

class Produto extends MGModel
{
    protected $table = 'tblproduto';
    protected $primaryKey = 'codproduto';
    protected $fillable = [
        'produto',
        'referencia',
        'preco',
        'importado',
        'inativo',
        'site',
        'descricaosite',
        'codncm',
        'codcest',
        'codtipoproduto',
        'codtributacao',
        'codunidademedida',
        'codsubgrupoproduto',
        'codmarca',
    ];
    
    public function EstoqueSaldoS()
    {
        return $this->hasMany(EstoqueSaldo::class, 'codproduto', 'codproduto');
    } 
    
    public function ProdutoEmbalagemS()
    {
        return $this->hasMany(ProdutoEmbalagem::class, 'codproduto', 'codproduto');
    } 

    public function ProdutoBarraS()
    {
        return $this->hasMany(ProdutoBarra::class, 'codproduto', 'codproduto');
    } 

    public function UnidadeMedida()
    {
        return $this->belongsTo(UnidadeMedida::class, 'codunidademedida', 'codunidademedida');
    } 
    
    public function Marca()
    {
        return $this->belongsTo(Marca::class, 'codmarca', 'codmarca');
    }

    public function SubGrupoProduto()
    {
        return $this->belongsTo(SubGrupoProduto::class, 'codsubgrupoproduto', 'codsubgrupoproduto');
    }   
    
    public function TipoProduto()
    {
        return $this->belongsTo(TipoProduto::class, 'codtipoproduto', 'codtipoproduto');
    }

    public function Tributacao()
    {
        return $this->belongsTo(Tributacao::class, 'codtributacao', 'codtributacao');
    }     
    
    public function Ncm()
    {
        return $this->belongsTo(Ncm::class, 'codncm', 'codncm');
    }    

    public function Cest()
    {
        return $this->belongsTo(Cest::class, 'codcest', 'codcest');
    }


    public function recalculaEstoque()
    {
        foreach ($this->ProdutoBarraS as $pb)
            $pb->recalculaEstoque();
    }
    
    // TODO: Criar Relacionamentos
    /*
     *         'codncm',
        'codcest',
        'codtipoproduto',
        'codtributacao',
        'codsubgrupoproduto',
        'codmarca',

     */
    
}
