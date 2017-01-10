select * from 
(
	select tblprodutobarra.codproduto, tblproduto.produto, tblmarca.marca, count(tblnegocioprodutobarra.codnegocioprodutobarra) Vendas, sum(tblnegocioprodutobarra.valortotal) Total
	from tblnegocioprodutobarra
	inner join tblnegocio on (tblnegocio.codnegocio = tblnegocioprodutobarra.codnegocio)
	inner join tblprodutobarra on (tblprodutobarra.codprodutobarra = tblnegocioprodutobarra.codprodutobarra)
	inner join tblproduto on (tblproduto.codproduto = tblprodutobarra.codproduto)
	inner join tblmarca on (tblmarca.codmarca = coalesce(tblprodutobarra.codmarca, tblproduto.codmarca))
	where tblnegocio.codnegociostatus = 2 -- fechado
	--and tblnegocio.lancamento between '2012-01-01' and '2012-08-31'
	group by tblprodutobarra.codproduto, tblproduto.produto, tblmarca.marca
	order by 5 desc
	limit 150
) x
union
select * from 
(
	select tblprodutobarra.codproduto, tblproduto.produto, tblmarca.marca, count(tblnegocioprodutobarra.codnegocioprodutobarra) Vendas, sum(tblnegocioprodutobarra.valortotal) Total
	from tblnegocioprodutobarra
	inner join tblnegocio on (tblnegocio.codnegocio = tblnegocioprodutobarra.codnegocio)
	inner join tblprodutobarra on (tblprodutobarra.codprodutobarra = tblnegocioprodutobarra.codprodutobarra)
	inner join tblproduto on (tblproduto.codproduto = tblprodutobarra.codproduto)
	inner join tblmarca on (tblmarca.codmarca = coalesce(tblprodutobarra.codmarca, tblproduto.codmarca))
	where tblnegocio.codnegociostatus = 2 -- fechado
	--and tblnegocio.lancamento between '2012-01-01' and '2012-08-31'
	group by tblprodutobarra.codproduto, tblproduto.produto, tblmarca.marca
	order by 4 desc
	limit 150
) y
order by produto

