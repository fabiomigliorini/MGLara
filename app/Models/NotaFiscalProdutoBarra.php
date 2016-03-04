<?php

namespace MGLara\Models;

class NotaFiscalProdutoBarra extends MGModel
{
    protected $table = 'tblnotafiscalprodutobarra';
    protected $primaryKey = 'codnotafiscalprodutobarra';
    
    public function ProdutoBarra()
    {
        return $this->belongsTo(ProdutoBarra::class, 'codprodutobarra', 'codprodutobarra');
    }
    
    public function NotaFiscal()
    {
        return $this->belongsTo(NotaFiscal::class, 'codnotafiscal', 'codnotafiscal');
    }
    
    public function NegocioProdutoBarra()
    {
        return $this->belongsTo(NegocioProdutoBarra::class, 'codnegocioprodutobarra', 'codnegocioprodutobarra');
    }
    
    public function EstoqueMovimentoS()
    {
        return $this->hasMany(EstoqueMovimento::class, 'codnotafiscalprodutobarra', 'codnotafiscalprodutobarra');
    }
    
    public function quantidadeUnitaria()
    {
        return $this->ProdutoBarra->converteQuantidade($this->quantidade);
    }
    
    public function recalculaEstoque()
    {
        $ems = $this->EstoqueMovimentoS;
        
        if ((!empty($this->NotaFiscal->nfecancelamento))
            || (!empty($this->NotaFiscal->nfeinutilizacao)))
        {
            //Apaga movimentos gerados por notas canceladas
            foreach ($ems as $em)
                $em->delete();
            
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
        
        $em->codestoquemovimentotipo = $this->NotaFiscal->NaturezaOperacao->codestoquemovimentotipo;
        
        $quantidade = $this->quantidadeUnitaria();
        $valor = $this->valortotal + $this->icmsstvalor + $this->ipivalor;
        
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
        $em->codnegocioprodutobarra = null;
        $em->codnotafiscalprodutobarra = $this->codnotafiscalprodutobarra;
        $mes = EstoqueMes::buscaOuCria($this->ProdutoBarra->codproduto, $this->NotaFiscal->codestoquelocal, true, $this->NotaFiscal->saida);
        $em->codestoquemes = $mes->codestoquemes;
        $em->manual = false;
        $em->data = $this->NotaFiscal->saida;
        return $em->save();
    }
    
}
