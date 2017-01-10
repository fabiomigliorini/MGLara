select 
/*
	codestoquelocal, count(*)
*/
	(select mes.codestoquemes from tblestoquemes mes where mes.codestoquesaldo = es.codestoquesaldo order by mes desc limit 1) as codestoquemes
	, p.codproduto
	, p.produto
	, pv.variacao
	--, m.marca
	--, elpv.codestoquelocal
	, es.saldoquantidade
	, es.saldovalor
from tblestoquesaldo es 
inner join tblestoquelocalprodutovariacao elpv on (elpv.codestoquelocalprodutovariacao = es.codestoquelocalprodutovariacao)
inner join tblprodutovariacao pv on (pv.codprodutovariacao = elpv.codprodutovariacao)
inner join tblproduto p on (p.codproduto = pv.codproduto)
left join tblmarca m on (m.codmarca = coalesce(pv.codmarca, p.codmarca))
where es.fiscal = true
and elpv.codestoquelocal = 201001
and es.saldoquantidade != 0
order by produto, variacao
--limit 100
--group by codestoquelocal