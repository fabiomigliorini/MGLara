select * from tblestoquelocalprodutovariacao where vendaanoquantidade is not null

	select 
		tblnegocio.codestoquelocal
		, tblprodutobarra.codprodutovariacao
		--, tblprodutobarra.codproduto
		, sum(tblnegocioprodutobarra.quantidade * coalesce(tblprodutoembalagem.quantidade, 1)) as vendaanoquantidade
		, sum(tblnegocioprodutobarra.valortotal) as vendaanovalor
		, sum(case when (tblnegocio.lancamento >= (current_date - interval '6 months')) then tblnegocioprodutobarra.quantidade * coalesce(tblprodutoembalagem.quantidade, 1) else 0 end) as vendasemestrequantidade
		, sum(case when (tblnegocio.lancamento >= (current_date - interval '6 months')) then tblnegocioprodutobarra.valortotal else 0 end) as vendasemestrevalor
		, sum(case when (tblnegocio.lancamento >= '2016-07-17T00:00:00-0400') then tblnegocioprodutobarra.quantidade * coalesce(tblprodutoembalagem.quantidade, 1) else 0 end) as vendabimestrequantidade
		, sum(case when (tblnegocio.lancamento >= '2016-07-17T00:00:00-0400') then tblnegocioprodutobarra.valortotal else 0 end) as vendabimestrevalor
	from tblnegocio 
	inner join tblnaturezaoperacao on (tblnaturezaoperacao.codnaturezaoperacao = tblnegocio.codnaturezaoperacao)
	inner join tblnegocioprodutobarra on (tblnegocioprodutobarra.codnegocio = tblnegocio.codnegocio)
	inner join tblprodutobarra on (tblprodutobarra.codprodutobarra = tblnegocioprodutobarra.codprodutobarra)
	left join tblprodutoembalagem on (tblprodutoembalagem.codprodutoembalagem = tblprodutobarra.codprodutoembalagem)
	where tblnegocio.codnegociostatus = 2 --Fechado
	and tblnegocio.lancamento >= (current_date - interval '1 year')
	and tblnaturezaoperacao.venda = true
	--and tblprodutobarra.codprodutovariacao in (14991, 14992)
	and tblprodutobarra.codproduto in (100)
	group by 
		tblnegocio.codestoquelocal
		, tblprodutobarra.codprodutovariacao
		, tblprodutobarra.codproduto
		
