select n.codfilial, extract (month from n.lancamento), extract (hour from n.lancamento), sum(n.valortotal) as totla
from tblnegocio n 
where codnegociostatus = 2 
and n.lancamento >= '2014-08-22 00:00:00.0'
--and extract (hour from n.lancamento) <= 6
group by n.codfilial, extract (month from n.lancamento), extract (hour from n.lancamento)
--limit 100