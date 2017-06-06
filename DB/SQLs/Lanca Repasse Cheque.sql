select * from tblportador

insert into tblchequerepasse (codportador, data, observacoes, criacao, codusuariocriacao)
values (210, '2017-06-06', null, '2017-06-06 11:41', 1)

select * from tblchequerepasse order by codchequerepasse desc 

insert into tblchequerepassecheque (codcheque, codchequerepasse, criacao, codusuariocriacao)
select codcheque, 2047, '2017-06-06 11:41', 1
from tblcheque where cmc7 in (
'<74880181<0180025175>800004034401:',
'<00142700<0188511795>371011750685:'
)

update tblcheque set indstatus = 2 where indstatus = 1 and codcheque in (select crc.codcheque from tblchequerepassecheque crc where codchequerepasse = 2047)


select crc.codchequerepasse, sum(c.valor), count(crc.codchequerepassecheque)
from tblchequerepassecheque crc
inner join tblcheque c on (c.codcheque = crc.codcheque)
where crc.codchequerepasse >= 1900
group by crc.codchequerepasse
order by 1 desc


update tblchequerepasse set data = '2017-06-06', criacao = '2017-06-06 11:41' where codchequerepasse = 2042
update tblchequerepassecheque set criacao = '2017-06-06 11:41' where codchequerepasse = 2042


select * from tblchequerepassecheque where codchequerepasse = 2019

delete from tblchequerepassecheque where codchequerepassecheque in (15693, 15692)
