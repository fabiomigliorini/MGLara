select codecf, numero, count(*)
from tblcupomfiscal
where numero <> 0 
group by codecf, numero
having count(*) > 1
limit 10