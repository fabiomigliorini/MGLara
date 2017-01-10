/*
select * from tblfilial
select * from tblestoquemovimentotipo
*/
/*
select codfilial, sum(entradaquantidade) as quant, sum(entradavalor) as valor
from tblestoquemovimento
group by codfilial
*/
select codfilial, sum(saldoquantidade) as quant, sum(saldovalor) as valor
from tblestoquesaldo_2013
group by codfilial

select codfilial, sum(saldoquantidade) as quant, sum(saldovalor) as valor
from tblestoquesaldo_2012_2013
group by codfilial

create table tblestoquesaldo_2014_2015 as select * from tblestoquesaldo

select codfilial, sum(saldoquantidade) as quant, sum(saldovalor) as valor
from tblestoquesaldo_2014_2015
group by codfilial

select codfilial, sum(saldoquantidade) as quant, sum(saldovalor) as valor
from tblestoquesaldo
group by codfilial


select * from tblestoquesaldo limit 10

delete from tblestoquesaldo

--update tblestoquemovimento set lancamento = '2011-12-31'