<?php

namespace MGLara\Models;
use Illuminate\Support\Facades\DB;
use MGLara\Models\EstoqueSaldo;
use MGLara\Models\ProdutoBarra;
use Carbon\Carbon;

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
 * @property  EstoqueLocalProduto[]          $EstoqueLocalProdutoS
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
    
    public function getPrecocAttribute()
    {
        return $this->preco;
    }
    
    public function validate() {
        if ($this->codproduto) {
            $unique = 'unique:tblproduto,produto,'.$this->codproduto.',codproduto';
        } else {
            $unique = 'unique:tblproduto,produto';
        }    
        
        $this->_regrasValidacao = [            
            'produto'           => "min:10|max:100|validaMarca:$this->codmarca,$this->inativo|$unique",
            'referencia'        => 'max:50',
            'codunidademedida'  => 'required|numeric',
            'codsubgrupoproduto' => 'required|numeric',
            'codmarca'          => 'required|numeric',
            'preco'             => 'required|numeric|min:0.01',
            'codtributacao'     => "required|numeric|validaTributacao:$this->codncm|validaTributacaoSubstituicao:$this->codncm",
            'codtipoproduto'    => 'required|numeric',
            'codncm'            => 'required|numeric|validaNcm',
            'codcest'           => 'numeric',            
        ];
    
        $this->_mensagensErro = [
            'produto.required'              => 'O campo descrição não pode ser vazio',
            'produto.unique'                => 'Já existe um produto com essa descrição',
            'produto.min'                   => 'A descrição do produto não pode ter menos de 10 caracteres',
            'produto.valida_marca'          => 'Preencha o nome da marca na descrição do produto',
            'codunidademedida.required'     => 'O campo Unidade de medida não pode ser vazio',
            'codsubgrupoproduto.required'   => 'O campo Grupo do produto não pode ser vazio',
            'codmarca.required'             => 'O campo Marca não pode ser vazio',
            'preco.required'                => 'O campo Preço não pode ser vazio',
            'codtributacao.required'        => 'O campo Tributação não pode ser vazio',
            'codtributacao.valida_tributacao' => 'Existe Regulamento de ICMS ST para este NCM!',
            'codtributacao.valida_tributacao_substituicao' => 'Não existe regulamento de ICMS ST para este NCM!',
            
            'codtipoproduto.required'       => 'O campo Tipo não pode ser vazio',
            'codncm.required'               => 'O campo NCM não pode ser vazio',
            'codncm.valida_ncm'             => 'Ncm Inválido',
        ];
        
        return parent::validate();
    }    

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
    public function EstoqueLocalProdutoS()
    {
        return $this->hasMany(EstoqueLocalProduto::class, 'codproduto', 'codproduto');
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
    
    public function notasFiscais()
    {
        $barras = [];
        foreach ($this->ProdutoBarraS as $barra)
        {
            $barras[] = $barra['codprodutobarra'];
        }
        
        $query = NotaFiscalProdutoBarra::whereIn('codprodutobarra', $barras)
                ->orderBy('codnotafiscal', 'DESC')
                ->paginate(15);
        
        return $query;        
    }
    
    public function produtoNegocios()
    {
        $negocios = [];
        foreach ($this->ProdutoBarraS as $barra)
        {
            $negocios[] = $barra['codprodutobarra'];
        }
        
        $query = NegocioProdutoBarra::whereIn('codprodutobarra', $negocios)
                //->orderBy('codnotafiscal', 'DESC')
                ->paginate(15);
        
        return $query;        
    }

    public static function search($parametros, $registros = 20)
    {
        $query = Produto::orderBy('produto', 'ASC');
            
        if(isset($parametros['codproduto']))
            $query->id($parametros['codproduto']);

        if(isset($parametros['barras']) and !empty($parametros['barras'])) {
            $barras = $parametros['barras'];
            $query->whereHas('ProdutoBarraS', function($q) use ($barras) {
                $q->where('barras', $barras);
            }); 
        }
            
        if(isset($parametros['produto']))
            $query->produto($parametros['produto']);

        if(isset($parametros['codmarca']) and !empty($parametros['codmarca']))
            $query->where('codmarca', $parametros['codmarca']);

        if(isset($parametros['codsecaoproduto']) and !empty($parametros['codsecaoproduto'])) {
            $query->leftJoin('tblsubgrupoproduto', 'tblsubgrupoproduto.codsubgrupoproduto', '=', 'tblproduto.codsubgrupoproduto')
                ->leftJoin('tblgrupoproduto', 'tblgrupoproduto.codgrupoproduto', '=', 'tblsubgrupoproduto.codgrupoproduto')
                ->leftJoin('tblfamiliaproduto', 'tblfamiliaproduto.codfamiliaproduto', '=', 'tblgrupoproduto.codfamiliaproduto')
                ->leftJoin('tblsecaoproduto', 'tblsecaoproduto.codsecaoproduto', '=', 'tblfamiliaproduto.codsecaoproduto')
                ->where('tblsecaoproduto.codsecaoproduto', $parametros['codsecaoproduto']);            
        }
        
        if(isset($parametros['codfamiliaproduto']) and !empty($parametros['codfamiliaproduto'])) {
            $query->leftJoin('tblsubgrupoproduto', 'tblsubgrupoproduto.codsubgrupoproduto', '=', 'tblproduto.codsubgrupoproduto')
                ->leftJoin('tblgrupoproduto', 'tblgrupoproduto.codgrupoproduto', '=', 'tblsubgrupoproduto.codgrupoproduto')
                ->leftJoin('tblfamiliaproduto', 'tblfamiliaproduto.codfamiliaproduto', '=', 'tblgrupoproduto.codfamiliaproduto')
                ->where('tblfamiliaproduto.codfamiliaproduto', $parametros['codfamiliaproduto']);            
        }
        
        if(isset($parametros['codgrupoproduto']) and !empty($parametros['codgrupoproduto'])) {
            $grupo = $parametros['codgrupoproduto'];
            $query->whereHas('SubGrupoProduto.GrupoProduto', function($query) use ($grupo){
                $query->where('tblgrupoproduto.codgrupoproduto', $grupo);
            });
            
            /*
            $query->whereHas('SubGrupoProduto', function($query) use ($grupo)
            {
                $query->whereHas('GrupoProduto', function($query) use ($grupo)
                {
                    $query->where('codgrupoproduto', $grupo);
                });
            });            
            */
//            
//            $query->leftJoin('tblsubgrupoproduto', 'tblsubgrupoproduto.codsubgrupoproduto', '=', 'tblproduto.codsubgrupoproduto')
//                ->leftJoin('tblgrupoproduto', 'tblgrupoproduto.codgrupoproduto', '=', 'tblsubgrupoproduto.codgrupoproduto')
//                ->where('tblgrupoproduto.codgrupoproduto', $parametros['codgrupoproduto']);            
//            
            
            
        }
            
        if(isset($parametros['codsubgrupoproduto']) and !empty($parametros['codsubgrupoproduto']))
            $query->where('codsubgrupoproduto', $parametros['codsubgrupoproduto']);
        
            
            

        if(isset($parametros['codfamiliaproduto']) and !empty($parametros['codfamiliaproduto']))
            $query->where('codfamiliaproduto', $parametros['codfamiliaproduto']);

        if(isset($parametros['codgrupoproduto']) and !empty($parametros['codgrupoproduto']))
            $query->where('codgrupoproduto', $parametros['codgrupoproduto']);

        if(isset($parametros['codsubgrupoproduto']) and !empty($parametros['codsubgrupoproduto']))
            $query->where('codsubgrupoproduto', $parametros['codsubgrupoproduto']);

        if(isset($parametros['referencia']) and !empty($parametros['referencia']))
            $query->where('referencia', $parametros['referencia']);

        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        if(isset($parametros['inativo']))
            switch ($parametros['inativo'])
            {
                case 9: // Todos
                    break;
                case 2: // Inativos
                    $query->inativo();      break;
                default:
                    $query->ativo();        break;
            }
        else
            $query->ativo();
        
        return $query->paginate($registros);
    }

    
    // Buscas 
    public static function filterAndPaginate(
            $id, 
            $codsubgrupoproduto, 
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
        return Produto::id(numeroLimpo($id))
            ->codsubgrupoproduto($codsubgrupoproduto)
            ->barras($barras)
            ->produto($produto)
            ->codmarca($codmarca)
            ->referencia($referencia)
            ->codtributacao($codtributacao)
            ->site($site)
            ->codncm($codncm)
            ->precoDe($preco_de)
            ->precoAte($preco_ate)
            ->criacao($criacao_de, $criacao_ate)
            ->alteracao($alteracao_de, $alteracao_ate)
            ->inativo($inativo)
            ->orderBy('produto', 'ASC')
            ->paginate(20);
    }
    
    public function scopeId($query, $id)
    {
        if (trim($id) === '')
            return;
        
        $query->where('codproduto', $id);
    }

    public function scopeCodsubgrupoproduto($query, $codsubgrupoproduto)
    {
        if (trim($codsubgrupoproduto) === '')
            return;
        
        $query->where('codsubgrupoproduto', $codsubgrupoproduto);
    }
       

    
    public function scopeProduto($query, $produto)
    {
        if (trim($produto) === '')
            return;
        
        $produto = explode(' ', $produto);
        foreach ($produto as $str)
            $query->where('produto', 'ILIKE', "%$str%");
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
        
        if($site == 1)
            $query->where('site', TRUE);

        if($site == 2)
            $query->where('site', FALSE);
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
        
        $query->where('preco','>=', converteParaNumerico($preco_de));
    }

    public function scopePrecoAte($query, $preco_ate)
    {
        if (trim($preco_ate) === '')
            return;
        
        $query->where('preco','<=', converteParaNumerico($preco_ate));
    }
      
    public function scopeCriacao($query, $criacao_de, $criacao_ate)
    {
        if ( (trim($criacao_de) === '') && (trim($criacao_ate) === '') )
            return;
        
        if(!empty($criacao_de))
            $criacao_de = Carbon::createFromFormat('d/m/y', $criacao_de)->format('Y-m-d').' 00:00:00.0';
        
        if(!empty($criacao_ate))
            $criacao_ate = Carbon::createFromFormat('d/m/y', $criacao_ate)->format('Y-m-d').' 23:59:59.9';
        
        if( (!empty($criacao_de)) && (empty($criacao_ate)) )
            $criacao_ate = Carbon::now()->format('Y-m-d').' 23:59:59.9';

        if( (empty($criacao_de)) && (!empty($criacao_ate)) )
            $criacao_de = '1900-01-01 00:00:00.0';

        $query->whereBetween('criacao', [$criacao_de, $criacao_ate]);
    }
       
    public function scopeAlteracao($query, $alteracao_de, $alteracao_ate)
    {
        if ( (trim($alteracao_de) === '') && (trim($alteracao_ate) === '') )
            return;
        
        if(!empty($alteracao_de))
            $alteracao_de = Carbon::createFromFormat('d/m/y', $alteracao_de)->toDateTimeString();
        
        if(!empty($alteracao_ate))
            $alteracao_ate = Carbon::createFromFormat('d/m/y', $alteracao_ate)->toDateTimeString();
        
        if( (!empty($alteracao_de)) && (empty($alteracao_ate)) )
            $alteracao_ate = Carbon::now();
        
        if( (empty($alteracao_de)) && (!empty($alteracao_ate)) )
            $alteracao_de = '1900-01-01 00:00:00.0';        

        $query->whereBetween('alteracao', [$alteracao_de, $alteracao_ate]);    
    }
    
    
    public function scopeInativo($query)
    {
        $query->whereNotNull('tblproduto.inativo');
    }

    public function scopeAtivo($query)
    {
        $query->whereNull('tblproduto.inativo');
    }
}
