<?php

namespace MGLara\Models;
use DB;
/**
 * Campos
 * @property  bigint                         $codmeta                            NOT NULL DEFAULT nextval('tblmeta_codmeta_seq'::regclass)
 * @property  timestamp                      $periodoinicial                     NOT NULL
 * @property  timestamp                      $periodofinal                       NOT NULL
 * @property  numeric(14,2)                  $premioprimeirovendedorfilial       NOT NULL
 * @property  numeric(4,2)                   $percentualcomissaovendedor         NOT NULL
 * @property  numeric(4,2)                   $percentualcomissaovendedormeta     NOT NULL
 * @property  numeric(4,2)                   $percentualcomissaosubgerentemeta   NOT NULL
 * @property  text                           $observacoes                        
 * @property  timestamp                      $criacao                            
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  bigint                         $codusuariocriacao                  
 *
 * Chaves Estrangeiras
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 * @property  MetaFilial[]                   $MetaFilialS
 */

class Meta extends MGModel
{
    protected $table = 'tblmeta';
    protected $primaryKey = 'codmeta';
    protected $fillable = [
        'periodoinicial',
        'periodofinal',
        'premioprimeirovendedorfilial',
        'percentualcomissaovendedor',
        'percentualcomissaovendedormeta',
        'percentualcomissaosubgerentemeta',
        'percentualcomissaoxerox',
        'observacoes',
    ];
    protected $dates = [
        'periodoinicial',
        'periodofinal',
        'criacao',
        'alteracao',
    ];

