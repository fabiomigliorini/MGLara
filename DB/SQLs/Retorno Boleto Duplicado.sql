/*

--select * from tblboletoretorno limit 10
--

delete from tblmovimentotitulo where codboletoretorno in (
delete from tblboletoretorno where codboletoretorno in (
select codboletoretorno 
from tblboletoretorno 
where arquivo = 'CB160700.RET' and dataretorno = '2015-07-17' and codportador = 2222
order by linha
)
*/

select 
	codportador, dataretorno, arquivo, linha
	, count(*)
from	tblboletoretorno
group by
	codportador, dataretorno, arquivo, linha
having count(*) > 1
order by 
	codportador, dataretorno, arquivo, linha



