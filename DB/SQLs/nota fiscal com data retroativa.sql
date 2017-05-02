update tblnotafiscal
set numero = nextval('tblnotafiscal_numero_' || cast(tblnotafiscal.codfilial as varchar) || '_' || cast(tblnotafiscal.serie as varchar) || '_' || cast(tblnotafiscal.modelo as varchar) || '_seq')
, emissao = '2017-04-30 22:00'
, saida  = '2017-04-30 22:00'
where tblnotafiscal.codnotafiscal between 00529645 and 00529647
and tblnotafiscal.numero = 0


update tblnotafiscalprodutobarra set valortotal = quantidade * valorunitario where codnotafiscal between 00529645 and 00529647