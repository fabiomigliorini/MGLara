--NUMERACAO SALTEADA
select n.codfilial, n.modelo, n.serie, n.numero, na.numero, n.saida
from tblnotafiscal n
left outer join tblnotafiscal na on (na.codfilial = n.codfilial AND na.modelo = n.modelo AND na.serie = n.serie AND na.numero = n.numero-1 AND na.emitida = true)
where n.emitida = true
and n.saida between '2014-07-01' and '2014-08-01'
and na.codnotafiscal is null
order by n.codfilial, n.modelo, n.serie, n.numero

--NUMERACAO DUPLICADA
select n.codfilial, n.modelo, n.serie, n.numero, count(*)
from tblnotafiscal n
where n.emitida = true
group by n.codfilial, n.modelo, n.serie, n.numero
having count(*) > 1

