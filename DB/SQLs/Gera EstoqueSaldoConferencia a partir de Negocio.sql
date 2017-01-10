/*
INSERT INTO tblestoquesaldoconferencia(
            codestoquesaldo
            , quantidadesistema
            , quantidadeinformada
            , customediosistema
            , customedioinformado
            , data
            , observacoes
            , alteracao
            , codusuarioalteracao
            , criacao
            , codusuariocriacao)
select  
	es.codestoquesaldo
	, es.saldoquantidade as quantidadesistema
	, sum(npb.quantidade) as quantidadeinformada
	, es.customedio as customediosistema
	, coalesce(es.customedio, 0) as customedioinformado
	, '2016-04-01 00:00:00' as data
	, 'importado via negocio #' || min (npb.codnegocio)
	, min(npb.alteracao) as alteracao
	, min(npb.codusuarioalteracao) as codusuarioalteracao
	, min(npb.criacao) as criacao
	, min(npb.codusuariocriacao) as codusuariocriacao
from tblnegocioprodutobarra npb
left join tblnegocio n on (n.codnegocio = npb.codnegocio)
left join tblprodutobarra pb on (pb.codprodutobarra = npb.codprodutobarra)
left join tblestoquelocalprodutovariacao elpv on (elpv.codestoquelocal = n.codestoquelocal and elpv.codprodutovariacao = pb.codprodutovariacao)
left join tblestoquesaldo es on (es.codestoquelocalprodutovariacao = elpv.codestoquelocalprodutovariacao and es.fiscal = false)
where npb.codnegocio = 440000
group by 
	es.codestoquesaldo
	, es.saldoquantidade 
	, es.customedio 	

update tblestoquesaldo
set ultimaconferencia = (select max(esc.alteracao) from tblestoquesaldoconferencia esc where esc.codestoquesaldo = tblestoquesaldo.codestoquesaldo)
where tblestoquesaldo.codestoquesaldo in 
	(
	select esc.codestoquesaldo
	from tblestoquesaldoconferencia esc
	left join tblestoquemovimento em on (em.codestoquesaldoconferencia = esc.codestoquesaldoconferencia)
	where em.codestoquesaldoconferencia is null
	)

INSERT INTO tblestoquemovimento(
            codestoquemovimento
            , codestoquemovimentotipo
            , entradaquantidade
            , entradavalor
            , saidaquantidade
            , saidavalor
            , codnegocioprodutobarra
            , codnotafiscalprodutobarra
            , codestoquemes
            , manual
            , data
            , alteracao
            , codusuarioalteracao
            , criacao
            , codusuariocriacao
            , codestoquemovimentoorigem
            , observacoes
            , codestoquesaldoconferencia)
    VALUES (?, ?, ?, 
            ?, ?, ?, ?, 
            ?, ?, ?, ?, ?, 
            ?, ?, ?, ?, 
            ?, ?);

select * 
from tblestoquelocalprodutovariacao 

select  
	  pb.codprodutovariacao
	, n.codestoquelocal
	, sum(npb.quantidade) as quantidadeinformada
	, min(npb.alteracao) as alteracao
	, min(npb.codusuarioalteracao) as codusuarioalteracao
	, min(npb.criacao) as criacao
	, min(npb.codusuariocriacao) as codusuariocriacao
from tblnegocioprodutobarra npb
left join tblnegocio n on (n.codnegocio = npb.codnegocio)
left join tblprodutobarra pb on (pb.codprodutobarra = npb.codprodutobarra)
where npb.codnegocio = 440000
group by 
	  pb.codprodutovariacao
	, n.codestoquelocal

select * 
from tblproduto p
left join tblprodutovariacao pv on (pv.codproduto = p.codproduto)

select * from tblestoquemovimentotipo
*/
select em.entradavalor / em.entradaquantidade as custo
from tblestoquemovimento em
inner join tblestoquemes mes on (mes.codestoquemes = em.codestoquemes)
inner join tblestoquesaldo es on (es.codestoquesaldo = mes.codestoquesaldo and es.fiscal = false)
inner join tblestoquelocalprodutovariacao elpv on (elpv.codestoquelocalprodutovariacao = es.codestoquelocalprodutovariacao)
inner join tblprodutovariacao pv on (pv.codprodutovariacao = elpv.codprodutovariacao)
where em.codestoquemovimentotipo = 2001
and pv.codproduto = 80
and coalesce(em.entradaquantidade, 0) > 0
order by data desc
limit 100

