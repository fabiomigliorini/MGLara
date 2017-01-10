select tblproduto.codproduto, tblproduto.produto, iq.vendas, iq.total, tblmarca.marca, tblgrupoproduto.grupoproduto, tblsubgrupoproduto.subgrupoproduto
from 	
	(
	select tblprodutobarra.codproduto, count(tblnegocioprodutobarra.codnegocioprodutobarra) as vendas, sum(tblnegocioprodutobarra.valortotal) as total
	from tblnegocioprodutobarra
	inner join tblprodutobarra on (tblprodutobarra.codprodutobarra = tblnegocioprodutobarra.codprodutobarra)
	inner join (select n2.codnegocio from tblnegocio n2 where n2.codnegociostatus = 2 and n2.lancamento >= '2012-01-01') n3 on (n3.codnegocio = tblnegocioprodutobarra.codnegocio)
	group by codproduto
	) iq
inner join tblproduto on (tblproduto.codproduto = iq.codproduto)
inner join tblmarca on (tblmarca.codmarca = tblproduto.codmarca)
inner join tblsubgrupoproduto on (tblsubgrupoproduto.codsubgrupoproduto = tblproduto.codsubgrupoproduto)
inner join tblgrupoproduto on (tblgrupoproduto.codgrupoproduto = tblsubgrupoproduto.codgrupoproduto)
where tblproduto.inativo is null
--limit 2