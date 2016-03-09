<?php

namespace MGLara\Models;
use Illuminate\Support\Facades\DB;

/**
 * Campos
 * @property  bigint                         $codproduto                         NOT NULL DEFAULT nextval('tblproduto_codproduto_seq'::regclass)
 * @property  varchar(100)                   $produto                            NOT NULL
 * @property  varchar(50)                    $referencia                         
 * @property  bigint                         $codunidademedida                   NOT NULL
 * @property  bigint                         $codsubgrupoproduto                 
 * @property  bigint                         $codmarca                           
 * @property  numeric(14,2)                  $preco                              
 * @property  boolean                        $importado                          NOT NULL DEFAULT false
 * @property  bigint                         $codtributacao                      NOT NULL
 * @property  date                           $inativo                            
 * @property  bigint                         $codtipoproduto                     NOT NULL
 * @property  boolean                        $site                               NOT NULL DEFAULT false
 * @property  varchar(1024)                  $descricaosite                      
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 * @property  bigint                         $codncm                             
 * @property  bigint                         $codcest                            
 *
 * Chaves Estrangeiras
 * @property  Cest                           $Cest                          
 * @property  Marca                          $Marca                         
 * @property  Ncm                            $Ncm                           
 * @property  SubGrupoProduto                $SubGrupoProduto               
 * @property  TipoProduto                    $TipoProduto                   
 * @property  Tributacao                     $Tributacao                    
 * @property  UnidadeMedida                  $UnidadeMedida                 
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 * @property  EstoqueSaldo[]                 $EstoqueSaldoS
 * @property  ProdutoBarra[]                 $ProdutoBarraS
 * @property  ProdutoEmbalagem[]             $ProdutoEmbalagemS
 * @property  ProdutoHistoricoPreco[]        $ProdutoHistoricoPrecoS
 */

class Produto extends MGModel
{
    protected $table = 'tblproduto';
    protected $primaryKey = 'codproduto';
    protected $fillable = [
        'produto',
        'referencia',
        'codunidademedida',
        'codsubgrupoproduto',
        'codmarca',
        'preco',
        'importado',
        'codtributacao',
        'inativo',
        'codtipoproduto',
        'site',
        'descricaosite',
        'codncm',
        'codcest',
    ];
    protected $dates = [
        'inativo',
        'alteracao',
        'criacao',
    ];


    // Chaves Estrangeiras
    public function Cest()
    {
        return $this->belongsTo(Cest::class, 'codcest', 'codcest');
    }

    public function Marca()
    {
        return $this->belongsTo(Marca::class, 'codmarca', 'codmarca');
    }

    public function Ncm()
    {
        return $this->belongsTo(Ncm::class, 'codncm', 'codncm');
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

    public function UnidadeMedida()
    {
        return $this->belongsTo(UnidadeMedida::class, 'codunidademedida', 'codunidademedida');
    }

    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuarioalteracao', 'codusuario');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuariocriacao', 'codusuario');
    }


    // Tabelas Filhas
    public function EstoqueSaldoS()
    {
        return $this->hasMany(EstoqueSaldo::class, 'codproduto', 'codproduto')->orderBy('codestoquelocal');
    }

    public function ProdutoBarraS()
    {
        return $this->hasMany(ProdutoBarra::class, 'codproduto', 'codproduto');
    }

    public function ProdutoEmbalagemS()
    {
        return $this->hasMany(ProdutoEmbalagem::class, 'codproduto', 'codproduto');
    }

    public function ProdutoHistoricoPrecoS()
    {
        return $this->hasMany(ProdutoHistoricoPreco::class, 'codproduto', 'codproduto');
    }

    public function recalculaMovimentoEstoque()
    {
        
        $resultado = true;
        $mensagem = '';
        
        set_time_limit(1000);
        
        $sql = 
            "
            select nfpb.codnotafiscalprodutobarra
            from tblnotafiscalprodutobarra nfpb
            inner join tblprodutobarra pb on (pb.codprodutobarra = nfpb.codprodutobarra)
            inner join tblnotafiscal nf on (nf.codnotafiscal = nfpb.codnotafiscal)
            inner join tblnaturezaoperacao no on (no.codnaturezaoperacao = nf.codnaturezaoperacao)
            inner join tblestoquemovimentotipo emt on (emt.codestoquemovimentotipo = no.codestoquemovimentotipo)
            where pb.codproduto = {$this->codproduto}
            and nf.saida between '2015-01-01 00:00:00.0' and '2015-12-31 23:59:59.9'
            order by emt.preco, nfpb.criacao
            ";
        
        $nfs = DB::select($sql);
        
        foreach ($nfs as $nf)
        {
            $nfpb = NotaFiscalProdutoBarra::find($nf->codnotafiscalprodutobarra);
            
            $ret['codnotafiscalprodutobarra'][$nfpb->codnotafiscalprodutobarra] = $nfpb->recalculaMovimentoEstoque();
            
            if ($ret['codnotafiscalprodutobarra'][$nfpb->codnotafiscalprodutobarra] !== true)
            {
                $resultado = false;
                $mensagem = 'erro';
            }
        }
        $ret["resultado"] = $resultado;
        $ret["mensagem"] = $mensagem;
        
        $this->recalculaCustoMedio();
        
        return $ret;
        
    }
    
    public function recalculaCustoMedio()
    {
        foreach ($this->EstoqueSaldoS as $es)
            $ret['codestoquesaldo'][$es->codestoquesaldo] = $es->recalculaCustoMedio();
        
        $ret["resultado"] = true;
        $ret["mensagem"] = null;
        
        return $ret;
    }

    
}
