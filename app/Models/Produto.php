<?php

namespace MGLara\Models;
use Illuminate\Support\Facades\DB;
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
 * @property  EstoqueLocalProdutoVariacao[]          $EstoqueLocalProdutoVariacaoS
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
        'observacoes',
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
        
        $unique  = "uniqueMultiple:tblproduto,codproduto,$this->codproduto,produto";
        
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
            'codcest'           => 'numeric|required_if:codtributacao,'.Tributacao::ISENTO,            
        ];
    
        $this->_mensagensErro = [
            'produto.required'              => 'O campo descrição não pode ser vazio',
            'produto.unique_multiple'       => 'Já existe um produto com essa descrição!',
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
    public function EstoqueLocalProdutoVariacaoS()
    {
        return $this->hasMany(EstoqueLocalProdutoVariacao::class, 'codproduto', 'codproduto');
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

    public static function search($parametros)
    {
        $query = Produto::query();
            
        if(!empty($parametros['codproduto'])) {
            $query->id($parametros['codproduto']);
        }

        if(!empty($parametros['barras'])) {
            
            $barras = $parametros['barras'];
            
            $query->whereIn('codproduto', function ($query) use ($barras) {
                $query->select('codproduto')
                    ->from('tblprodutobarra')
                    ->where('barras', 'ilike', "%$barras%");
            });
            
        }
            
        if(!empty($parametros['produto'])) {
            $query->produto($parametros['produto']);
        }

        if(!empty($parametros['codmarca'])) {
            $query->where('codmarca', $parametros['codmarca']);
        }

        if(!empty($parametros['codsubgrupoproduto'])) {
            
            $query->where('codsubgrupoproduto', $parametros['codsubgrupoproduto']);
            
        } elseif (!empty($parametros['codgrupoproduto'])) {
            
            $query->join('tblsubgrupoproduto', 'tblsubgrupoproduto.codsubgrupoproduto', '=', 'tblproduto.codsubgrupoproduto');
            $query->where('tblsubgrupoproduto.codgrupoproduto', $parametros['codgrupoproduto']);
            
        } elseif (!empty($parametros['codfamiliaproduto'])) {
            
            $query->join('tblsubgrupoproduto', 'tblsubgrupoproduto.codsubgrupoproduto', '=', 'tblproduto.codsubgrupoproduto');
            $query->join('tblgrupoproduto', 'tblgrupoproduto.codgrupoproduto', '=', 'tblsubgrupoproduto.codgrupoproduto');
            $query->where('tblgrupoproduto.codfamiliaproduto', $parametros['codfamiliaproduto']);
            
        } elseif (!empty($parametros['codsecaoproduto'])) {
            
            $query->join('tblsubgrupoproduto', 'tblsubgrupoproduto.codsubgrupoproduto', '=', 'tblproduto.codsubgrupoproduto');
            $query->join('tblgrupoproduto', 'tblgrupoproduto.codgrupoproduto', '=', 'tblsubgrupoproduto.codgrupoproduto');
            $query->join('tblfamiliaproduto', 'tblfamiliaproduto.codfamiliaproduto', '=', 'tblgrupoproduto.codfamiliaproduto');
            $query->where('tblfamiliaproduto.codsecaoproduto', $parametros['codsecaoproduto']);
            
        }

        if(!empty($parametros['referencia'])) {
            $query->where('referencia', $parametros['referencia']);
        }

        if(!empty($parametros['codtributacao'])) {
            $query->where('codtributacao', $parametros['codtributacao']);
        }

        if(!empty($parametros['site'])) {
            $query->where('site', $parametros['site']);
        }

        if(!empty($parametros['codncm'])) {
            $query->where('codncm', $parametros['codncm']);
        }

        if(!empty($parametros['preco_de']) && empty($parametros['preco_ate'])) {
            
            $sql = "codproduto in (
                        select pe.codproduto 
                        from tblprodutoembalagem pe 
                        inner join tblproduto p on (p.codproduto = pe.codproduto) 
                        where coalesce(pe.preco, pe.quantidade * p.preco) >= {$parametros['preco_de']}
                        or p.preco >= {$parametros['preco_de']}
                    )
                    ";
            
            $query->whereRaw($sql);
            
        }

        if(empty($parametros['preco_de']) && !empty($parametros['preco_ate'])) {
            
            $sql = "codproduto in (
                        select pe.codproduto 
                        from tblprodutoembalagem pe 
                        inner join tblproduto p on (p.codproduto = pe.codproduto) 
                        where coalesce(pe.preco, pe.quantidade * p.preco) <= {$parametros['preco_ate']}
                        or p.preco <= {$parametros['preco_ate']}
                    )
                    ";
            
            $query->whereRaw($sql);
            
        }
        
        if(!empty($parametros['preco_de']) && !empty($parametros['preco_ate'])) {
            
            $sql = "codproduto in (
                        select pe.codproduto 
                        from tblprodutoembalagem pe 
                        inner join tblproduto p on (p.codproduto = pe.codproduto) 
                        where coalesce(pe.preco, pe.quantidade * p.preco) between {$parametros['preco_de']} and {$parametros['preco_ate']}
                        or p.preco between {$parametros['preco_de']} and {$parametros['preco_ate']}
                            )
                    ";
            
            $query->whereRaw($sql);
            
        }
        
        if(!empty($parametros['criacao_de'])) {
            $query->where('criacao', '>=', $parametros['criacao_de']);
        }

        if(!empty($parametros['criacao_ate'])) {
            $query->where('criacao', '<=', $parametros['criacao_ate']);
        }

        if(!empty($parametros['alteracao_de'])) {
            $query->where('alteracao', '>=', $parametros['alteracao_de']);
        }

        if(!empty($parametros['alteracao_ate'])) {
            $query->where('alteracao', '<=', $parametros['alteracao_ate']);
        }

        switch (isset($parametros['ativo'])?$parametros['ativo']:'9') {
            case 1: //Ativos
                $query->ativo();
                break;
            case 2: //Inativos
                $query->inativo();
                break;
            case 9; //Todos
            default:
        }
        
        return $query;
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
    
    public function getArraySaldoEstoque()
    {

        // Array de Retorno
        $arrRet = [
            'local' => [],
            //'total' => [],
        ];
        
        // Array com Totais
        $arrTotal = [
            'estoqueminimo' => null,
            'estoquemaximo' => null,
            'fisico' => [
                'saldoquantidade' => null,
                'saldovalor' => null,
                'customedio' => null,
                'ultimaconferencia' => null,
            ],
            'fiscal' => [
                'saldoquantidade' => null,
                'saldovalor' => null,
                'customedio' => null,
                'ultimaconferencia' => null,
            ],
            'variacao' => [],
        ];
        
        // Array com Totais Por Variacao
        $pvs = $this->ProdutoVariacaoS()->orderBy(DB::raw("coalesce(variacao, '')"), 'ASC')->get();
        foreach ($pvs as $pv) {
            $arrTotalVar[$pv->codprodutovariacao] = [
                'codprodutovariacao' => $pv->codprodutovariacao,
                'variacao' => $pv->variacao,
                'estoqueminimo' => null,
                'estoquemaximo' => null,
                'corredor' => null,
                'prateleira' => null,
                'coluna' => null,
                'bloco' => null,
                'fisico' => [
                    'saldoquantidade' => null,
                    'saldovalor' => null,
                    'customedio' => null,
                    'ultimaconferencia' => null,
                ],
                'fiscal' => [
                    'saldoquantidade' => null,
                    'saldovalor' => null,
                    'customedio' => null,
                    'ultimaconferencia' => null,
                ],
            ];
        }
        
        // Percorrre todos os Locais
        foreach (EstoqueLocal::ativo()->orderBy('codestoquelocal', 'asc')->get() as $el) {
            
            // Array com Totais por Local
            $arrLocal = [
                'codestoquelocal' => $el->codestoquelocal,
                'estoquelocal' => $el->estoquelocal,
                'estoqueminimo' => null,
                'estoquemaximo' => null,
                'fisico' => [
                    'saldoquantidade' => null,
                    'saldovalor' => null,
                    'customedio' => null,
                    'ultimaconferencia' => null,
                ],
                'fiscal' => [
                    'saldoquantidade' => null,
                    'saldovalor' => null,
                    'customedio' => null,
                    'ultimaconferencia' => null,
                ],
                'variacao' => [],
            ];
            
            
            foreach ($pvs as $pv) {
                
                // Array com Saldo de Cada EstoqueLocalProdutoVariacao
                $arrVar = [
                    'codprodutovariacao' => $pv->codprodutovariacao,
                    'variacao' => $pv->variacao,
                    'codestoquelocalprodutovariacao' => null,
                    'estoqueminimo' => null,
                    'estoquemaximo' => null,
                    'corredor' => null,
                    'prateleira' => null,
                    'coluna' => null,
                    'bloco' => null,
                    'fisico' => [
                        'codestoquesaldo' => null,
                        'saldoquantidade' => null,
                        'saldovalor' => null,
                        'customedio' => null,
                        'ultimaconferencia' => null,
                    ],
                    'fiscal' => [
                        'codestoquesaldo' => null,
                        'saldoquantidade' => null,
                        'saldovalor' => null,
                        'customedio' => null,
                        'ultimaconferencia' => null,
                    ],
                ];
                
                //Se já existe a combinação de Variacao para o Local
                if ($elpv = $pv->EstoqueLocalProdutoVariacaoS()->where('codestoquelocal', $el->codestoquelocal)->first()) {
                    
                    $arrVar['codestoquelocalprodutovariacao'] = $elpv->codestoquelocalprodutovariacao;

                    //Acumula Estoque Mínimo
                    $arrVar['estoqueminimo'] = $elpv->estoqueminimo;
                    if (!empty($elpv->estoqueminimo)) {
                        $arrLocal['estoqueminimo'] += $elpv->estoqueminimo;
                        $arrTotal['estoqueminimo'] += $elpv->estoqueminimo;
                        $arrTotalVar[$pv->codprodutovariacao]['estoqueminimo'] += $elpv->estoqueminimo;
                    }
                    
                    //Acumula Estoque Máximo
                    $arrVar['estoquemaximo'] = $elpv->estoquemaximo;
                    if (!empty($elpv->estoquemaximo)) {
                        $arrLocal['estoquemaximo'] += $elpv->estoquemaximo;
                        $arrTotal['estoquemaximo'] += $elpv->estoquemaximo;
                        $arrTotalVar[$pv->codprodutovariacao]['estoquemaximo'] += $elpv->estoquemaximo;
                    }

                    $arrVar['corredor'] = $elpv->corredor;
                    if (!empty($elpv->corredor)) {
                        $arrLocal['corredor'] = $elpv->corredor;
                    }

                    $arrVar['prateleira'] = $elpv->prateleira;
                    if (!empty($elpv->prateleira)) {
                        $arrLocal['prateleira'] = $elpv->prateleira;
                    }

                    $arrVar['coluna'] = $elpv->coluna;
                    if (!empty($elpv->coluna)) {
                        $arrLocal['coluna'] = $elpv->coluna;
                    }

                    $arrVar['bloco'] = $elpv->bloco;
                    if (!empty($elpv->bloco)) {
                        $arrLocal['bloco'] = $elpv->bloco;
                    }
                    
                    //Percorre os Saldos Físico e Fiscal
                    foreach($elpv->EstoqueSaldoS as $es) {
                        
                        $tipo = ($es->fiscal == true)?'fiscal':'fisico';
                        
                        $arrVar[$tipo]["codestoquesaldo"] = $es->codestoquesaldo;

                        //Acumula as quantidades de Saldo
                        $arrVar[$tipo]["saldoquantidade"] = $es->saldoquantidade;
                        $arrLocal[$tipo]["saldoquantidade"] += $es->saldoquantidade;
                        $arrTotal[$tipo]["saldoquantidade"] += $es->saldoquantidade;
                        $arrTotalVar[$pv->codprodutovariacao][$tipo]["saldoquantidade"] += $es->saldoquantidade;
                        
                        //Acumula os valores de Saldo
                        $arrVar[$tipo]["saldovalor"] = $es->saldovalor;
                        $arrLocal[$tipo]["saldovalor"] += $es->saldovalor;
                        $arrTotal[$tipo]["saldovalor"] += $es->saldovalor;
                        $arrTotalVar[$pv->codprodutovariacao][$tipo]["saldovalor"] += $es->saldovalor;
                        
                        $arrVar[$tipo]["customedio"] = $es->customedio;
                        
                        $arrVar[$tipo]["ultimaconferencia"] = $es->ultimaconferencia;
                        
                        //Pega a data de conferência mais antiga para o total do Local
                        if (empty($arrLocal[$tipo]["ultimaconferencia"])) {
                            $arrLocal[$tipo]["ultimaconferencia"] = $es->ultimaconferencia;
                        } elseif (!empty($es->ultimaconferencia) && $es->ultimaconferencia < $arrLocal[$tipo]["ultimaconferencia"]) {
                            $arrLocal[$tipo]["ultimaconferencia"] = $es->ultimaconferencia;
                        }
                        
                        //Pega a data de conferência mais antiga para o total da variacao
                        if (empty($arrTotalVar[$pv->codprodutovariacao][$tipo]["ultimaconferencia"])) {
                            $arrTotalVar[$pv->codprodutovariacao][$tipo]["ultimaconferencia"] = $es->ultimaconferencia;
                        } elseif (!empty($es->ultimaconferencia) && $es->ultimaconferencia < $arrTotalVar[$pv->codprodutovariacao][$tipo]["ultimaconferencia"]) {
                            $arrTotalVar[$pv->codprodutovariacao][$tipo]["ultimaconferencia"] = $es->ultimaconferencia;                            
                        }
                        
                        //Pega a data de conferência mais antiga para o total geral
                        if (empty($arrTotal[$tipo]["ultimaconferencia"])) {
                            $arrTotal[$tipo]["ultimaconferencia"] = $es->ultimaconferencia;
                        } elseif (!empty($es->ultimaconferencia) && $es->ultimaconferencia < $arrTotal[$tipo]["ultimaconferencia"]) {
                            $arrTotal[$tipo]["ultimaconferencia"] = $es->ultimaconferencia;                            
                        }
                        
                    }

                }
                
                // Adiciona variacao ao array de locais
                $arrLocal['variacao'][$pv->codprodutovariacao] = $arrVar;
                
            }
            
            // Calcula o custo médio do Local
            if ($arrLocal['fisico']['saldoquantidade'] > 0)
                $arrLocal['fisico']['customedio'] = $arrLocal['fisico']['saldovalor'] / $arrLocal['fisico']['saldoquantidade'];
            if ($arrLocal['fiscal']['saldoquantidade'] > 0)
                $arrLocal['fiscal']['customedio'] = $arrLocal['fiscal']['saldovalor'] / $arrLocal['fiscal']['saldoquantidade'];
            
            // Adiciona local no array de retorno
            $arrRet['local'][$el->codestoquelocal] = $arrLocal;
            
        }
        
        // Calcula o custo médio dos totais de cada variacao
        foreach($arrTotalVar as $codvariacao => $arr) {
            if ($arrTotalVar[$codvariacao]['fisico']['saldoquantidade'] > 0)
                $arrTotalVar[$codvariacao]['fisico']['customedio'] = $arrTotalVar[$codvariacao]['fisico']['saldovalor'] / $arrTotalVar[$codvariacao]['fisico']['saldoquantidade'];
            if ($arrTotalVar[$codvariacao]['fiscal']['saldoquantidade'] > 0)
                $arrTotalVar[$codvariacao]['fiscal']['customedio'] = $arrTotalVar[$codvariacao]['fiscal']['saldovalor'] / $arrTotalVar[$codvariacao]['fiscal']['saldoquantidade'];
        }
        
        // Adiciona totais das variações ao array de totais
        $arrTotal['variacao'] = $arrTotalVar;

        // calcula o custo médio do total
        if ($arrTotal['fisico']['saldoquantidade'] > 0)
            $arrTotal['fisico']['customedio'] = $arrTotal['fisico']['saldovalor'] / $arrTotal['fisico']['saldoquantidade'];
        if ($arrTotal['fiscal']['saldoquantidade'] > 0)
            $arrTotal['fiscal']['customedio'] = $arrTotal['fiscal']['saldovalor'] / $arrTotal['fiscal']['saldoquantidade'];
        
        // Adiciona totais no array de retorno
        $arrRet['local']['total'] = $arrTotal;
        //$arrRet['total'] = $arrTotal;

        /*
        echo json_encode($arrRet);
        die();
        */
        
        //retorna
        return $arrRet;

    }
}
