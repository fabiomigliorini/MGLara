﻿select count(codtitulo), sum(debito), extract(year from emissao), extract(month from emissao)
from tbltitulo
where boleto = true 
and estornado is null
group by extract(year from emissao), extract(month from emissao)
order by 3 desc, 4 desc