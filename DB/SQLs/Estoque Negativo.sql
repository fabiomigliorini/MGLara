/*
select gp.grupoproduto, sgp.subgrupoproduto, m.marca, sum(saldoquantidade) as saldoquantidade, sum(saldovalor) as saldovalor
from tblestoquesaldo es
left join tblproduto p on (p.codproduto = es.codproduto)
left join tblmarca m on (m.codmarca = p.codmarca)
left join tblsubgrupoproduto sgp on (sgp.codsubgrupoproduto = p.codsubgrupoproduto)
left join tblgrupoproduto gp on (gp.codgrupoproduto = sgp.codgrupoproduto)
where saldovalor < 0
or saldoquantidade < 0
group by gp.grupoproduto, sgp.subgrupoproduto, m.marca
order by 1, 2, 3
*/

select 
	--distinct p.codproduto
	codestoquesaldo, gp.grupoproduto, sgp.codsubgrupoproduto, sgp.subgrupoproduto, m.codmarca, m.marca, p.codproduto, p.produto, el.codestoquelocal, el.estoquelocal, saldoquantidade, saldovalor
from tblestoquesaldo es
left join tblestoquelocal el on (el.codestoquelocal = es.codestoquelocal)
left join tblproduto p on (p.codproduto = es.codproduto)
left join tblmarca m on (m.codmarca = p.codmarca)
left join tblsubgrupoproduto sgp on (sgp.codsubgrupoproduto = p.codsubgrupoproduto)
left join tblgrupoproduto gp on (gp.codgrupoproduto = sgp.codgrupoproduto)
where saldovalor < 0
or saldoquantidade < 0
order by gp.grupoproduto, sgp.subgrupoproduto, m.marca, p.produto
--limit 100