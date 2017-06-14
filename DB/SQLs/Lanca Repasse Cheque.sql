select * from tblportador

insert into tblchequerepasse (codportador, data, observacoes, criacao, codusuariocriacao)
values (210, '2017-06-12', null, '2017-06-12 11:41', 1)

select * from tblchequerepasse order by codchequerepasse desc 

insert into tblchequerepassecheque (codcheque, codchequerepasse, criacao, codusuariocriacao)
select codcheque, 2051, '2017-06-12 11:41', 1
from tblcheque where cmc7 in (
'<10408542<0480002905>000300018892:',
'<34113644<0480000665>721220814798:',
'<23755811<0180000405>247300667103:',
'<23702349<0180000265>311601032094:',
'<10408545<0480002975>000300018892:',
'<10408548<0480002865>000300018892:',
'<34113641<0480000025>721171251933:',
'<10408549<0480002935>000300018892:',
'<03341682<0180020035>279130076759:',
'<00142709<0188515505>300010510246:'
)

update tblcheque set indstatus = 2 where indstatus = 1 and codcheque in (select crc.codcheque from tblchequerepassecheque crc where codchequerepasse = 2051)


select crc.codchequerepasse, sum(c.valor), count(crc.codchequerepassecheque)
from tblchequerepassecheque crc
inner join tblcheque c on (c.codcheque = crc.codcheque)
where crc.codchequerepasse >= 1900
group by crc.codchequerepasse
order by 1 desc


update tblchequerepasse set data = '2017-06-12', criacao = '2017-06-12 11:41' where codchequerepasse = 2042
update tblchequerepassecheque set criacao = '2017-06-12 11:41' where codchequerepasse = 2042


select * from tblchequerepassecheque where codchequerepasse = 2047


update tblchequerepassecheque set codchequerepasse = 2049 where codchequerepassecheque = 15793
delete from tblchequerepassecheque where codchequerepassecheque in (15693, 15692)
