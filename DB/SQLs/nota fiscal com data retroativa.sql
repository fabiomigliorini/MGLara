update tblnotafiscal
set numero = nextval('tblnotafiscal_numero_' || cast(tblnotafiscal.codfilial as varchar) || '_' || cast(tblnotafiscal.serie as varchar) || '_' || cast(tblnotafiscal.modelo as varchar) || '_seq')
, emissao = '2017-04-30 22:00'
, saida  = '2017-04-30 22:00'
where tblnotafiscal.codnotafiscal between 463227 and 463227
and tblnotafiscal.numero = 0


