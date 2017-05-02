select 
	el.estoquelocal
	, p.codproduto
	, p.produto
	, p.codmarca
	, p.preco
	, m.marca
	, mes.saldoquantidade
	, mes.customedio
	, mes.saldovalor
from 	(select 
		iq_sld.*, 
		(select iq_mes.codestoquemes
			from tblestoquemes iq_mes
			where iq_mes.mes <= '2016-12-31'
			and iq_mes.codestoquesaldo = iq_sld.codestoquesaldo
			order by iq_mes desc
			limit 1
		) as codestoquemes
	from tblestoquesaldo iq_sld
	where iq_sld.fiscal = true
	) sld
inner join tblestoquemes mes on (mes.codestoquemes = sld.codestoquemes)
inner join tblestoquelocalprodutovariacao elpv on (elpv.codestoquelocalprodutovariacao = sld.codestoquelocalprodutovariacao)
inner join tblestoquelocal el on (el.codestoquelocal = elpv.codestoquelocal)
inner join tblprodutovariacao pv on (pv.codprodutovariacao = elpv.codprodutovariacao)
inner join tblproduto p on (p.codproduto = pv.codproduto)
left join tblmarca m on (m.codmarca = p.codmarca)
where mes.saldoquantidade > 0
and el.codfilial in (101, 102, 103, 104)