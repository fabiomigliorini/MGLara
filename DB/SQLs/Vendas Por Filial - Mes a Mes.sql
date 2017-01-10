select extract(year from lancamento) as ano, extract(month from lancamento) as mes, filial, fantasiavendedor, sum(valortotalfinal)
from vwnegocio 
where lancamento >= '2011-12-01 00:00:00.0'
and codnegociostatus = 2
and vwnegocio.codpessoa not in (select tblfilial.codpessoa from tblfilial)
group by filial, fantasiavendedor, extract(year from lancamento), extract(month from lancamento)  
order by 2 asc 