/*
update tblestoquemovimento
set entradavalor = null
, saidavalor = orig.entradavalor
from tblestoquemovimento orig
where tblestoquemovimento.codestoquemovimentoorigem = orig.codestoquemovimento
and tblestoquemovimento.codestoquemes = 348197

*/

update tblnotafiscalprodutobarra
set codnotafiscalprodutobarraorigem = ( 
	select min(nfpbo.codnotafiscalprodutobarra)
	from tblnotafiscal nfo 
	inner join tblnotafiscalprodutobarra nfpbo on (
		nfpbo.codnotafiscal = nfo.codnotafiscal and 
		nfpbo.codprodutobarra = tblnotafiscalprodutobarra.codprodutobarra and 
		nfpbo.valortotal = tblnotafiscalprodutobarra.valortotal and
		nfpbo.quantidade = tblnotafiscalprodutobarra.quantidade)
	where nfo.codnotafiscal != nf.codnotafiscal
	and nfo.emitida = true
	and nfo.nfechave = nf.nfechave
)
from tblnotafiscal nf 
where nf.codnotafiscal = tblnotafiscalprodutobarra.codnotafiscal
and nf.codnaturezaoperacao = 16
and nf.emitida = false
and tblnotafiscalprodutobarra.codnotafiscalprodutobarraorigem is null
--and nf.codnotafiscal = 229395

/*
select nf.codnotafiscal, nfpb.codnotafiscalprodutobarra, nf.nfechave, nfpb.codnotafiscalprodutobarraorigem, nf.codnaturezaoperacao, nfpb.codprodutobarra, nfpb.valortotal
, ( 
	select cast(nfpbo.codnotafiscal as varchar) || ' - ' || cast(nfpbo.codnotafiscalprodutobarra as varchar)
	from tblnotafiscal nfo 
	inner join tblnotafiscalprodutobarra nfpbo on (nfpbo.codnotafiscal = nfo.codnotafiscal and nfpbo.codprodutobarra = nfpb.codprodutobarra and nfpbo.valortotal = nfpb.valortotal)
	where nfo.codnotafiscal != nf.codnotafiscal
	and nfo.emitida = true
	and nfo.nfechave = nf.nfechave
) iq
*/
select count(*) 
from tblnotafiscalprodutobarra nfpb
inner join tblnotafiscal nf on (nf.codnotafiscal = nfpb.codnotafiscal)
where nf.codnaturezaoperacao = 16
and nf.emitida = false
and nfpb.codnotafiscalprodutobarraorigem is null
and nf.codnotafiscal = 229395
limit 10