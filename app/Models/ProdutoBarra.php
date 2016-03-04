<?php

namespace MGLara\Models;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProdutoBarra extends MGModel
{
    protected $table = 'tblprodutobarra';
    protected $primaryKey = 'codprodutobarra';
    protected $fillable = [
        'codproduto',
        'codmarca',
        'codprodutoembalagem',
        'variacao',
        'barras',
        'referencia',
    ];
    
    public function Produto()
    {
        return $this->belongsTo(Produto::class, 'codproduto', 'codproduto');
    }
    
    public function ProdutoEmbalagem()
    {
        return $this->belongsTo(ProdutoEmbalagem::class, 'codprodutoembalagem', 'codprodutoembalagem');
    }
    
    public function NegocioProdutoBarraS()
    {
        return $this->hasMany(NegocioProdutoBarra::class, 'codprodutobarra', 'codprodutobarra');
    }
    
    public function NotaFiscalProdutoBarraS()
    {
        return $this->hasMany(NotaFiscalProdutoBarra::class, 'codprodutobarra', 'codprodutobarra');
    }

    public function recalculaEstoque()
    {
        $resultado = true;
        $mensagem = '';
        
        set_time_limit(200);
        
        $sql = 
            "select nfpb.codnotafiscalprodutobarra 
            from tblnotafiscalprodutobarra nfpb
            inner join tblnotafiscal nf on (nf.codnotafiscal = nfpb.codnotafiscal)
            where nfpb.codprodutobarra = {$this->codprodutobarra}
            and nf.saida between '2015-01-01 00:00:00.0' and '2015-12-31 23:59:59.9'
            ";
        
        $nfs = DB::select($sql);
        
        //foreach ($this->NotaFiscalProdutoBarraS()->with('NotaFiscal')->where('saida', '>=', '2017-01-01 00:00:0.0')->get() as $nfpb)
        //foreach ($this->NotaFiscalProdutoBarraS as $nfpb)
        foreach ($nfs as $nf)
        {

            $nfpb = NotaFiscalProdutoBarra::find($nf->codnotafiscalprodutobarra);
                
            $ret['codnotafiscalprodutobarra'][$nfpb->codnotafiscalprodutobarra] = $nfpb->recalculaEstoque();
            
            if ($ret['codnotafiscalprodutobarra'][$nfpb->codnotafiscalprodutobarra] !== true)
            {
                $resultado = false;
                $mensagem = erro;
            }
        }
        $ret["resultado"] = $resultado;
        $ret["mensagem"] = $mensagem;
        return $ret;
    }
    
    public function converteQuantidade($quantidade)
    {
        if (empty($this->codprodutoembalagem))
            return $quantidade;
        
        return $quantidade * $this->ProdutoEmbalagem->quantidade;
    }
    
    //TODO: Criar relacionamentos
    /**
        'codmarca',
     * 
     */

}
