-- Function: criatriggers_geraauditoria(boolean)

-- DROP FUNCTION fnCalculaEstoque(pCodProduto bigint);

CREATE OR REPLACE FUNCTION fnCalculaEstoque(pCodProduto bigint)
  RETURNS boolean AS
  
$BODY$
--
--DECLARE
--	rTabelas RECORD;
BEGIN

	--limpa movimento
	delete from tblestoquemovimento where codproduto = pCodProduto;

	--insere estoque inicial
	insert into tblestoquemovimento (codfilial, codproduto, fiscal, codestoquemovimentotipo, lancamento, entradaquantidade, entradavalor)
	  select codfilial, codproduto, fiscal, 100, '2011-12-31', saldoquantidade, saldovalor from tblestoquesaldo where codproduto = pCodProduto;

	-- NOTAS DE ENTRADAS
	insert into tblestoquemovimento (codfilial, codproduto, fiscal, codestoquemovimentotipo, lancamento, entradaquantidade, entradavalor, codnotafiscalprodutobarra)
	select 
	       codfilial
	     , tblprodutobarra.codproduto
	     , true
	     , 200
	     , saida
	     --, tblnotafiscalprodutobarra.quantidade
	     --, tblprodutoembalagem.quantidade
	     , tblnotafiscalprodutobarra.quantidade * coalesce(tblprodutoembalagem.quantidade, 1)
	     , tblnotafiscalprodutobarra.valortotal
	     , tblnotafiscalprodutobarra.codnotafiscalprodutobarra
	  from tblnotafiscal 
	 inner join tblnotafiscalprodutobarra on (tblnotafiscalprodutobarra.codnotafiscal = tblnotafiscal.codnotafiscal)
	 inner join tblprodutobarra on (tblprodutobarra.codprodutobarra = tblnotafiscalprodutobarra.codprodutobarra)
	  left join tblprodutoembalagem on (tblprodutoembalagem.codprodutoembalagem = tblprodutobarra.codprodutoembalagem)
	 where tblnotafiscal.saida between '2012-01-01 00:00:00.0' and '2012-12-31 23:59:59.9'
	   and tblnotafiscal.nfecancelamento is null
	   and tblnotafiscal.codoperacao = 1
	   and tblprodutobarra.codproduto = pCodProduto;

	-- NOTAS DE SAIDAS
	insert into tblestoquemovimento (codfilial, codproduto, fiscal, codestoquemovimentotipo, lancamento, saidaquantidade, saidavalor, codnotafiscalprodutobarra)
	select 
	       tblnotafiscal.codfilial
	     , tblprodutobarra.codproduto
	     , true
	     , 200
	     , saida
	     , tblnotafiscalprodutobarra.quantidade * coalesce(tblprodutoembalagem.quantidade, 1)
	     , tblnotafiscalprodutobarra.quantidade * coalesce(tblprodutoembalagem.quantidade, 1) * coalesce(iqprecofilial.media, iqprecoproduto.media, tblnotafiscalprodutobarra.valorunitario * 0.6, 0) as custo
	     , tblnotafiscalprodutobarra.codnotafiscalprodutobarra
	  from tblnotafiscal 
	 inner join tblnotafiscalprodutobarra on (tblnotafiscalprodutobarra.codnotafiscal = tblnotafiscal.codnotafiscal)
	 inner join tblprodutobarra on (tblprodutobarra.codprodutobarra = tblnotafiscalprodutobarra.codprodutobarra)
	  left join tblprodutoembalagem on (tblprodutoembalagem.codprodutoembalagem = tblprodutobarra.codprodutoembalagem)
	  left join (
			  select tblestoquemovimento.codproduto
			       , tblestoquemovimento.codfilial
			       , sum(tblestoquemovimento.entradavalor) / sum(tblestoquemovimento.entradaquantidade) as media 
			  from tblestoquemovimento 
			  group by tblestoquemovimento.codproduto
				 , tblestoquemovimento.codfilial
		    ) iqprecofilial on (iqprecofilial.codproduto = tblprodutobarra.codproduto and iqprecofilial.codfilial = tblnotafiscal.codfilial)
	  left join (
			  select tblestoquemovimento.codproduto
			       , sum(tblestoquemovimento.entradavalor) / sum(tblestoquemovimento.entradaquantidade) as media 
			  from tblestoquemovimento 
			  group by tblestoquemovimento.codproduto
		    ) iqprecoproduto on (iqprecoproduto.codproduto = tblprodutobarra.codproduto)
	 where tblnotafiscal.saida between '2012-01-01 00:00:00.0' and '2012-12-31 23:59:59.9'
	   and tblnotafiscal.nfecancelamento is null
	   and tblnotafiscal.codoperacao = 2
	   and tblprodutobarra.codproduto = pCodProduto;


	--CUPOM FISCAL
	insert into tblestoquemovimento (codfilial, codproduto, fiscal, codestoquemovimentotipo, lancamento, saidaquantidade, saidavalor, codcupomfiscalprodutobarra)
	select 
	       tblecf.codfilial
	     , tblprodutobarra.codproduto
	     , true
	     , 300
	     , datamovimento
	     , tblcupomfiscalprodutobarra.quantidade * coalesce(tblprodutoembalagem.quantidade, 1)
	     , tblcupomfiscalprodutobarra.quantidade * coalesce(tblprodutoembalagem.quantidade, 1) * coalesce(iqprecofilial.media, iqprecoproduto.media, tblcupomfiscalprodutobarra.valorunitario * 0.6, 0) as custo
	     , tblcupomfiscalprodutobarra.codcupomfiscalprodutobarra
	  from tblcupomfiscal 
	 inner join tblecf on (tblecf.codecf = tblcupomfiscal.codecf)
	 inner join tblcupomfiscalprodutobarra on (tblcupomfiscalprodutobarra.codcupomfiscal = tblcupomfiscal.codcupomfiscal)
	 inner join tblprodutobarra on (tblprodutobarra.codprodutobarra = tblcupomfiscalprodutobarra.codprodutobarra)
	  left join tblprodutoembalagem on (tblprodutoembalagem.codprodutoembalagem = tblprodutobarra.codprodutoembalagem)
	  left join (
			  select tblestoquemovimento.codproduto
			       , tblestoquemovimento.codfilial
			       , sum(tblestoquemovimento.entradavalor) / sum(tblestoquemovimento.entradaquantidade) as media 
			  from tblestoquemovimento 
			  group by tblestoquemovimento.codproduto
				 , tblestoquemovimento.codfilial
		    ) iqprecofilial on (iqprecofilial.codproduto = tblprodutobarra.codproduto and iqprecofilial.codfilial = tblecf.codfilial)
	  left join (
			  select tblestoquemovimento.codproduto
			       , sum(tblestoquemovimento.entradavalor) / sum(tblestoquemovimento.entradaquantidade) as media 
			  from tblestoquemovimento 
			  group by tblestoquemovimento.codproduto
		    ) iqprecoproduto on (iqprecoproduto.codproduto = tblprodutobarra.codproduto)
	 where tblcupomfiscal.datamovimento between '2012-01-01 00:00:00.0' and '2012-12-31 23:59:59.9'
	   and tblcupomfiscal.cancelado = false
	   and tblprodutobarra.codproduto = pCodProduto;
	 
	return true;
