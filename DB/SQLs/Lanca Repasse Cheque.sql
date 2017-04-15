select * from tblportador

insert into tblchequerepasse (codportador, data, observacoes, criacao, codusuariocriacao)
values (210, '2017-04-12', null, '2017-04-12 11:41', 1)

select * from tblchequerepasse order by codchequerepasse desc 

insert into tblchequerepassecheque (codcheque, codchequerepasse, criacao, codusuariocriacao)
select codcheque, 2019, '2017-04-12 11:41', 1
from tblcheque where cmc7 in (
'<23731260<0180000255>811500173882:',
'<23713051<0180000655>041000023725:'
)

update tblcheque set indstatus = 2 where indstatus = 1 and codcheque in (select crc.codcheque from tblchequerepassecheque crc where codchequerepasse = 2019)


select crc.codchequerepasse, sum(c.valor), count(crc.codchequerepassecheque)
from tblchequerepassecheque crc
inner join tblcheque c on (c.codcheque = crc.codcheque)
where crc.codchequerepasse >= 1979
group by crc.codchequerepasse
order by 1 desc


update tblchequerepasse set data = '2017-04-10', criacao = '2017-04-10 11:41' where codchequerepasse = 2017
update tblchequerepassecheque set criacao = '2017-04-10 11:41' where codchequerepasse = 2017


select * from tblchequerepassecheque where codchequerepasse = 2019

delete from tblchequerepassecheque where codchequerepassecheque in (15693, 15692)
