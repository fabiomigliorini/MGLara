<?php

namespace MGLara\Models;
use Illuminate\Support\Facades\DB;
use MGLara\Models\EstoqueSaldo;

/**
 * Campos
 * @property  bigint                         $codproduto                         NOT NULL DEFAULT nextval('tblproduto_codproduto_seq'::regclass)
 * @property  varchar(100)                   $produto                            NOT NULL
 * @property  varchar(50)                    $referencia                         
 * @property  bigint                         $codunidademedida                   NOT NULL
 * @property  bigint                         $codsubgrupoproduto                 NOT NULL
 * @property  bigint                         $codmarca                           NOT NULL
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
    
    public function ImagemS()
    {
        return $this->belongsToMany(Imagem::class, 'tblprodutoimagem', 'codproduto', 'codimagem');
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
            and no.estoque = true
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

    /**
     * @var EstoqueSaldo $negativo
     * @var EstoqueSaldo[] $negativos
     */
    public function cobreEstoqueNegativo()
    {
        set_time_limit(1000);
        
        $negativos = EstoqueSaldo::
            where('codproduto', $this->codproduto)
            ->where('saldoquantidade', '<=', -1)
            ->where('fiscal', true)
            ->get();
        $saldoquantidade = [];
        $ret = [];
        foreach($negativos as $negativo)
        {
            //cai fora do xerox
            if ($negativo->Produto->codsubgrupoproduto == 17001)
                continue;
            
            $quantidade = abs(floor($negativo->saldoquantidade));
            
            $sql = "
                select * from (
                    -- MESMO NCM/SUBGRUPO/MARCA/LOCAL
                    select 11 as prioridade, es.codestoquesaldo, p.codproduto, p.produto, p.preco, es.codestoquelocal, es.saldoquantidade
                    from tblestoquesaldo es 
                    inner join tblproduto p on (p.codproduto = es.codproduto)
                    inner join 
                        (
                        select es_orig.codestoquelocal, es_orig.fiscal, es_orig.codproduto, p_orig.codncm, p_orig.preco, p_orig.codsubgrupoproduto, p_orig.codmarca
                        from tblestoquesaldo es_orig
                        inner join tblproduto p_orig on (p_orig.codproduto = es_orig.codproduto)
                        where es_orig.codestoquesaldo = {$negativo->codestoquesaldo}
                        ) orig on 
                        (
                        p.codncm = orig.codncm
                        and p.preco between (orig.preco * .80) and  (orig.preco * 1.20) 
                        AND p.codsubgrupoproduto = orig.codsubgrupoproduto
                        and p.codmarca = orig.codmarca
                        AND ES.codestoquelocal = orig.codestoquelocal 
                        )
                    where es.saldoquantidade > 0
                    union 
                    -- MESMO NCM/SUBGRUPO/MARCA NA SINOPEL
                    select 21 as prioridade, es.codestoquesaldo, p.codproduto, p.produto, p.preco, es.codestoquelocal, es.saldoquantidade
                    from tblestoquesaldo es 
                    inner join tblproduto p on (p.codproduto = es.codproduto)
                    inner join 
                        (
                        select es_orig.codestoquelocal, es_orig.fiscal, es_orig.codproduto, p_orig.codncm, p_orig.preco, p_orig.codsubgrupoproduto, p_orig.codmarca
                        from tblestoquesaldo es_orig
                        inner join tblproduto p_orig on (p_orig.codproduto = es_orig.codproduto)
                        where es_orig.codestoquesaldo = {$negativo->codestoquesaldo}
                        ) orig on 
                        (
                        p.codncm = orig.codncm
                        and p.preco between (orig.preco * .80) and  (orig.preco * 1.20) 
                        AND p.codsubgrupoproduto = orig.codsubgrupoproduto
                        and p.codmarca = orig.codmarca
                        AND ES.codestoquelocal = 301001
                        )
                    where es.saldoquantidade > 0
                    union 
                    -- MESMO NCM NA SINOPEL
                    select 22 as prioridade, es.codestoquesaldo, p.codproduto, p.produto, p.preco, es.codestoquelocal, es.saldoquantidade
                    from tblestoquesaldo es 
                    inner join tblproduto p on (p.codproduto = es.codproduto)
                    inner join 
                        (
                        select es_orig.codestoquelocal, es_orig.fiscal, es_orig.codproduto, p_orig.codncm, p_orig.preco, p_orig.codsubgrupoproduto, p_orig.codmarca
                        from tblestoquesaldo es_orig
                        inner join tblproduto p_orig on (p_orig.codproduto = es_orig.codproduto)
                        where es_orig.codestoquesaldo = {$negativo->codestoquesaldo}
                        ) orig on 
                        (
                        p.codncm = orig.codncm
                        and p.preco between (orig.preco * .80) and  (orig.preco * 1.20) 
                        --AND p.codsubgrupoproduto = orig.codsubgrupoproduto
                        --and p.codmarca = orig.codmarca
                        AND ES.codestoquelocal = 301001
                        )
                    where es.saldoquantidade > 0
                    union 
                    -- MESMO NCM/SUBGRUPO/MARCA NA FDF
                    select 31 as prioridade, es.codestoquesaldo, p.codproduto, p.produto, p.preco, es.codestoquelocal, es.saldoquantidade
                    from tblestoquesaldo es 
                    inner join tblproduto p on (p.codproduto = es.codproduto)
                    inner join 
                        (
                        select es_orig.codestoquelocal, es_orig.fiscal, es_orig.codproduto, p_orig.codncm, p_orig.preco, p_orig.codsubgrupoproduto, p_orig.codmarca
                        from tblestoquesaldo es_orig
                        inner join tblproduto p_orig on (p_orig.codproduto = es_orig.codproduto)
                        where es_orig.codestoquesaldo = {$negativo->codestoquesaldo}
                        ) orig on 
                        (
                        p.codncm = orig.codncm
                        and p.preco between (orig.preco * .80) and  (orig.preco * 1.20) 
                        AND p.codsubgrupoproduto = orig.codsubgrupoproduto
                        and p.codmarca = orig.codmarca
                        AND ES.codestoquelocal = 201001
                        )
                    where es.saldoquantidade > 0
                    union 
                    -- MESMO NCM NA FDF
                    select 32 as prioridade, es.codestoquesaldo, p.codproduto, p.produto, p.preco, es.codestoquelocal, es.saldoquantidade
                    from tblestoquesaldo es 
                    inner join tblproduto p on (p.codproduto = es.codproduto)
                    inner join 
                        (
                        select es_orig.codestoquelocal, es_orig.fiscal, es_orig.codproduto, p_orig.codncm, p_orig.preco, p_orig.codsubgrupoproduto, p_orig.codmarca
                        from tblestoquesaldo es_orig
                        inner join tblproduto p_orig on (p_orig.codproduto = es_orig.codproduto)
                        where es_orig.codestoquesaldo = {$negativo->codestoquesaldo}
                        ) orig on 
                        (
                        p.codncm = orig.codncm
                        and p.preco between (orig.preco * .80) and  (orig.preco * 1.20) 
                        --AND p.codsubgrupoproduto = orig.codsubgrupoproduto
                        --and p.codmarca = orig.codmarca
                        AND ES.codestoquelocal = 201001
                        )
                    where es.saldoquantidade > 0
                    union
                    -- MESMO NCM/SUBGRUPO/MARCA NAS MIGLIORINIS
                    select 41 as prioridade, es.codestoquesaldo, p.codproduto, p.produto, p.preco, es.codestoquelocal, es.saldoquantidade
                    from tblestoquesaldo es 
                    inner join tblproduto p on (p.codproduto = es.codproduto)
                    inner join 
                        (
                        select es_orig.codestoquelocal, es_orig.fiscal, es_orig.codproduto, p_orig.codncm, p_orig.preco, p_orig.codsubgrupoproduto, p_orig.codmarca
                        from tblestoquesaldo es_orig
                        inner join tblproduto p_orig on (p_orig.codproduto = es_orig.codproduto)
                        where es_orig.codestoquesaldo = {$negativo->codestoquesaldo}
                        ) orig on 
                        (
                        p.codncm = orig.codncm
                        and p.preco between (orig.preco * .80) and  (orig.preco * 1.20) 
                        AND p.codsubgrupoproduto = orig.codsubgrupoproduto
                        and p.codmarca = orig.codmarca
                        AND ES.codestoquelocal not in (301001, 201001)
                        )
                    where es.saldoquantidade > 0
                    union
                    -- MESMO NCM/SUBGRUPO 
                    select 51 as prioridade, es.codestoquesaldo, p.codproduto, p.produto, p.preco, es.codestoquelocal, es.saldoquantidade
                    from tblestoquesaldo es 
                    inner join tblproduto p on (p.codproduto = es.codproduto)
                    inner join 
                        (
                        select es_orig.codestoquelocal, es_orig.fiscal, es_orig.codproduto, p_orig.codncm, p_orig.preco, p_orig.codsubgrupoproduto, p_orig.codmarca
                        from tblestoquesaldo es_orig
                        inner join tblproduto p_orig on (p_orig.codproduto = es_orig.codproduto)
                        where es_orig.codestoquesaldo = {$negativo->codestoquesaldo}
                        ) orig on 
                        (
                        p.codncm = orig.codncm
                        and p.preco between (orig.preco * .80) and  (orig.preco * 1.20) 
                        AND p.codsubgrupoproduto = orig.codsubgrupoproduto
                        --and p.codmarca = orig.codmarca
                        --AND ES.codestoquelocal = 301001
                        )
                    where es.saldoquantidade > 0
                    ) iq2
                order by iq2.prioridade, iq2.codestoquelocal desc                
            ";
                        
            $alternativas = DB::select($sql);
            
            //echo $sql;
            
            /*
            $origens = EstoqueSaldo::where('codproduto', $this->codproduto)->where('fiscal', $negativo->fiscal)->where('saldoquantidade', '>', 0)->where('codestoquelocal', 301001)->get();
            $origens = $origens->merge(EstoqueSaldo::where('codproduto', $this->codproduto)->where('fiscal', $negativo->fiscal)->where('saldoquantidade', '>', 0)->where('codestoquelocal', 201001)->get());
            $origens = $origens->merge(EstoqueSaldo::where('codproduto', $this->codproduto)->where('fiscal', $negativo->fiscal)->where('saldoquantidade', '>', 0)->where('codestoquelocal', 101001)->get());
            $origens = $origens->merge(EstoqueSaldo::where('codproduto', $this->codproduto)->where('fiscal', $negativo->fiscal)->where('saldoquantidade', '>', 0)->where('codestoquelocal', 102001)->get());
            $origens = $origens->merge(EstoqueSaldo::where('codproduto', $this->codproduto)->where('fiscal', $negativo->fiscal)->where('saldoquantidade', '>', 0)->where('codestoquelocal', 103001)->get());
            $origens = $origens->merge(EstoqueSaldo::where('codproduto', $this->codproduto)->where('fiscal', $negativo->fiscal)->where('saldoquantidade', '>', 0)->where('codestoquelocal', 104001)->get());
            */
            
            $i = 1;
            foreach ($alternativas as $alternativa)
            {
                if (isset($saldoquantidade[$alternativa->codestoquesaldo]))
                    if ($saldoquantidade[$alternativa->codestoquesaldo] == 0)
                        continue;
                    
                $origem = EstoqueSaldo::find($alternativa->codestoquesaldo);
                
                if (!isset($saldoquantidade[$alternativa->codestoquesaldo]))
                    $saldoquantidade[$alternativa->codestoquesaldo] = $origem->saldoquantidade;
                
                $transferir = ($quantidade > $saldoquantidade[$origem->codestoquesaldo])?$saldoquantidade[$origem->codestoquesaldo]:$quantidade;
                
                if ($transferir == 0)
                    continue;

                $ret[] = array(
                    'origem codestoquesaldo' => $origem->codestoquesaldo,
                    'origem local' => $origem->EstoqueLocal->estoquelocal,
                    'origem produto' => $origem->Produto->produto,
                    'destino codestoquesaldo' => $negativo->codestoquesaldo,
                    'destino local' => $negativo->EstoqueLocal->estoquelocal,
                    'destino produto' => $negativo->Produto->produto,
                    'quantidade' => $transferir,
                    'resultado' => $origem->transfere($negativo, $transferir)
                );
                
                $origem->recalculaCustoMedio();
                
                $quantidade -= $transferir;
                $saldoquantidade[$origem->codestoquesaldo] -= $transferir;
                
                if ($quantidade == 0)
                    break;
                

            }
            
            $negativo->recalculaCustoMedio();
            
        }
        
        return $ret;
        
    }
    
    /*
    public function cobreEstoqueNegativo()
    {
        set_time_limit(1000);
        
        $negativos = EstoqueSaldo::where('codproduto', $this->codproduto)->where('saldoquantidade', '<', 0)->where('fiscal', true)->get();
        $saldoquantidade = [];
        $ret = [];
        foreach($negativos as $negativo)
        {
            $quantidade = abs($negativo->saldoquantidade);
            
            $origens = EstoqueSaldo::where('codproduto', $this->codproduto)->where('fiscal', $negativo->fiscal)->where('saldoquantidade', '>', 0)->where('codestoquelocal', 301001)->get();
            $origens = $origens->merge(EstoqueSaldo::where('codproduto', $this->codproduto)->where('fiscal', $negativo->fiscal)->where('saldoquantidade', '>', 0)->where('codestoquelocal', 201001)->get());
            $origens = $origens->merge(EstoqueSaldo::where('codproduto', $this->codproduto)->where('fiscal', $negativo->fiscal)->where('saldoquantidade', '>', 0)->where('codestoquelocal', 101001)->get());
            $origens = $origens->merge(EstoqueSaldo::where('codproduto', $this->codproduto)->where('fiscal', $negativo->fiscal)->where('saldoquantidade', '>', 0)->where('codestoquelocal', 102001)->get());
            $origens = $origens->merge(EstoqueSaldo::where('codproduto', $this->codproduto)->where('fiscal', $negativo->fiscal)->where('saldoquantidade', '>', 0)->where('codestoquelocal', 103001)->get());
            $origens = $origens->merge(EstoqueSaldo::where('codproduto', $this->codproduto)->where('fiscal', $negativo->fiscal)->where('saldoquantidade', '>', 0)->where('codestoquelocal', 104001)->get());
            
            foreach ($origens as $origem)
            {
                if (!isset($saldoquantidade[$origem->codestoquesaldo]))
                    $saldoquantidade[$origem->codestoquesaldo] = $origem->saldoquantidade;
                
                $transferir = ($quantidade > $saldoquantidade[$origem->codestoquesaldo])?$saldoquantidade[$origem->codestoquesaldo]:$quantidade;
                
                if ($transferir == 0)
                    continue;

                $ret[] = array(
                    'origem' => $origem->EstoqueLocal->estoquelocal,
                    'destino' => $negativo->EstoqueLocal->estoquelocal,
                    'quantidade' => $transferir,
                    'resultado' => $origem->transfere($negativo, $transferir)
                );
                
                $quantidade -= $transferir;
                $saldoquantidade[$origem->codestoquesaldo] -= $transferir;
                
                if ($quantidade == 0)
                    break;

            }
            
        }
        
        $this->recalculaCustoMedio();
        
        return $ret;
        
    }
     * 
     */
    

    // Buscas 
    public static function filterAndPaginate(
            $codproduto, 
            $barras, 
            $produto, 
            $codmarca, 
            $referencia, 
            $codtributacao, 
            $site, 
            $codncm,
            $preco_de, 
            $preco_ate, 
            $criacao_de, 
            $criacao_ate, 
            $alteracao_de, 
            $alteracao_ate,  
            $inativo)
    {
        return Produto::codproduto(numeroLimpo($codproduto))
            ->barras($barras)
            ->produto($produto)
            ->codmarca($codmarca)
            ->referencia($referencia)
            ->codtributacao($codtributacao)
            ->site($site)
            ->codncm($codncm)
            ->precoDe($preco_de)
            ->precoAte($preco_ate)
            ->criacaoDe($criacao_de)
            ->criacaoAte($criacao_ate)
            ->alteracaoDe($alteracao_de)
            ->alteracaoAte($alteracao_ate)
            ->inativo($inativo)
            ->orderBy('produto', 'ASC')
            ->paginate(20);
    }
    
    public function scopeCodproduto($query, $codproduto)
    {
        if (trim($codproduto) === '')
            return;
        
        $query->where('codproduto', $codproduto);
    }
       
    public function scopeBarras($query, $barras)
    {
        if (trim($barras) === '')
            return;
        
        $query->where('barras', $barras);
    }
    
    public function scopeProduto($query, $produto)
    {
        if (trim($produto) === '')
            return;
        
        $produto = explode(' ', $produto);
        foreach ($produto as $str)
            $query->where('produto', 'ILIKE', "%$str%");
    }
        
    public function scopeCodmarca($query, $codmarca)
    {
        if (trim($codmarca) === '')
            return;
        
        $query->where('codmarca', $codmarca);
    }
    
    public function scopeReferencia($query, $referencia)
    {
        if (trim($referencia) === '')
            return;
        
        $query->where('referencia', $referencia);
    }
    
    public function scopeCodtributacao($query, $codtributacao)
    {
        if (trim($codtributacao) === '')
            return;
        
        $query->where('codtributacao', $codtributacao);
    }
    
    public function scopeSite($query, $site)
    {
        if (trim($site) === '')
            return;
        
        $query->where('site', $site);
    }
    
    public function scopeCodncm($query, $codncm)
    {
        if (trim($codncm) === '')
            return;
        
        $query->where('codncm', $codncm);
    }
    
    public function scopePrecoDe($query, $preco_de)
    {
        if (trim($preco_de) === '')
            return;
        
        $query->where('value', $var);
    }
    
    public function scopePrecoAte($query, $preco_ate)
    {
        if (trim($preco_ate) === '')
            return;
        
        $query->where('value', $var);
    }
       
    public function scopeCriacaoDe($query, $criacao_de)
    {
        if (trim($criacao_de) === '')
            return;
        
        $query->where('value', $var);
    }
    
    public function scopeCriacaoAte($query, $criacao_ate)
    {
        if (trim($criacao_ate) === '')
            return;
        
        $query->where('value', $var);
    }
       
    public function scopeAlteracaoDe($query, $alteracao_de)
    {
        if (trim($alteracao_de) === '')
            return;
        
        $query->where('value', $var);
    }
    
    public function scopeAlteracaoAte($query, $alteracao_ate)
    {
        if (trim($alteracao_ate) === '')
            return;
        
        $query->where('value', $var);
    }
    
    public function scopeInativo($query, $inativo)
    {
        if (trim($inativo) === '')
            $query->whereNull('inativo');
        
        if($inativo == 1)
            $query->whereNull('inativo');

        if($inativo == 2)
            $query->whereNotNull('inativo');
    }    
}
