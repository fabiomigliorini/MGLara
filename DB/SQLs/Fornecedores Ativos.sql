/*
select 
      tblpessoa.codpessoa
    , fantasia, telefone1, telefone2, telefone3, email, emailnfe, emailcobranca 
from tblpessoa
inner join tblnotafiscal on (tblnotafiscal.codpessoa = tblpessoa.codpessoa and tblnotafiscal.emitida = false)
limit 5
*/

select 
	iq.codpessoa
	, tblpessoa.fantasia
	, coalesce(tblpessoa.telefone1, '-') || ' / ' || coalesce(tblpessoa.telefone2, '-') || ' / ' || coalesce(tblpessoa.telefone3, '-') as fone
	, coalesce(tblpessoa.email, '-') || ' / ' || coalesce(tblpessoa.emailnfe, '-') || ' / ' || coalesce(tblpessoa.emailcobranca, '-') as email
	, iq.emissao_atacado
	, iq.emissao_ingas
	, iq.emissao_inativa
	, iq.emissao_centro
from (
	select 
		tblnotafiscal.codpessoa
		, max(case when tblnotafiscal.codfilial = 101 then emissao else null end) as emissao_atacado
		, max(case when tblnotafiscal.codfilial = 201 then emissao else null end) as emissao_ingas
		, max(case when tblnotafiscal.codfilial = 202 then emissao else null end) as emissao_inativa
		, max(case when tblnotafiscal.codfilial = 301 then emissao else null end) as emissao_centro
	from tblnotafiscal
	where tblnotafiscal.emitida = false
	group by tblnotafiscal.codpessoa
	) iq
left join tblpessoa on (tblpessoa.codpessoa = iq.codpessoa)
order by fantasia