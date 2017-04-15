
-- SAIDA COM CUSTO DIFERENTE MEDIO
/*
select mov.codestoquemovimento, mov.codestoquemes, mov.saidaquantidade, mes.customedio, round((mov.saidaquantidade * mes.customedio), 2), mov.saidavalor, 'wget http://192.168.1.205/MGLara/estoque/calcula-custo-medio/' || cast(mov.codestoquemes as varchar)
from tblestoquemovimento mov
inner join tblestoquemovimentotipo tipo on (tipo.codestoquemovimentotipo = mov.codestoquemovimentotipo)
inner join tblestoquemes mes on (mes.codestoquemes = mov.codestoquemes)
where tipo.preco = 2 --preco medio
and abs(round((coalesce(mov.saidaquantidade, 0) * coalesce(mes.customedio, 0)), 2) - coalesce(mov.saidavalor, 0)) > 0.1
and mov.saidaquantidade > 0
limit 50
*/

-- SAIDA COM CUSTO DIFERENTE MEDIO
select distinct 
	mov.codestoquemovimento, mov.codestoquemes, mov.entradavalor, 
	abs(round(orig.saidavalor / orig.saidaquantidade * mov.entradaquantidade, 2)) as calc, 
	abs(mov.entradavalor - round(orig.saidavalor / orig.saidaquantidade * mov.entradaquantidade, 2)) as dif, 
	round((abs(mov.entradavalor - round(orig.saidavalor / orig.saidaquantidade * mov.entradaquantidade, 2)) / mov.entradavalor) * 100, 1) as perc, 
	'wget http://192.168.1.205/MGLara/estoque/calcula-custo-medio/' || cast(mov.codestoquemes as varchar)
from tblestoquemovimento mov
inner join tblestoquemovimentotipo tipo on (tipo.codestoquemovimentotipo = mov.codestoquemovimentotipo)
inner join tblestoquemovimento orig on (orig.codestoquemovimento = mov.codestoquemovimentoorigem)
where tipo.preco = 3 --preco origem
and abs(coalesce(mov.entradavalor, 0) - round(coalesce(orig.saidavalor, 0) / coalesce(orig.saidaquantidade, 0) * coalesce(mov.entradaquantidade, 0), 2)) > .01
and orig.saidaquantidade is not null
order by 6 desc
--limit 50

-- SALDO INICIAL DIFERENTE SALDO FINAL MES ANTERIOR
select 
	/*
	atu.codestoquemes
	, ant.codestoquemes as codestoquemes_ant
	, atu.codestoquesaldo
	, ant.saldoquantidade
	, ant.saldovalor
	, atu.inicialquantidade
	, atu.inicialvalor
	*/
	'wget http://192.168.1.205/MGLara/estoque/calcula-custo-medio/' || cast(atu.codestoquemes as varchar)
from tblestoquemes atu
left join tblestoquemes ant on (ant.codestoquemes = (select atu_ant.codestoquemes from tblestoquemes atu_ant where atu_ant.codestoquesaldo = atu.codestoquesaldo and atu_ant.mes < atu.mes order by mes desc limit 1))
where (coalesce(ant.saldoquantidade, 0) != coalesce(atu.inicialquantidade, 0) or coalesce(ant.saldovalor, 0) != coalesce(atu.inicialvalor, 0))
--and atu.codestoquemes = 267685
order by atu.codestoquesaldo

-- custo medio zerado
-- 90482
-- 84802
select  count(*)
	--'wget http://192.168.1.205/MGLara/estoque/calcula-custo-medio/' || cast(mes.codestoquemes as varchar) 
from tblestoquemes mes where customedio = 0


