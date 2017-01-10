select 
  extract(year from emissao)
, extract(month from emissao)
, sum(debito)
, max(debito)
, min(debito)
, sum(saldo)
, count(codtitulo)
from tbltitulo 
where boleto 
--and numero like 'A%' 
and emissao >= '2013-01-01'
and estornado is null
group by 
  extract(year from emissao)
, extract(month from emissao)
order by 1 desc, 2 desc
