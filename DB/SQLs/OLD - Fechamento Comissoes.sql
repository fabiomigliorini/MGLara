-- VENDAS
select filial, fantasiavendedor, round(sum(valortotalfinal), 0) 
from vwnegocio 
where lancamento between '2014-04-26 00:00:00.0' and '2014-05-25 23:59:59.9'
and codnegociostatus = 2
and vwnegocio.codpessoa not in (select tblfilial.codpessoa from tblfilial)
group by filial, fantasiavendedor
order by 2 asc 

-- XEROX
select 
	iq.filial
	, iq.codproduto
	, iq.produto
	, round(sum(iq.quantidade), 0) as quantidade
	, round(sum(iq.valortotal), 0) as totalbruto
	, round(sum(iq.totaldescontado), 0) as totalliquido
from 
	(
	select 
		vn.filial
		, p.codproduto
		, p.produto
		, npb.valortotal
		, npb.quantidade
		, ((coalesce(vn.valordesconto, 0) / coalesce(vn.valortotal, 1)) * 100) as desconto
		, npb.valortotal * (1 - (coalesce(vn.valordesconto, 0) / coalesce(vn.valortotal, 1))) as totaldescontado
	from tblnegocioprodutobarra npb 
	inner join tblprodutobarra pb on (pb.codprodutobarra = npb.codprodutobarra)
	inner join tblproduto p on (p.codproduto = pb.codproduto)
	inner join vwnegocio vn on (vn.codnegocio = npb.codnegocio)
	where vn.codnegociostatus = 2 -- fechado
	and vn.codpessoa not in (select tblfilial.codpessoa from tblfilial)
	and vn.lancamento between '2014-04-26 00:00:00.00' and '2014-05-25 23:59:59.9'
	and p.codsubgrupoproduto = 17001 --xerox
	) iq
group by iq.filial, iq.codproduto, iq.produto
order by 1, 2, 3




