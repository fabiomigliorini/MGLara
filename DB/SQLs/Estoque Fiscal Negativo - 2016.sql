select count(*)
from tblestoquesaldo es 
inner join tblestoquemes em on (em.codestoquesaldo = es.codestoquesaldo)
where es.fiscal = true
and em.saldoquantidade < 0
and em.mes <= '2016-12-31 23:59:59.9'