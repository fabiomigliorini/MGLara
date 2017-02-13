select * from tblportador

insert into tblchequerepasse (codportador, data, observacoes, criacao, codusuariocriacao)
values (210, '2017-02-10', null, '2017-02-10 11:41', 1)

select * from tblchequerepasse order by codchequerepasse desc 

insert into tblchequerepassecheque (codcheque, codchequerepasse, criacao, codusuariocriacao)
select codcheque, 1991, '2017-02-10 11:41', 1
from tblcheque where cmc7 in (
'<00182331<0488500185>783000118865:',
'<23755817<0180000365>229400667100:',
'<34182188<0480000475>861791468129:', 
'<10408544<0180003285>000300018892:',
'<39907703<0180006185>407700085686:'
)

update tblcheque set indstatus = 2 where indstatus = 1 and codcheque in (select crc.codcheque from tblchequerepassecheque crc where codchequerepasse = 1991)


select crc.codchequerepasse, sum(c.valor)
from tblchequerepassecheque crc
inner join tblcheque c on (c.codcheque = crc.codcheque)
where crc.codchequerepasse >= 1979
group by crc.codchequerepasse
order by 1 desc