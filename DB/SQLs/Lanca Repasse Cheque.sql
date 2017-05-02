select * from tblportador

insert into tblchequerepasse (codportador, data, observacoes, criacao, codusuariocriacao)
values (210, '2017-03-02', null, '2017-03-02 11:41', 1)

select * from tblchequerepasse order by codchequerepasse desc 

insert into tblchequerepassecheque (codcheque, codchequerepasse, criacao, codusuariocriacao)
select codcheque, 2021, '2017-03-02 11:41', 1
from tblcheque where cmc7 in (
'<00142706<0188510255>381003785003:'
)

update tblcheque set indstatus = 2 where indstatus = 1 and codcheque in (select crc.codcheque from tblchequerepassecheque crc where codchequerepasse = 2021)


select crc.codchequerepasse, sum(c.valor), count(crc.codchequerepassecheque)
from tblchequerepassecheque crc
inner join tblcheque c on (c.codcheque = crc.codcheque)
where crc.codchequerepasse >= 1900
group by crc.codchequerepasse
order by 1 desc


update tblchequerepasse set data = '2017-04-10', criacao = '2017-04-10 11:41' where codchequerepasse = 2020
update tblchequerepassecheque set criacao = '2017-04-10 11:41' where codchequerepasse = 2020


select * from tblchequerepassecheque where codchequerepasse = 2019

delete from tblchequerepassecheque where codchequerepassecheque in (15693, 15692)
