-- Marcas
select p.codmarca, m.marca, sum(npb.valortotal) as valortotal
from tblnegocioprodutobarra npb
inner join tblnegocio n on (n.codnegocio = npb.codnegocio)
inner join tblnaturezaoperacao no on (no.codnaturezaoperacao = n.codnaturezaoperacao)
inner join tblprodutobarra pb on (pb.codprodutobarra = npb.codprodutobarra)
inner join tblproduto p on (p.codproduto = pb.codproduto)
left join tblmarca m on (m.codmarca = p.codmarca)
where n.codnegociostatus = 2 -- Fechado
and n.lancamento >= '2016-01-01'
and no.venda = true
group by p.codmarca, m.marca
order by valortotal desc
--limit 100

-- Produtos
select p.codproduto, p.produto, sum(npb.valortotal) as valortotal
from tblnegocioprodutobarra npb
inner join tblnegocio n on (n.codnegocio = npb.codnegocio)
inner join tblnaturezaoperacao no on (no.codnaturezaoperacao = n.codnaturezaoperacao)
inner join tblprodutobarra pb on (pb.codprodutobarra = npb.codprodutobarra)
inner join tblproduto p on (p.codproduto = pb.codproduto)
left join tblmarca m on (m.codmarca = p.codmarca)
where n.codnegociostatus = 2 -- Fechado
and n.lancamento >= '2016-01-01'
and no.venda = true
group by p.codproduto, p.produto
order by valortotal desc
--limit 100