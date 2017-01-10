select elpv.codestoquelocal, elpv.codprodutovariacao, es.fiscal, pv.codproduto, es.saldoquantidade
from tblestoquesaldo es
inner join tblestoquelocalprodutovariacao elpv on (elpv.codestoquelocalprodutovariacao = es.codestoquelocalprodutovariacao)
inner join tblprodutovariacao pv on (pv.codprodutovariacao = elpv.codprodutovariacao)
where es.saldoquantidade <> 0
and es.fiscal = false
and elpv.codestoquelocal = 104001

select max(mes) as mes, lpv.codestoquelocal, lpv.codprodutovariacao, sld.fiscal, var.codproduto, sld.saldoquantidade
from tblestoquemes mes
join tblestoquesaldo sld on (sld.codestoquesaldo = mes.codestoquesaldo)
join tblestoquelocalprodutovariacao lpv on (lpv.codestoquelocalprodutovariacao = sld.codestoquelocalprodutovariacao)
join tblprodutovariacao var on (var.codprodutovariacao = lpv.codprodutovariacao)
where mes.saldoquantidade > 0
and sld.fiscal = false
and lpv.codestoquelocal = 104001
and mes.mes <= '2016-07-29'
and sld.codestoquesaldo not in (select distinct iq.codestoquesaldo from tblestoquesaldoconferencia iq)
group by lpv.codestoquelocal, lpv.codprodutovariacao, sld.fiscal, var.codproduto, sld.saldoquantidade
order by var.codproduto
Limit 1000

            select es.codestoquesaldo, elpv.codestoquelocal, elpv.codprodutovariacao, es.fiscal, pv.codproduto, es.saldoquantidade
            from tblestoquesaldo es
            inner join tblestoquelocalprodutovariacao elpv on (elpv.codestoquelocalprodutovariacao = es.codestoquelocalprodutovariacao)
            inner join tblprodutovariacao pv on (pv.codprodutovariacao = elpv.codprodutovariacao)
            where es.saldoquantidade <> 0
            and es.fiscal = false
            and elpv.codestoquelocal = 104001
            order by pv.codproduto, pv.codprodutovariacao
            limit 10



select * from tblestoquemovimento limit 10