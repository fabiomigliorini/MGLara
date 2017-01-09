<?php

namespace MGLara\Models;
use Carbon\Carbon;
use MGLara\Models\TributacaoNaturezaOperacao;
/**
 * Campos
 * @property  bigint                         $codnotafiscalprodutobarra          NOT NULL DEFAULT nextval('tblnotafiscalprodutobarra_codnotafiscalprodutobarra_seq'::regclass)
 * @property  bigint                         $codnotafiscal                      NOT NULL
 * @property  bigint                         $codprodutobarra                    NOT NULL
 * @property  bigint                         $codcfop                            NOT NULL
 * @property  varchar(100)                   $descricaoalternativa               
 * @property  numeric(14,3)                  $quantidade                         NOT NULL
 * @property  numeric(14,3)                  $valorunitario                      NOT NULL
 * @property  numeric(14,2)                  $valortotal                         NOT NULL
 * @property  numeric(14,2)                  $icmsbase                           
 * @property  numeric(14,2)                  $icmspercentual                     
 * @property  numeric(14,2)                  $icmsvalor                          
 * @property  numeric(14,2)                  $ipibase                            
 * @property  numeric(14,2)                  $ipipercentual                      
 * @property  numeric(14,2)                  $ipivalor                           
 * @property  numeric(14,2)                  $icmsstbase                         
 * @property  numeric(14,2)                  $icmsstpercentual                   
 * @property  numeric(14,2)                  $icmsstvalor                        
 * @property  varchar(4)                     $csosn                              
 * @property  bigint                         $codnegocioprodutobarra             
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 * @property  numeric(3,0)                   $icmscst                            
 * @property  numeric(3,0)                   $ipicst                             
 * @property  numeric(3,0)                   $piscst                             
 * @property  numeric(14,2)                  $pisbase                            
 * @property  numeric(4,2)                   $pispercentual                      
 * @property  numeric(14,2)                  $pisvalor                           
 * @property  numeric(3,0)                   $cofinscst                          
 * @property  numeric(14,2)                  $cofinsbase                         
 * @property  numeric(14,2)                  $cofinsvalor                        
 * @property  numeric(14,2)                  $csllbase                           
 * @property  numeric(4,2)                   $csllpercentual                     
 * @property  numeric(14,2)                  $csllvalor                          
 * @property  numeric(14,2)                  $irpjbase                           
 * @property  numeric(4,2)                   $irpjpercentual                     
 * @property  numeric(14,2)                  $irpjvalor                          
 * @property  numeric(4,2)                   $cofinspercentual                   
 * @property  bigint                         $codnotafiscalprodutobarraorigem
 *
 * Chaves Estrangeiras
 * @property  Cfop                           $Cfop                          
 * @property  NegocioProdutoBarra            $NegocioProdutoBarra           
 * @property  NotaFiscal                     $NotaFiscal                    
 * @property  NotaFiscalProdutoBarraOrigem   $NotaFiscalProdutoBarra
 * @property  ProdutoBarra                   $ProdutoBarra                  
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 * @property  EstoqueMovimento[]             $EstoqueMovimentoS
 * @property  NotaFiscalProdutoBarra[]       $NotaFiscalProdutoBarraS
 */


class NotaFiscalProdutoBarra extends MGModel
{
    protected $table = 'tblnotafiscalprodutobarra';
    protected $primaryKey = 'codnotafiscalprodutobarra';
    protected $fillable = [
        'codnotafiscal',
        'codprodutobarra',
        'codcfop',
        'descricaoalternativa',
        'quantidade',
        'valorunitario',
        'valortotal',
        'icmsbase',
        'icmspercentual',
        'icmsvalor',
        'ipibase',
        'ipipercentual',
        'ipivalor',
        'icmsstbase',
        'icmsstpercentual',
        'icmsstvalor',
        'csosn',
        'codnegocioprodutobarra',
        'icmscst',
        'ipicst',
        'piscst',
        'pisbase',
        'pispercentual',
        'pisvalor',
        'cofinscst',
        'cofinsbase',
        'cofinsvalor',
        'csllbase',
        'csllpercentual',
        'csllvalor',
        'irpjbase',
        'irpjpercentual',
        'irpjvalor',
        'cofinspercentual',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
    ];
    
    // Chaves Estrangeiras
    public function Cfop()
    {
        return $this->belongsTo(Cfop::class, 'codcfop', 'codcfop');
    }

    public function NegocioProdutoBarra()
    {
        return $this->belongsTo(NegocioProdutoBarra::class, 'codnegocioprodutobarra', 'codnegocioprodutobarra');
    }

