delete from tblestoquemovimento;
delete from tblestoquemes;
delete from tblestoquesaldo;

ALTER SEQUENCE tblestoquesaldo_codestoquesaldo_seq RESTART WITH 1;

INSERT INTO tblestoquesaldo (codproduto, codestoquelocal, fiscal, saldoquantidade, saldovalor, customedio)
select 
	  si.codproduto
	, el.codestoquelocal
	, true as fiscal
	, si.saldoquantidade
	, si.saldovalor
	, si.saldovalor / si.saldoquantidade as customedio
from tblestoquesaldo_2014_2015 si
left join tblestoquelocal el on (el.codfilial = si.codfilial);

ALTER SEQUENCE tblestoquemes_codestoquemes_seq RESTART WITH 1;

insert into tblestoquemes (codestoquesaldo, mes, inicialquantidade, inicialvalor, entradaquantidade, entradavalor, saidaquantidade, saidavalor, saldoquantidade, saldovalor, customedio)
select 
	  es.codestoquesaldo
	, '2014-12-01' as mes
	, null as inicialquantidade
	, null as inicialvalor
	, si.saldoquantidade as entradaquantidade
	, si.saldovalor as entradavalor
	, null as saidaquantidade
	, null as saidavalor
	, si.saldoquantidade
	, si.saldovalor
	, si.saldovalor / si.saldoquantidade as customedio
from tblestoquesaldo_2014_2015 si
left join tblestoquelocal el on (el.codfilial = si.codfilial)
left join tblestoquesaldo es on (es.codproduto = si.codproduto AND es.codestoquelocal = el.codestoquelocal AND es.fiscal = true);

ALTER SEQUENCE tblestoquemovimento_codestoquemovimento_seq RESTART WITH 1;

INSERT INTO tblestoquemovimento (codestoquemes, codestoquemovimentotipo, manual, "data", entradaquantidade, entradavalor, saidaquantidade, saidavalor)
select 
	  em.codestoquemes
	, 1001 as codestoquemovimentotipo -- Saldo Inicial
	, true as manual
	, '2014-12-31 23:59:59' as data
	, si.saldoquantidade as entradaquantidade
	, si.saldovalor as entradavalor
	, null as saidaquantidade
	, null as saidavalor
from tblestoquesaldo_2014_2015 si
left join tblestoquelocal el on (el.codfilial = si.codfilial)
left join tblestoquesaldo es on (es.codproduto = si.codproduto AND es.codestoquelocal = el.codestoquelocal AND es.fiscal = true)
left join tblestoquemes em on (em.codestoquesaldo = es.codestoquesaldo AND em.mes = '2014-12-01')
order by codestoquemes;
/*

select codestoquelocal, sum(saldoquantidade), sum(saldovalor) 
from tblestoquesaldo
group by codestoquelocal

select 
	  sum(inicialquantidade)
	, sum(inicialvalor)
	, sum(entradaquantidade)
	, sum(entradavalor)
	, sum(saidaquantidade)
	, sum(saidavalor)
	, sum(saldoquantidade)
	, sum(saldovalor)
from tblestoquemes



select sum(entradaquantidade), sum(entradavalor), sum(saidaquantidade), sum(saidavalor)
from tblestoquemovimento


--select * from tblestoquemovimento limit 500

select count(*) from tblestoquesaldo_2014_2015

*/

select * from tblproduto where codsubgrupoproduto is null

select * from tblsubgrupoproduto order by codsubgrupoproduto  desc

-- CLassificar
update tblproduto set codsubgrupoproduto = 18003 where codsubgrupoproduto is null;

--Diversos
update tblproduto set codmarca = 10000299 where codmarca is null;