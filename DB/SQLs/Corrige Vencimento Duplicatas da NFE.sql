update tblnotafiscalduplicatas
set vencimento = (select max(tblnotafiscal.emissao) from tblnotafiscal where tblnotafiscal.codnotafiscal = tblnotafiscalduplicatas.codnotafiscal)
where tblnotafiscalduplicatas.codnotafiscalduplicatas in (
	select tblnotafiscalduplicatas.codnotafiscalduplicatas--, emissao, vencimento, numero, codfilial
	from tblnotafiscal
	inner join tblnotafiscalduplicatas on (tblnotafiscalduplicatas.codnotafiscal = tblnotafiscal.codnotafiscal)
	where tblnotafiscal.emissao > tblnotafiscalduplicatas.vencimento
	and tblnotafiscal.emissao >= '2012-11-01'
	)