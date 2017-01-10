SELECT codfilial, modelo, 'NA FILA' as status, count(codnotafiscal), min(criacao), max(criacao)
from tblnotafiscal
where numero <> 0
and emitida = true
and nfeautorizacao is null
and nfeinutilizacao is null
and nfecancelamento is null
group by codfilial, modelo

union all

select codfilial, modelo, 'OK'  as status, count(codnotafiscal), min(nfedataautorizacao), max(nfedataautorizacao)
from tblnotafiscal
where emitida = true
and emissao >= current_date
and nfeautorizacao is not null
group by codfilial, modelo

union all

SELECT null as codfilial, null as modelo, 'TOTAL NA FILA' as status, count(codnotafiscal), min(criacao), max(criacao)
from tblnotafiscal
where numero <> 0
and emitida = true
and nfeautorizacao is null
and nfeinutilizacao is null
and nfecancelamento is null
--group by codfilial, modelo

union all 

select null as codfilial, null as modelo, 'TOTAL OK'  as status, count(codnotafiscal), min(nfedataautorizacao), max(nfedataautorizacao)
from tblnotafiscal
where emitida = true
and emissao >= current_date
and nfeautorizacao is not null

order by codfilial asc, modelo desc, status

/*

SELECT *
from tblnotafiscal
where numero <> 0
and emitida = true
and nfeautorizacao is null
and nfeinutilizacao is null
and nfecancelamento is null

*/