/*
select 
	tblproduto.codproduto
	, tblproduto.produto
	, tblproduto.referencia
	, tblproduto.preco
	, tblunidademedida.codunidademedida
	, tblunidademedida.sigla as siglaunidademedida
	, tblsubgrupoproduto.codsubgrupoproduto
	, tblsubgrupoproduto.subgrupoproduto
	, tblgrupoproduto.codgrupoproduto
	, tblgrupoproduto.grupoproduto
	, tblfamiliaproduto.codfamiliaproduto
	, tblfamiliaproduto.familiaproduto
	, tblsecaoproduto.codsecaoproduto
	, tblsecaoproduto.secaoproduto
	, tblmarca.codmarca
	, tblmarca.marca
	, tblprodutovariacao.codprodutovariacao
	, tblprodutovariacao.variacao
	, tblprodutovariacao.referencia
	, tblestoquelocal.codestoquelocal
	, tblestoquelocal.sigla as siglaestoquelocal
	, tblestoquelocalprodutovariacao.codestoquelocalprodutovariacao
	, tblestoquelocalprodutovariacao.corredor
	, tblestoquelocalprodutovariacao.prateleira
	, tblestoquelocalprodutovariacao.coluna
	, tblestoquelocalprodutovariacao.bloco
	, tblestoquelocalprodutovariacao.estoqueminimo
	, tblestoquelocalprodutovariacao.estoquemaximo
	, tblestoquesaldo.codestoquesaldo
	, tblestoquesaldo.saldoquantidade
	, tblestoquesaldo.customedio
	, tblestoquesaldo.saldovalor
	, tblestoquesaldo.dataentrada
	, venda.ano
	, venda.semestre
	, venda.bimestre
	, venda.previsaoquinzena
	--, cast(current_date - interval '2 years' as bigint)
	--, age(current_date, current_date - interval '2 years')
	--, date_part('year', age(current_date, current_date - interval '2 years')) as diassemestre
from tblproduto
join tblunidademedida on (tblunidademedida.codunidademedida = tblproduto.codunidademedida)
left join tblsubgrupoproduto on (tblsubgrupoproduto.codsubgrupoproduto = tblproduto.codsubgrupoproduto)
left join tblgrupoproduto on (tblgrupoproduto.codgrupoproduto = tblsubgrupoproduto.codgrupoproduto)
left join tblfamiliaproduto on (tblfamiliaproduto.codfamiliaproduto = tblgrupoproduto.codfamiliaproduto)
left join tblsecaoproduto on (tblsecaoproduto.codsecaoproduto = tblfamiliaproduto.codsecaoproduto)
left join tblmarca on (tblmarca.codmarca = tblproduto.codmarca)
left join tblprodutovariacao on (tblprodutovariacao.codproduto = tblproduto.codproduto)
join tblestoquelocal on (1=1)
left join tblestoquelocalprodutovariacao on (tblestoquelocalprodutovariacao.codprodutovariacao = tblprodutovariacao.codprodutovariacao and tblestoquelocalprodutovariacao.codestoquelocal = tblestoquelocal.codestoquelocal)
left join tblestoquesaldo on (tblestoquesaldo.codestoquelocalprodutovariacao = tblestoquelocalprodutovariacao.codestoquelocalprodutovariacao and tblestoquesaldo.fiscal = false)
left join (
	select 
		tblnegocio.codestoquelocal
		, tblprodutobarra.codprodutovariacao
		, tblprodutobarra.codproduto
		, sum(tblnegocioprodutobarra.quantidade * coalesce(tblprodutoembalagem.quantidade, 1)) as ano
		, sum(case when (tblnegocio.lancamento >= (current_date - interval '6 months')) then tblnegocioprodutobarra.quantidade * coalesce(tblprodutoembalagem.quantidade, 1) else 0 end) as semestre
		, sum(case when (tblnegocio.lancamento >= (current_date - interval '2 months')) then tblnegocioprodutobarra.quantidade * coalesce(tblprodutoembalagem.quantidade, 1) else 0 end) as bimestre
		, (sum(case when (tblnegocio.lancamento >= (current_date - interval '6 months')) then tblnegocioprodutobarra.quantidade * coalesce(tblprodutoembalagem.quantidade, 1) else 0 end) / extract(days from (current_date - (current_date - interval '6 months')))) * 15 as previsaoquinzena
	from tblnegocio 
	inner join tblnaturezaoperacao on (tblnaturezaoperacao.codnaturezaoperacao = tblnegocio.codnaturezaoperacao)
	inner join tblnegocioprodutobarra on (tblnegocioprodutobarra.codnegocio = tblnegocio.codnegocio)
	inner join tblprodutobarra on (tblprodutobarra.codprodutobarra = tblnegocioprodutobarra.codprodutobarra)
	left join tblprodutoembalagem on (tblprodutoembalagem.codprodutoembalagem = tblprodutobarra.codprodutoembalagem)
	where tblnegocio.codnegociostatus = 2 --Fechado
	and tblnegocio.lancamento >= (current_date - interval '1 year')
	--and tblnegocio.codnaturezaoperacao in (select tblnaturezaoperacao.codnaturezaoperacao from tblnaturezaoperacao where tblnaturezaoperacao.venda = true)
	and tblnaturezaoperacao.venda = true
	and tblprodutobarra.codprodutovariacao in (14991, 14992)
	group by 
		tblnegocio.codestoquelocal
		, tblprodutobarra.codprodutovariacao
		, tblprodutobarra.codproduto
	) venda on (venda.codestoquelocal = tblestoquelocal.codestoquelocal and venda.codprodutovariacao = tblprodutovariacao.codprodutovariacao)
where tblestoquelocal.inativo is null
--and tblestoquelocal.codestoquelocal = 101001
and tblproduto.codmarca = 29 -- Acrilex
--and tblproduto.codmarca = 30000020 -- ACC
and tblproduto.inativo is null
--and tblproduto.codproduto in (9785, 9786, 9787)
*/
--select current_date - interval '2 months', current_date - interval '6 months', current_date - interval '1 year'
/*
select 
	current_date - interval '2 years'
	, extract(days from (current_date - (current_date - interval '2 months')))
	--, cast((current_date - (current_date - interval '2 months')))
*/
/*
select el.sigla, pv.variacao
from tblestoquelocal el
inner join tblprodutovariacao pv on (1=1)
left join tblestoquelocalprodutovariacao elpv on (elpv.codestoquelocal = el.codestoquelocal and elpv.codprodutovariacao = pv.codprodutovariacao)
where pv.codprodutovariacao in (14991, 14992)
*/