END;

$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;

select fnCalculaEstoque(017113);

select 
	   codfilial
	 , grupoproduto
	 , subgrupoproduto
	 , tblproduto.codproduto
	, produto
	, tblproduto.preco
	 , entradaquantidade
	 , entradavalor
	  , saidaquantidade
	 , case when (entradaquantidade > 0) then entradavalor / entradaquantidade end as custo
	from (
	 select
	    tblestoquemovimento.codfilial
	  , tblestoquemovimento.codproduto
	  , sum(coalesce(tblestoquemovimento.entradaquantidade, 0)) as entradaquantidade
	  , sum(coalesce(tblestoquemovimento.entradavalor, 0)) as entradavalor
	  , sum(coalesce(tblestoquemovimento.saidaquantidade, 0)) as saidaquantidade
	   from tblestoquemovimento 
	 group by tblestoquemovimento.codfilial, tblestoquemovimento.codproduto
	 ) iqsaldo
	  left join tblproduto on (tblproduto.codproduto = iqsaldo.codproduto)
	  left join tblsubgrupoproduto on (tblsubgrupoproduto.codsubgrupoproduto = tblproduto.codsubgrupoproduto)
	  left join tblgrupoproduto on (tblgrupoproduto.codgrupoproduto = tblsubgrupoproduto.codgrupoproduto)
	where tblproduto.codproduto = 017113
	order by produto, codfilial, tblproduto.preco;