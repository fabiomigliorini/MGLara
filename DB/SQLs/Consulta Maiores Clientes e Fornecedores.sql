-- COMPRAS
/*
select p.codpessoa, p.fantasia, p.pessoa, p.telefone1, iq.valor
from 
	(
	select nf.codpessoa, sum(nf.valortotal) as valor
	from tblnotafiscal nf
	where nf.codnaturezaoperacao = 00000004 -- compra
	and nf.SAIDA between '2014-10-01' and '2015-09-30'
	--and nf.codfilial = 101 --atacado
	group by nf.codpessoa 
	order by 2 desc
	--limit 7
	) iq
inner join tblpessoa p on (p.codpessoa = iq.codpessoa)
*/

select p.codpessoa, p.fantasia, p.pessoa, p.telefone1, iq.valor
from 
	(
	select nf.codpessoa, sum(nf.valortotal) as valor
	from tblnotafiscal nf
	where nf.codnaturezaoperacao = 00000001 -- venda
	and nf.emissao between '2014-10-01' and '2015-09-30'
	--and nf.codfilial = 101 --atacado
	group by nf.codpessoa 
	order by 2 desc
	--limit 20
	) iq
inner join tblpessoa p on (p.codpessoa = iq.codpessoa)
--LIMIT 50

/*
select EXTRACT(YEAR FROM EMISSAO) AS ANO, EXTRACT(MONTH FROM EMISSAO) AS ANO, sum(nf.valortotal) as valor
from tblnotafiscal nf
where nf.codnaturezaoperacao = 00000001 -- venda
and nf.emissao between '2014-10-01' and '2015-09-30'
and nf.codfilial = 201 --INGAS
group by EXTRACT(YEAR FROM EMISSAO) , EXTRACT(MONTH FROM EMISSAO) 
order by 1, 2 ASC
limit 20
*/