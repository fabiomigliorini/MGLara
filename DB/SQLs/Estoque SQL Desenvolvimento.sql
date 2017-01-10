select count(*)
from tblestoquemovimento mov
left join tblestoquemes mes on (mes.codestoquemes = mov.codestoquemes)
left join tblestoquesaldo sld on (sld.codestoquesaldo = mes.codestoquesaldo)
where sld.fiscal = false
and mov.codestoquemovimentoorigem is null

select * 
from tblestoquesaldo
where fiscal = false

select mov.codestoquemes, count(*)
from tblestoquemovimento mov
left join tblestoquemes mes on (mes.codestoquemes = mov.codestoquemes)
left join tblestoquesaldo sld on (sld.codestoquesaldo = mes.codestoquesaldo)
where sld.fiscal = false
group by mov.codestoquemes
order by 2 desc

delete from tbljobs
select count(*) from tbljobs
comecou 08:17

delete from tblestoquemovimento where codestoquemes in (select mes.codestoquemes from tblestoquemes mes inner join tblestoquesaldo sld on (sld.codestoquesaldo = mes.codestoquesaldo) where sld.fiscal = false)
delete from tblestoquemes where codestoquesaldo in (select sld.codestoquesaldo from tblestoquesaldo sld where sld.fiscal = false)
delete from tblestoquesaldo where fiscal = false

select count(*) 
from tblnegocioprodutobarra npb 
inner join tblnegocio n on (n.codnegocio = npb.codnegocio)
where n.lancamento between '2016-04-01 00:00:00' and '2016-04-30 23:59:59'
and n.codnegociostatus = 2

select count(*) 
from tblnegocio n 
where n.lancamento between '2016-04-01 00:00:00' and '2016-04-30 23:59:59'


select p.codproduto, em.codestoquemes, npb.codnegocio
from tblproduto p
inner join tblestoquelocalproduto elp on (elp.codproduto = p.codproduto)
inner join tblestoquesaldo es on (es.codestoquelocalproduto = elp.codestoquelocalproduto and es.fiscal = false)
inner join tblestoquemes em on (em.codestoquesaldo = es.codestoquesaldo)
inner join tblestoquemovimento mov on (mov.codestoquemes = em.codestoquemes)
inner join tblnegocioprodutobarra npb on (npb.codnegocioprodutobarra = mov.codnegocioprodutobarra)
where codtipoproduto <> 0
order by npb.codnegocio asc
