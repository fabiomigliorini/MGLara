select 
	v.fantasia as vendedor
	, gc.grupocliente
	, c.fantasia
	, f.filial
	, extract(month from n.lancamento) as mes
	, extract(year from n.lancamento) as ano
	, sum(n.valortotal) as valortotal
from tblnegocio n
inner join tblfilial f on (f.codfilial = n.codfilial)
inner join tblpessoa c on (c.codpessoa = n.codpessoa)
left join tblgrupocliente gc on (gc.codgrupocliente = c.codgrupocliente)
left join tblpessoa v on (v.codpessoa = n.codpessoavendedor)
where n.codnaturezaoperacao in (1, 5) -- Venda
and n.codnegociostatus = 2 -- Fechado
and n.codpessoa not in (select f2.codpessoa from tblfilial f2) -- Não filial
and n.lancamento >= '2014-01-01 00:00:00'
group by
	v.fantasia 
	, gc.grupocliente
	, c.fantasia
	, f.filial
	, extract(month from n.lancamento) 
	, extract(year from n.lancamento)
--limit 100

--select * from tblnaturezaoperacao

--select * from tblgrupocliente