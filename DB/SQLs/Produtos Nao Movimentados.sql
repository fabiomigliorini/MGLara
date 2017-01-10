select 
	'http://10.0.1.4/MGLara/estoque-saldo/' || cast(es.codestoquesaldo as varchar) || '/zera'
 --codestoquesaldo, gp.grupoproduto, sgp.codsubgrupoproduto, sgp.subgrupoproduto, m.codmarca, m.marca, p.codproduto, p.produto, el.codestoquelocal, el.estoquelocal, saldoquantidade, saldovalor
from tblestoquesaldo es
left join tblestoquelocal el on (el.codestoquelocal = es.codestoquelocal)
left join tblproduto p on (p.codproduto = es.codproduto)
left join tblmarca m on (m.codmarca = p.codmarca)
left join tblsubgrupoproduto sgp on (sgp.codsubgrupoproduto = p.codsubgrupoproduto)
left join tblgrupoproduto gp on (gp.codgrupoproduto = sgp.codgrupoproduto)
where es.codproduto not in (
	select distinct pb.codproduto
	from tblnegocio n
	inner join tblnegocioprodutobarra npb on (npb.codnegocio = n.codnegocio)
	inner join tblprodutobarra pb on (pb.codprodutobarra = npb.codprodutobarra)
	where n.lancamento >= '2015-01-01 00:00:00.0'
	union
	select distinct pb.codproduto
	from tblnotafiscal n
	inner join tblnotafiscalprodutobarra npb on (npb.codnotafiscal = n.codnotafiscal)
	inner join tblprodutobarra pb on (pb.codprodutobarra = npb.codprodutobarra)
	where n.saida >= '2015-01-01 00:00:00.0'
)
and es.saldoquantidade > 0
order by gp.grupoproduto, sgp.subgrupoproduto, m.marca, p.produto




--select * from tblestoquemovimentotipo