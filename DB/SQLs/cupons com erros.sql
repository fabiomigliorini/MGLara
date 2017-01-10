
-- Numeracao Zerada ou Data vazia
select * from tblcupomfiscal 
where (   codcupomfiscal between 30149105 and 39999999 -- centro
       or codcupomfiscal between 10068278 and 19999999 -- atacado
       or codcupomfiscal between 20054835 and 29999999 -- ingas
       )
and (numero = 0 or datamovimento < '2000-01-01 00:00:00.0')
order by codecf, codcupomfiscal desc

-- Numero Duplicado
select numero, codecf, count(*)
from tblcupomfiscal
where numero <> 0
group by numero, codecf
having count(*) > 1

-- Erro de Dizima (Arredondamento)
select * 
from tblcupomfiscalprodutobarra
inner join tblcupomfiscal on (tblcupomfiscal.codcupomfiscal = tblcupomfiscalprodutobarra.codcupomfiscal)
where tblcupomfiscalprodutobarra.quantidade % 1 > 0
and ((tblcupomfiscalprodutobarra.quantidade * tblcupomfiscalprodutobarra.valorunitario) % 0.01) > 0
and tblcupomfiscal.datamovimento >= '2014-01-01 00:00:00.0'
and tblcupomfiscal.codecf = 30102

