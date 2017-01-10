select 
	  extract(month from (case when (extract(day from lancamento) >= 26) then lancamento + interval '1 month' else lancamento end)) as mes
	, extract(year from (case when (extract(day from lancamento) >= 26) then lancamento + interval '1 month' else lancamento end)) as ano
	, f.filial
	, v.fantasia
	, sum(case when coalesce(n.valortotal, 0) > 0 then npb.valortotal * (1-(coalesce(n.valordesconto, 0) / coalesce(n.valortotal, 0))) else 0 end) as venda
from tblnegocio n
inner join tblnaturezaoperacao no on (no.codnaturezaoperacao = n.codnaturezaoperacao)
inner join tblnegocioprodutobarra npb on (npb.codnegocio = n.codnegocio)
inner join tblprodutobarra pb on (pb.codprodutobarra = npb.codprodutobarra)
inner join tblproduto p on (p.codproduto = pb.codproduto)
inner join tblfilial f on (f.codfilial = n.codfilial)
left join tblpessoa v on (v.codpessoa = n.codpessoavendedor)
where no.venda = true
and n.codfilial not in (select distinct f2.codpessoa from tblfilial f2)
and n.codnegociostatus = 2 -- Fechado
and p.codsubgrupoproduto != 2951 --Xerox
--and n.lancamento >= '2012-12-26 00:00:00.0'
and n.lancamento >= '2016-06-26 00:00:00.0'
group by
	extract(month from (case when (extract(day from lancamento) >= 26) then lancamento + interval '1 month' else lancamento end)) 
	, extract(year from (case when (extract(day from lancamento) >= 26) then lancamento + interval '1 month' else lancamento end))
	, f.filial
	, v.fantasia


select 
	  extract(month from (case when (extract(day from lancamento) >= 26) then lancamento + interval '1 month' else lancamento end)) as mes
	, extract(year from (case when (extract(day from lancamento) >= 26) then lancamento + interval '1 month' else lancamento end)) as ano
	, f.filial
	--, v.fantasia
	, sum(case when coalesce(n.valortotal, 0) > 0 then npb.valortotal * (1-(coalesce(n.valordesconto, 0) / coalesce(n.valortotal, 0))) else 0 end) as venda
from tblnegocio n
inner join tblnaturezaoperacao no on (no.codnaturezaoperacao = n.codnaturezaoperacao)
inner join tblnegocioprodutobarra npb on (npb.codnegocio = n.codnegocio)
inner join tblprodutobarra pb on (pb.codprodutobarra = npb.codprodutobarra)
inner join tblproduto p on (p.codproduto = pb.codproduto)
inner join tblfilial f on (f.codfilial = n.codfilial)
left join tblpessoa v on (v.codpessoa = n.codpessoavendedor)
where no.venda = true
and n.codfilial not in (select distinct f2.codpessoa from tblfilial f2)
and n.codnegociostatus = 2 -- Fechado
and p.codsubgrupoproduto = 2951 --Xerox
and n.lancamento >= '2012-12-26 00:00:00.0'
--and n.lancamento >= '2016-06-26 00:00:00.0'
group by
	extract(month from (case when (extract(day from lancamento) >= 26) then lancamento + interval '1 month' else lancamento end)) 
	, extract(year from (case when (extract(day from lancamento) >= 26) then lancamento + interval '1 month' else lancamento end))
	, f.filial
	--, v.fantasia
