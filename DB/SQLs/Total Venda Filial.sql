/*
select sum(valortotal)
from vwnotafiscal 
where codfilial = 301 
and emissao between '2012-02-01' and '2012-02-29' 
and emitida = true
and nfecancelamento is null 
and nfeinutilizacao is null

union all

select sum(valortotalfinal)
from vwcupomfiscal
where codecf in (30101, 30102, 30103)
and datamovimento between '2012-02-01' and '2012-02-29' 
and cancelado = false


*/