    public function validate() {
        
        $this->_regrasValidacao = [
            'premioprimeirovendedorfilial' => 'required', 
        ];
    
        $this->_mensagensErro = [
            'premioprimeirovendedorfilial.required' => 'O campo Prêmio Melhor Vendedor não pode ser vazio',
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
    public function MetaFilialS()
    {
        return $this->hasMany(MetaFilial::class, 'codmeta', 'codmeta');
    }

    public function totalVendas($parametros = null)
    {
        $sql_filiais = "
            select
                  f.codfilial
                , f.filial
                , mf.valormetafilial
                , mf.valormetavendedor
                , (
                    select 
                            sum((case when n.codoperacao = 1 then -1 else 1 end) * coalesce(n.valortotal, 0)) as valorvendas
                    from tblnegocio n
                    where n.codnegociostatus = 2 -- fechado
                    and n.codpessoa not in (select distinct f2.codpessoa from tblfilial f2)
                    and n.codnaturezaoperacao in (1, 2) -- Venda / Devolucao de Vendas -- TODO: Fazer modelagem para tirar o codigo fixo
                    and n.lancamento between m.periodoinicial and m.periodofinal
                    and n.codfilial = mf.codfilial
                ) as valorvendas
                , (
                    select
                        sum((case when n.codoperacao = 1 then -1 else 1 end) * coalesce(n.valortotal, 0)) as valorvendas
                    from tblnegocio n
                    inner join tblnegocioprodutobarra npb on (npb.codnegocio = n.codnegocio)
                    inner join tblprodutobarra pb on (pb.codprodutobarra = npb.codprodutobarra)
                    inner join tblproduto p on (p.codproduto = pb.codproduto)
                    where n.codnegociostatus = 2 -- fechado
                    and n.codpessoa not in (select distinct f2.codpessoa from tblfilial f2)
                    and n.codnaturezaoperacao in (1, 2) -- Venda / Devolucao de Vendas -- TODO: Fazer modelagem para tirar o codigo fixo
                    and n.lancamento between m.periodoinicial and m.periodofinal
                    and n.codfilial = mf.codfilial
                    and p.codsubgrupoproduto = 2951 -- Somente Xerox
                ) as valorvendasxerox                
                , mfp.codpessoa
                , p.pessoa
            from tblmeta m
            inner join tblmetafilial mf on (mf.codmeta = m.codmeta)
            inner join tblfilial f on (f.codfilial = mf.codfilial)
            left join tblmetafilialpessoa mfp on (mfp.codmetafilial = mf.codmetafilial and mfp.codcargo = 2) -- Subgerente -- TODO: Fazer modelagem
            left join tblpessoa p on (p.codpessoa = mfp.codpessoa)
            where m.codmeta = {$this->codmeta}
        ";
        
        $sql_vendedores = "
            select 
              mf.codfilial
            , f.filial
            , mf.valormetavendedor
            , mfp.codpessoa
            , p.fantasia
            , (
                select 
                        sum(coalesce(npb.valortotal, 0) * (case when n.codoperacao = 1 then -1 else 1 end) * (coalesce(n.valortotal, 0) / coalesce(n.valorprodutos, 0))) as valorvendas
                from tblnegocio n
                inner join tblnegocioprodutobarra npb on (npb.codnegocio = n.codnegocio)
                inner join tblprodutobarra pb on (pb.codprodutobarra = npb.codprodutobarra)
                inner join tblproduto p on (p.codproduto = pb.codproduto)
                where n.codnegociostatus = 2 -- fechado
                and n.codpessoa not in (select distinct f2.codpessoa from tblfilial f2)
                and n.codnaturezaoperacao in (1, 2) -- Venda / Devolucao de Vendas -- TODO: Fazer modelagem para tirar o codigo fixo
                and p.codsubgrupoproduto != 2951 -- Xerox -- TODO: Fazer modelagem para tirar o codigo fixo
                and n.lancamento between m.periodoinicial and m.periodofinal
                and n.codpessoavendedor = mfp.codpessoa
            ) as valorvendas
            , m.percentualcomissaovendedor
        from tblmeta m
        inner join tblmetafilial mf on (mf.codmeta = m.codmeta)
        inner join tblfilial f on (mf.codfilial = f.codfilial)
        inner join tblmetafilialpessoa mfp on (mfp.codmetafilial = mf.codmetafilial and mfp.codcargo = 1) -- Vendedor -- TODO: Fazer modelagem
        inner join tblpessoa p on (p.codpessoa = mfp.codpessoa)
        where m.codmeta = {$this->codmeta}
        order by valorvendas desc
        ";        
        
        $filiais = DB::select($sql_filiais);
        $vendedores = DB::select($sql_vendedores);
        
        $array_melhoresvendedores = [];
        foreach ($filiais as $filial){
            $array_melhoresvendedores[$filial->codfilial]=[];
            foreach ($vendedores as $vendedor){
                if($vendedor->codfilial == $filial->codfilial)
                {
                    array_push($array_melhoresvendedores[$filial->codfilial], $vendedor->valorvendas);
                }
            }
        }

        $retorno_vendedores = [];
        foreach ($vendedores as $vendedor){
            
            $valorcomissaovendedor = ($vendedor->percentualcomissaovendedor / 100 ) * $vendedor->valorvendas;
            $valorcomissaometavendedor = ($vendedor->valorvendas >= $vendedor->valormetavendedor ? ($this->percentualcomissaovendedormeta / 100 ) * $vendedor->valorvendas : null);
            $falta = ($vendedor->valorvendas < $vendedor->valormetavendedor ? $vendedor->valormetavendedor - $vendedor->valorvendas : null);
            $melhorvendedor = null;
            if($vendedor->valorvendas == max($array_melhoresvendedores[$vendedor->codfilial]) && $vendedor->valorvendas >= $vendedor->valormetavendedor){
                $melhorvendedor = 200;
            }
            
            $retorno_vendedores[] = [
                'codfilial'                 => $vendedor->codfilial,
                'filial'                    => $vendedor->filial,
                'valormetavendedor'         => $vendedor->valormetavendedor,
                'codpessoa'                 => $vendedor->codpessoa,
                'pessoa'                    => $vendedor->fantasia, 
                'valorvendas'               => $vendedor->valorvendas,
                'percentualcomissaovendedor' =>  $vendedor->percentualcomissaovendedor,
                'valorcomissaovendedor'     => $valorcomissaovendedor,
                'valorcomissaometavendedor' => $valorcomissaometavendedor,
                'valortotalcomissao'        => $valorcomissaovendedor + $valorcomissaometavendedor + $melhorvendedor,
                'metaatingida'              => ($vendedor->valorvendas >= $vendedor->valormetavendedor) ? true : false,
                'primeirovendedor'          => $melhorvendedor,
                'falta'                     => $falta,
            ];            
        }

        $retorno_filiais = [];
        foreach ($filiais as $filial){
            $retorno_filiais[] = [
                "codfilial"             => $filial->codfilial,
                "filial"                => $filial->filial,
                "valormetafilial"       => $filial->valormetafilial,
                "valormetavendedor"     => $filial->valormetavendedor,
                "valorvendas"           => $filial->valorvendas,
                "valorvendasxerox"      => $filial->valorvendasxerox,
                "valorcomissaovendasxerox"=> ($filial->valorvendasxerox / 100) * $this->percentualcomissaoxerox,
                "codpessoa"             => $filial->codpessoa,
                "pessoa"                => $filial->pessoa
            ];
        }        
        
        $retorno = [
            'filiais' => $retorno_filiais,
            //'filiais' => $filiais,
            'vendedores' => $retorno_vendedores
        ];
        
        return $retorno;
    }

    public function buscaProximos($qtd = 7)
    {
        $metas = self::where('periodoinicial', '>', $this->periodofinal)
               ->orderBy('periodoinicial', 'asc')
               ->take($qtd)
               ->get();
        return $metas;
    }
    
    public function buscaAnteriores($qtd = 7)
    {
        $metas = self::where('periodofinal', '<', $this->periodoinicial)
               ->orderBy('periodoinicial', 'desc')
               ->take($qtd)
               ->get();
        return $metas->reverse();
    }

}