    public function NotaFiscal()
    {
        return $this->belongsTo(NotaFiscal::class, 'codnotafiscal', 'codnotafiscal');
    }

    public function ProdutoBarra()
    {
        return $this->belongsTo(ProdutoBarra::class, 'codprodutobarra', 'codprodutobarra');
    }

    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuarioalteracao', 'codusuario');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuariocriacao', 'codusuario');
    }

    public function NotaFiscalProdutoBarraOrigem()
    {
        return $this->belongsTo(NotaFiscalProdutoBarra::class, 'codnotafiscalprodutobarraorigem', 'codnotafiscalprodutobarra');
    }


    // Tabelas Filhas
    public function EstoqueMovimentoS()
    {
        return $this->hasMany(EstoqueMovimento::class, 'codnotafiscalprodutobarra', 'codnotafiscalprodutobarra');
    }
    
    public function NotaFiscalProdutoBarraS()
    {
        return $this->hasMany(NotaFiscalProdutoBarra::class, 'codnotafiscalprodutobarraorigem');
    }
    
    public static function search($parametros, $registros = 20)
    {
        //dd($parametros);
        $query = NotaFiscalProdutoBarra::orderBy('tblnotafiscal.saida', 'DESC');
        
        $query = $query->join('tblnotafiscal', function($join) use ($parametros) {
            $join->on('tblnotafiscal.codnotafiscal', '=', 'tblnotafiscalprodutobarra.codnotafiscal');
        });
        
        if (!empty($parametros['notasfiscais_codnaturezaoperacao']))
            $query = $query->where('tblnotafiscal.codnaturezaoperacao', '=', $parametros['notasfiscais_codnaturezaoperacao']);
        
        if (!empty($parametros['notasfiscais_codpessoa']))
            $query = $query->where('tblnotafiscal.codpessoa', '=', $parametros['notasfiscais_codpessoa']);
        
        if (!empty($parametros['notasfiscais_codfilial']))
            $query = $query->where('tblnotafiscal.codfilial', '=', $parametros['notasfiscais_codfilial']);
        
        if (!empty($parametros['notasfiscais_lancamento_de']))
            $query = $query->where('tblnotafiscal.saida', '>=', $parametros['notasfiscais_lancamento_de']);
        
        if (!empty($parametros['notasfiscais_lancamento_ate']))
            $query = $query->where('tblnotafiscal.saida', '<=', $parametros['notasfiscais_lancamento_ate']);
        
        if (!empty($parametros['notasfiscais_codproduto']))
        {
            $query = $query->join('tblprodutobarra', function($join) use ($parametros) {
                $join->on('tblprodutobarra.codprodutobarra', '=', 'tblnotafiscalprodutobarra.codprodutobarra');
            });
            
            $query = $query->join('tblprodutovariacao', function($join) use ($parametros) {
                $join->on('tblprodutovariacao.codprodutovariacao', '=', 'tblprodutobarra.codprodutovariacao');
            });
            
            $query = $query->where('tblprodutovariacao.codproduto', '=', $parametros['notasfiscais_codproduto']);
        }

        if (!empty($parametros['notasfiscais_codprodutovariacao']))
            $query->where('tblprodutovariacao.codprodutovariacao', '=', $parametros['notasfiscais_codprodutovariacao']);

        //dd($query->toSql());
        
        return $query->paginate($registros);
        
    }    

    /*
    public function quantidadeUnitaria()
    {
        return $this->ProdutoBarra->converteQuantidade($this->quantidade);
    }
    
    public function recalculaMovimentoEstoque()
    {
        $ems = $this->EstoqueMovimentoS;
        
        if ((!empty($this->NotaFiscal->nfecancelamento))
            || (!empty($this->NotaFiscal->nfeinutilizacao)))
        {
            //Apaga movimentos gerados por notas canceladas
            foreach ($ems as $em)
            {
                foreach ($em->EstoqueMovimentoS as $emOrigem)
                {
                    $emOrigem->codestoquemovimentoorigem = null;
                    $emOrigem->save();
                }
                $em->delete();
            }
            
            //retorna
            return true;
        }

        //Se houver mais re um registro para o mesmo registro da nota, apaga excedentes
        for ($i=1; $i<sizeof($ems); $i++)
            $ems[$i]->delete();
        
        //se nao existe movimento, cria novo
        if (sizeof($ems) == 0)
            $em = new EstoqueMovimento;
        else
            $em = $ems[0];
        
        $em->codnegocioprodutobarra = null;
        $em->codnotafiscalprodutobarra = $this->codnotafiscalprodutobarra;
        $mes = EstoqueMes::buscaOuCria($this->ProdutoBarra->codprodutovariacao, $this->NotaFiscal->codestoquelocal, true, $this->NotaFiscal->saida);
        $em->codestoquemes = $mes->codestoquemes;
        $em->manual = false;
        $em->data = $this->NotaFiscal->saida;
        
        $em->codestoquemovimentotipo = $this->NotaFiscal->NaturezaOperacao->codestoquemovimentotipo;
        
        $quantidade = $this->quantidadeUnitaria();
        
        $valor = 0;
        
        switch ($em->EstoqueMovimentoTipo->preco)
        {
                
            case EstoqueMovimentoTipo::PRECO_MEDIO;
                $valor = $em->EstoqueMes->customedio;
                break;
            
            case EstoqueMovimentoTipo::PRECO_ORIGEM:
                
                $nfechave = [$this->NotaFiscal->nfechave];
                foreach($this->NotaFiscal->NotaFiscalReferenciadaS as $nfref)
                    $nfechave[] = $nfref->nfechave;
                
                // Procura NF Origem baseado na chave
                if (isset($this->NotaFiscal->NaturezaOperacao->codnaturezaoperacaodevolucao))
                    $nfsOrigem = NotaFiscal
                        ::whereIn('nfechave', $nfechave)
                        ->where('codnotafiscal', '!=', $this->NotaFiscal->codnotafiscal)
                        ->where('codnaturezaoperacao', '=', $this->NotaFiscal->NaturezaOperacao->codnaturezaoperacaodevolucao)
                        ->get();
                else
                    $nfsOrigem = NotaFiscal
                        ::whereIn('nfechave', $nfechave)
                        ->where('codnotafiscal', '!=', $this->NotaFiscal->codnotafiscal)
                        ->get();

                // se nao achou a nota desiste
                if (sizeof($nfsOrigem) == 0)
                    break;
                
                // percorre as notas de origem
                foreach ($nfsOrigem as $nfOrigem)
                {
                    
                    // procura um item com a mesma quantidade
                    $nfpbsOrigem = NotaFiscalProdutoBarra
                        ::where('codnotafiscal', $nfOrigem->codnotafiscal)
                        ->where('codprodutobarra', $this->codprodutobarra)
                        ->where('quantidade', $this->quantidade)
                        ->get();

                    //se nao achou procura somente pelo codigo
                    if (sizeof($nfpbsOrigem) == 0)
                        $nfpbsOrigem = NotaFiscalProdutoBarra
                            ::where('codnotafiscal', $nfOrigem->codnotafiscal)
                            ->where('codprodutobarra', $this->codprodutobarra)
                            ->get();

                    //se nao achou origem desiste
                    if (sizeof($nfpbsOrigem) == 0)
                        break 2;
                    
                    foreach ($nfpbsOrigem[0]->EstoqueMovimentoS as $emOrigem)
                    {
                        $eif ((!empty($this->codcfop) && (!empty($this->csosn) || !empty($this->icmscst))) || !$somenteVazios)
			return true;
		
		if (empty($this->ProdutoBarra))
		{
			$this->addError('codprodutobarra', 'Erro ao calcular tributação. Produto não informado!');
			return false;
		}
		
		if (empty($this->NotaFiscal))
		{
			$this->addError('codnotafiscal', 'Erro ao calcular tributação. Nota Fiscal não informada!');
			return false;
		}
		
		if (empty($this->NotaFiscal->Pessoa))
		{
			$this->addError('codnotafiscal', 'Erro ao calcular tributação. Pessoa não informada na Nota Fiscal!');
			return false;
		}
		
		if (empty($this->NotaFiscal->Filial))
		{
			$this->addError('codnotafiscal', 'Erro ao calcular tributação. Filial não informada na Nota Fiscal!');
			return false;
		}
		
		if ($this->NotaFiscal->Pessoa->Cidade->Estado == $this->NotaFiscal->Filial->Pessoa->Cidade->Estado)
		    $filtroEstado = 'codestado = :codestado';
		else
		    $filtroEstado = '(codestado = :codestado or codestado is null)';

		$trib = TributacaoNaturezaOperacao::model()->find(
			array(
			    'condition' => 
					'   codtributacao = :codtributacao
					AND codtipoproduto = :codtipoproduto
					AND codnaturezaoperacao = :codnaturezaoperacao
					AND ' . $filtroEstado . '
					AND (:ncm ilike ncm || \'%\' or ncm is null)
					',
			    'params' => array(
					':codtributacao' => $this->ProdutoBarra->Produto->codtributacao,
					':codtipoproduto' => $this->ProdutoBarra->Produto->codtipoproduto,
					':codnaturezaoperacao' => $this->NotaFiscal->codnaturezaoperacao,
					':codestado' => $this->NotaFiscal->Pessoa->Cidade->codestado,
					':ncm' => $this->ProdutoBarra->Produto->Ncm->ncm,
				),
			    'order' => 'codestado nulls last, char_length(ncm) desc nulls last',
			)
		);
		
		if ($trib === null)
		{
			$this->addError('codprodutobarra', 'Erro ao calcular tributação. Impossível localizar tributação para o produto informado!');
			return false;
		}
		
		//Traz codigos de tributacao
		$this->codcfop = $trib->codcfop;
		
		if ($this->NotaFiscal->Filial->crt == Filial::CRT_REGIME_NORMAL)
		{
			
			//CST's
			$this->icmscst = $trib->icmscst;
			$this->ipicst = $trib->ipicst;
			$this->piscst = $trib->piscst;
			$this->cofinscst = $trib->cofinscst;
			
		    If (!empty($this->valortotal) && ($this->NotaFiscal->emitida))
		    {
				//Calcula ICMS				
				If (!empty($trib->icmslpbase))
					$this->icmsbase = round(($trib->icmslpbase * $this->valortotal)/100, 2);

				$this->icmspercentual = $trib->icmslppercentual;

				If ((!empty($this->icmsbase)) and (!empty($this->icmspercentual)))
					$this->icmsvalor = round(($this->icmsbase * $this->icmspercentual)/100, 2);
				
				//Calcula PIS
				If ($trib->pispercentual > 0)
				{
					$this->pisbase = $this->valortotal;
					$this->pispercentual = $trib->pispercentual;
					$this->pisvalor = round(($this->pisbase * $this->pispercentual)/100, 2);
				}
				
				//Calcula Cofins
				If ($trib->cofinspercentual > 0)
				{
					$this->cofinsbase = $this->valortotal;
					$this->cofinspercentual = $trib->cofinspercentual;
					$this->cofinsvalor = round(($this->cofinsbase * $this->cofinspercentual)/100, 2);
				}
				
				//Calcula CSLL
				If ($trib->csllpercentual > 0)
				{
					$this->csllbase = $this->valortotal;
					$this->csllpercentual = $trib->csllpercentual;
					$this->csllvalor = round(($this->csllbase * $this->csllpercentual)/100, 2);
				}
				
				//Calcula IRPJ
				If ($trib->irpjpercentual > 0)
				{
					$this->irpjbase = $this->valortotal;
					$this->irpjpercentual = $trib->irpjpercentual;
					$this->irpjvalor = round(($this->irpjbase * $this->irpjpercentual)/100, 2);
				}
				
			}
		}
		else
		{
		    $this->csosn = $trib->csosn;

		    //Calcula ICMSs
		    If (!empty($this->valortotal) && ($this->NotaFiscal->emitida))
		    {
			    If (!empty($trib->icmsbase))
				    $this->icmsbase = round(($trib->icmsbase * $this->valortotal)/100, 2);

			    $this->icmspercentual = $trib->icmspercentual;

			    If ((!empty($this->icmsbase)) and (!empty($this->icmspercentual)))
				    $this->icmsvalor = round(($this->icmsbase * $this->icmspercentual)/100, 2);
			}
		}m->codestoquemovimentoorigem = $emOrigem->codestoquemovimento;
                        $valor = $emOrigem->EstoqueMes->customedio;
                    }   
                    
                }
                break;
                
            
            case EstoqueMovimentoTipo::PRECO_INFORMADO:
            default:
                $valor = 
                    $this->valortotal + 
                    $this->icmsstvalor + 
                    $this->ipivalor;
                
                if ($this->NotaFiscal->valordesconto > 0)
                    $valor -= ($this->NotaFiscal->valordesconto / $this->NotaFiscal->valorprodutos) * $this->valortotal;
                
                if ($this->NotaFiscal->valorfrete > 0)
                    $valor += ($this->NotaFiscal->valorfrete / $this->NotaFiscal->valorprodutos) * $this->valortotal;
                
                if ($this->NotaFiscal->valoroutras > 0)
                    $valor += ($this->NotaFiscal->valoroutras / $this->NotaFiscal->valorprodutos) * $this->valortotal;
                
                if ($this->NotaFiscal->valorseguro > 0)
                    $valor += ($this->NotaFiscal->valorseguro / $this->NotaFiscal->valorprodutos) * $this->valortotal;
                break;
                
        }
        
        if ($this->NotaFiscal->NaturezaOperacao->codoperacao == Operacao::ENTRADA)
        {
            $em->entradaquantidade = $quantidade;
            $em->entradavalor = $valor;
            $em->saidaquantidade = null;
            $em->saidavalor = null;
        }
        else
        {
            $em->entradaquantidade = null;
            $em->entradavalor = null;
            $em->saidaquantidade = $quantidade;
            $em->saidavalor = $valor;
        }
        return $em->save();
    }
     * 
     */
    
    public function calculaTributacao()
    {
        
        $trib = TributacaoNaturezaOperacao
                ::where('codtributacao', $this->ProdutoBarra->Produto->codtributacao)
                ->where('codtipoproduto', $this->ProdutoBarra->Produto->codtipoproduto)
                ->where('codnaturezaoperacao', $this->NotaFiscal->codnaturezaoperacao)
                ->whereRaw("('{$this->ProdutoBarra->Produto->Ncm->ncm}' ilike ncm || '%' or ncm is null)");
                
        if ($this->NotaFiscal->Pessoa->Cidade->codestado == $this->NotaFiscal->Filial->Pessoa->Cidade->codestado) {
            $trib->where('codestado', $this->NotaFiscal->Pessoa->Cidade->codestado);
            $filtroEstado = 'codestado = :codestado';
        } else {
            $trib->whereNull('codestado');
        }
        
        if (!($trib = $trib->first())) {
            echo '<h1>Erro Ao Calcular Tributacao</h1>';
            dd($this);
            return false;
        }
        
        //Traz codigos de tributacao
        $this->codcfop = $trib->codcfop;

        if ($this->NotaFiscal->Filial->crt == Filial::CRT_REGIME_NORMAL)
        {

            //CST's
            $this->icmscst = $trib->icmscst;
            $this->ipicst = $trib->ipicst;
            $this->piscst = $trib->piscst;
            $this->cofinscst = $trib->cofinscst;

            If (!empty($this->valortotal) && ($this->NotaFiscal->emitida))
            {
                    //Calcula ICMS				
                    If (!empty($trib->icmslpbase))
                        $this->icmsbase = round(($trib->icmslpbase * $this->valortotal)/100, 2);

                    $this->icmspercentual = $trib->icmslppercentual;

                    If ((!empty($this->icmsbase)) and (!empty($this->icmspercentual)))
                        $this->icmsvalor = round(($this->icmsbase * $this->icmspercentual)/100, 2);

                    //Calcula PIS
                    If ($trib->pispercentual > 0)
                    {
                        $this->pisbase = $this->valortotal;
                        $this->pispercentual = $trib->pispercentual;
                        $this->pisvalor = round(($this->pisbase * $this->pispercentual)/100, 2);
                    }

                    //Calcula Cofins
                    If ($trib->cofinspercentual > 0)
                    {
                        $this->cofinsbase = $this->valortotal;
                        $this->cofinspercentual = $trib->cofinspercentual;
                        $this->cofinsvalor = round(($this->cofinsbase * $this->cofinspercentual)/100, 2);
                    }

                    //Calcula CSLL
                    If ($trib->csllpercentual > 0)
                    {
                        $this->csllbase = $this->valortotal;
                        $this->csllpercentual = $trib->csllpercentual;
                        $this->csllvalor = round(($this->csllbase * $this->csllpercentual)/100, 2);
                    }

                    //Calcula IRPJ
                    If ($trib->irpjpercentual > 0)
                    {
                        $this->irpjbase = $this->valortotal;
                        $this->irpjpercentual = $trib->irpjpercentual;
                        $this->irpjvalor = round(($this->irpjbase * $this->irpjpercentual)/100, 2);
                    }

            }
        }
        else
        {
            $this->csosn = $trib->csosn;

            //Calcula ICMSs
            If (!empty($this->valortotal) && ($this->NotaFiscal->emitida)) {
                If (!empty($trib->icmsbase))
                    $this->icmsbase = round(($trib->icmsbase * $this->valortotal)/100, 2);

                $this->icmspercentual = $trib->icmspercentual;

                If ((!empty($this->icmsbase)) and (!empty($this->icmspercentual)))
                    $this->icmsvalor = round(($this->icmsbase * $this->icmspercentual)/100, 2);
            }
        }        
    }
    
}
