update tblproduto 
set inativo = '2016-11-25 19:00:00'
, alteracao = '2016-11-25 19:00:00'
, codusuarioalteracao = 1 -- fabio
where tblproduto.inativo is null
and codtipoproduto = 0 --Mercadoria
and (tblproduto.criacao <= '2016-10-01 00:00:00' or tblproduto.criacao is null)
and tblproduto.codproduto not in (
	select distinct pb.codproduto
	from tblnotafiscal nf 
	inner join tblnotafiscalprodutobarra nfpb on (nf.codnotafiscal = nfpb.codnotafiscal)
	inner join tblprodutobarra pb on (nfpb.codprodutobarra = pb.codprodutobarra)
	where nf.saida >= '2016-01-01 00:00:0.0'

	union 

	select distinct pb.codproduto
	from tblnegocio n 
	inner join tblnegocioprodutobarra npb on (n.codnegocio = npb.codnegocio)
	inner join tblprodutobarra pb on (npb.codprodutobarra = pb.codprodutobarra)
	where n.lancamento >= '2016-01-01 00:00:0.0'

	union 

	select distinct pv.codproduto
	from tblestoquesaldo es
	inner join tblestoquelocalprodutovariacao elpv on elpv.codestoquelocalprodutovariacao = es.codestoquelocalprodutovariacao
	inner join tblprodutovariacao pv on pv.codprodutovariacao = elpv.codprodutovariacao
	where es.saldoquantidade <> 0

	)

--update tblproduto set inativo = null where inativo = '2016-11-25 19:00:00'
