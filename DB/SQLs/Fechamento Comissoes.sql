-- VENDAS
select 
	f.codfilial
	, f.filial
	, gp.codgrupoproduto
	, gp.grupoproduto
	, sgp.codsubgrupoproduto
	, sgp.subgrupoproduto
	, pv.codpessoa
	, pv.fantasia
	, coalesce(npb.valortotal, 0) as valorbruto
	, coalesce(n.valortotal, 0) / coalesce(n.valorprodutos, 0)
	, (coalesce(n.valortotal, 0) / coalesce(n.valorprodutos, 0)) * coalesce(npb.valortotal, 0) as valorliquido
	, n.codnegocio
from tblnegocio n
inner join tblfilial f on (f.codfilial = n.codfilial)
inner join tblnegocioprodutobarra npb on (npb.codnegocio = n.codnegocio)
inner join tblprodutobarra pb on (pb.codprodutobarra = npb.codprodutobarra)
left join tblproduto p on (p.codproduto = pb.codproduto)
left join tblsubgrupoproduto sgp on (sgp.codsubgrupoproduto = p.codsubgrupoproduto)
left join tblgrupoproduto gp on (gp.codgrupoproduto = sgp.codgrupoproduto)
left join tblpessoa pv on (pv.codpessoa = n.codpessoavendedor)
where lancamento between '2014-05-26 00:00:00.0' and '2014-06-25 23:59:59.9'
and codnegociostatus = 2
and n.codpessoa not in (select tblfilial.codpessoa from tblfilial)
--and n.codnegocio = 30362143
--group by filial, pv.fantasia
--order by 2 asc 
--limit 100


/*
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




*/

