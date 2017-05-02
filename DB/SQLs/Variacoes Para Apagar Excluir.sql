/*
update tblnegocioprodutobarra set codprodutobarra = 10016798 where codprodutobarra = 10018949

update tblnotafiscalprodutobarra set codprodutobarra = 10016798 where codprodutobarra = 10018949

update tblnfeterceiroitem set codprodutobarra = 10016798 where codprodutobarra = 10018949

delete from tblprodutobarra where codprodutobarra = 10018949

select em.mes, em.* 
from tblestoquesaldo sld 
inner join tblestoquelocalprodutovariacao elpv on (elpv.codestoquelocalprodutovariacao = sld.codestoquelocalprodutovariacao)
inner join tblestoquemes em on (em.codestoquesaldo =  sld.codestoquesaldo)
where elpv.codestoquelocal = 301001
and elpv.codprodutovariacao = 66156
and sld.fiscal = true

update tblestoquemovimento set codestoquemes = 23829 where codestoquemes = 23991
*/

select * from tblprodutovariacao where variacao ilike '%apagar%'