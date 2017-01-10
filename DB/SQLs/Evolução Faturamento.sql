select extract(year from lancamento) , extract(month from lancamento) , filial, pv.fantasia fantasiavendedor, sum(n.valortotal), p.fantasia, gc.grupocliente
from tblnegocio n
left join tblfilial f on (f.codfilial = n.codfilial)
left join tblpessoa pv on (pv.codpessoa = n.codpessoavendedor)
left join tblpessoa p on (p.codpessoa = n.codpessoa)
left join tblgrupocliente gc on (gc.codgrupocliente = p.codgrupocliente)
where lancamento >= '2015-01-01 00:00:00.0'
and lancamento <= '2016-08-31 23:59:59.9'
and codnegociostatus = 2
and n.codnaturezaoperacao in (1, 5) -- venda
and n.codpessoa not in (select tblfilial.codpessoa from tblfilial)
group by filial, fantasiavendedor, extract(year from lancamento), extract(month from lancamento), p.fantasia, gc.grupocliente
order by 2 asc

/*

select sum(n.valortotal), filial
from tblnegocio n
left join tblfilial f on (f.codfilial = n.codfilial)
left join tblpessoa pv on (pv.codpessoa = n.codpessoavendedor)
left join tblpessoa p on (p.codpessoa = n.codpessoa)
left join tblgrupocliente gc on (gc.codgrupocliente = p.codgrupocliente)
where lancamento >= '2016-08-01 00:00:00.0'
and lancamento <= '2016-08-24 23:59:59.9'
and codnegociostatus = 2
and n.codnaturezaoperacao in (1, 5) -- venda
and n.codpessoa not in (select tblfilial.codpessoa from tblfilial)
group by filial--, fantasiavendedor, extract(year from lancamento), extract(month from lancamento), p.fantasia, gc.grupocliente
--order by 2 asc

*/
