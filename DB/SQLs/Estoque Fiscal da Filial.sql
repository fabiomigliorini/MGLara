/*

-- Totais
select p.codproduto, p.produto, el.estoquelocal, sum(es.saldoquantidade) as saldoquantidade, sum(es.saldovalor) as saldovalor
from tblproduto p
inner join tblprodutovariacao pv on (pv.codproduto = p.codproduto)
inner join tblestoquelocalprodutovariacao elpv on (elpv.codprodutovariacao = pv.codprodutovariacao)
inner join tblestoquelocal el on (el.codestoquelocal = elpv.codestoquelocal)
inner join tblfilial f on (f.codfilial = el.codfilial)
inner join tblestoquesaldo es on (es.codestoquelocalprodutovariacao = elpv.codestoquelocalprodutovariacao and es.fiscal = true)
where f.codempresa = 2
and coalesce(es.saldoquantidade, 0) != 0
group by p.codproduto, p.produto, el.estoquelocal
having sum(es.saldoquantidade) > 0
order by p.codproduto
--limit 100
*/

select p.codproduto, p.produto, el.estoquelocal, pv.variacao, es.saldoquantidade, es.saldovalor, es.codestoquesaldo, (select mes.codestoquemes from tblestoquemes mes where mes.codestoquesaldo = es.codestoquesaldo order by mes desc limit 1) as codestoquemes
from tblproduto p
inner join tblprodutovariacao pv on (pv.codproduto = p.codproduto)
inner join tblestoquelocalprodutovariacao elpv on (elpv.codprodutovariacao = pv.codprodutovariacao)
inner join tblestoquelocal el on (el.codestoquelocal = elpv.codestoquelocal)
inner join tblfilial f on (f.codfilial = el.codfilial)
inner join tblestoquesaldo es on (es.codestoquelocalprodutovariacao = elpv.codestoquelocalprodutovariacao and es.fiscal = true)
where f.codempresa = 1
and coalesce(es.saldoquantidade, 0) < 0
order by p.codproduto, saldoquantidade
--limit 100

select pv.variacao, es.codestoquesaldo, es.saldoquantidade, es.codestoquelocalprodutovariacao
from tblprodutovariacao pv
inner join tblestoquelocalprodutovariacao elpv on (elpv.codprodutovariacao = pv.codprodutovariacao and elpv.codestoquelocal = 301001)
inner join tblestoquesaldo es on (es.codestoquelocalprodutovariacao = elpv.codestoquelocalprodutovariacao and es.fiscal = true)
where pv.codproduto = 106
and es.codestoquelocalprodutovariacao = 110983
--and 


