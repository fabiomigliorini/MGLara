select extract(year from datamovimento), extract(month from datamovimento), count(*)  
from tblcupomfiscal 
group by extract(year from datamovimento), extract(month from datamovimento)
order by 1 desc, 2 desc


select extract(year from emissao), extract(month from emissao), count(*)  
from tblnotafiscal
where emitida = true
group by extract(year from emissao), extract(month from emissao)
order by 1 desc, 2 desc


