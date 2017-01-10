select * from tblboletoretorno limit 5

select codportador, dataretorno, linha, arquivo, count(*) 
from tblboletoretorno
group by codportador, dataretorno, linha, arquivo
having count(*) > 1

delete from tblboletoretorno 
where codportador = 210
and dataretorno = '2015-01-06'
and arquivo = 'CB060100.RET'

delete from tblmovimentotitulo where codboletoretorno in (
select codboletoretorno
from tblboletoretorno
where codportador = 210
and dataretorno = '2015-01-06'
and arquivo = 'CB060100.RET'
)