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
 * @property  ProdutoVariacao                $ProdutoVariacaoS
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

    public function ProdutoVariacaoS()
    {
        return $this->hasMany(ProdutoVariacao::class, 'codproduto', 'codproduto');
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

        if(!empty($parametros['codsubgrupoproduto']))
            $query->where('codsubgrupoproduto', $parametros['codsubgrupoproduto']);
        elseif (!empty($parametros['codgrupoproduto']))
        {
            $query->whereHas('SubGrupoProduto', function ($iq) use($parametros) {
                $iq->where('codgrupoproduto', $parametros['codgrupoproduto']);
            });
        }
        elseif (!empty($parametros['codfamiliaproduto']))
        {
            $query->whereHas('SubGrupoProduto', function ($iq) use($parametros) {
                $iq->whereHas('GrupoProduto', function ($iq2) use($parametros) {
                    $iq2->where('codfamiliaproduto', $parametros['codfamiliaproduto']);
                });
            });
        }
        elseif (!empty($parametros['codsecaoproduto']))
        {
            $query->whereHas('SubGrupoProduto', function ($iq) use($parametros) {
                $iq->whereHas('GrupoProduto', function ($iq2) use($parametros) {
                    $iq2->whereHas('FamiliaProduto', function ($iq3) use($parametros) {
                        $iq3->where('codsecaoproduto', $parametros['codsecaoproduto']);
                    });
                });
            });
        }

        if(isset($parametros['referencia']) and !empty($parametros['referencia']))
            $query->where('referencia', $parametros['referencia']);

        if(isset($parametros['codtributacao']) and !empty($parametros['codtributacao']))
            $query->where('codtributacao', $parametros['codtributacao']);

        if(isset($parametros['site']) and !empty($parametros['site']))
            $query->where('site', $parametros['site']);

        if(isset($parametros['codncm']) and !empty($parametros['codncm']))
            $query->where('codncm', $parametros['codncm']);

        if(!empty($parametros['preco_de']) && empty($parametros['preco_ate']))
        {
            $preco_de = converteParaNumerico($parametros['preco_de']);
            $sql = "codproduto in (
                        select pe.codproduto 
                        from tblprodutoembalagem pe 
                        inner join tblproduto p on (p.codproduto = pe.codproduto) 
                        where coalesce(pe.preco, pe.quantidade * p.preco) >= $preco_de
                        or p.preco >= $preco_de
                    )
                    ";
            $query->whereRaw($sql);
        }

        if(empty($parametros['preco_de']) && !empty($parametros['preco_ate']))
        {
            $preco_ate = converteParaNumerico($parametros['preco_ate']);
            $sql = "codproduto in (
                        select pe.codproduto 
                        from tblprodutoembalagem pe 
                        inner join tblproduto p on (p.codproduto = pe.codproduto) 
                        where coalesce(pe.preco, pe.quantidade * p.preco) <= $preco_ate
                        or p.preco <= $preco_ate
                    )
                    ";
            $query->whereRaw($sql);
        }
        
        if(!empty($parametros['preco_de']) && !empty($parametros['preco_ate']))
        {
            $preco_de = converteParaNumerico($parametros['preco_de']);
            $preco_ate = converteParaNumerico($parametros['preco_ate']);
            $sql = "codproduto in (
                        select pe.codproduto 
                        from tblprodutoembalagem pe 
                        inner join tblproduto p on (p.codproduto = pe.codproduto) 
                        where coalesce(pe.preco, pe.quantidade * p.preco) between $preco_de and $preco_ate
                        or p.preco between $preco_de and $preco_ate
                            )
                    ";
            $query->whereRaw($sql);
        }
        
        if(isset($parametros['criacao_de']) and !empty($parametros['criacao_de']))
            $query->where('criacao', '>=', Carbon::createFromFormat('d/m/y', $parametros['criacao_de'])->format('Y-m-d').' 00:00:00.0');
            
        if(isset($parametros['criacao_ate']) and !empty($parametros['criacao_ate']))
            $query->where('criacao', '<=', Carbon::createFromFormat('d/m/y', $parametros['criacao_ate'])->format('Y-m-d').' 23:59:59.9');
            
        if(isset($parametros['alteracao_de']) and !empty($parametros['alteracao_de']))
            $query->where('alteracao', '>=', Carbon::createFromFormat('d/m/y', $parametros['alteracao_de'])->format('Y-m-d').' 00:00:00.0');
            
        if(isset($parametros['alteracao_ate']) and !empty($parametros['alteracao_ate']))
            $query->where('alteracao', '<=', Carbon::createFromFormat('d/m/y', $parametros['alteracao_ate'])->format('Y-m-d').' 23:59:59.9');
            
        if(isset($parametros['inativo']))
            switch ($parametros['inativo'])
            {
                case 1: // Todos
                    $query->ativo();
                    break;
                case 2: // Inativos
                    $query->inativo();
                    break;
                default:
                    //$query->ativo();
            }
        else
            $query->ativo();
        
        return $query->paginate($registros);
    }

    
    public function scopeId($query, $id)
    {
        if (trim($id) === '')
            return;
        
        $query->where('codproduto', $id);
    }
    
    public function scopeProduto($query, $produto)
    {
        if (trim($produto) === '')
            return;
        
        $produto = explode(' ', $produto);
        foreach ($produto as $str)
            $query->where('produto', 'ILIKE', "%$str%");
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
