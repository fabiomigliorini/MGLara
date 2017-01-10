--select * from tbltitulo where coalesce(debito, 0) = 0 and coalesce(credito, 0) = 0 order by emissao desc

/*
select * from tbltipomovimentotitulo

update tblmovimentotitulo
set credito = 84700
where codtipomovimentotitulo = 100
and codtitulo = 59809

update tbltitulo
set numero = 'ARR-STA-MARIA-2017'
, fatura = null
, credito = 84700
where codtitulo = 59809

delete from tblmovimentotitulo 
where codtipomovimentotitulo = 200 
and codtitulo = 59809

UPDATE TBLTITULO SET NUMERO = 'ARR-TAMOIO-2018' WHERE CODTITULO = 59812

*/

--select * from tblnaturezaoperacao 

select * 
from tblnotafiscal nf
left join tblnotafiscalreferenciada nfr on (nfr.codnotafiscal = nf.codnotafiscal)
where codnaturezaoperacao = 2
and nfr.codnotafiscal is null
and nf.emissao >= '2016-01-01'
order by emissao desc