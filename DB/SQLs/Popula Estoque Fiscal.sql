-- Popula Estoque Local Produto Variacao
INSERT INTO tblestoquelocalprodutovariacao (codestoquelocal, codprodutovariacao)
select distinct nf.codestoquelocal, pb.codprodutovariacao--, elpv.codestoquelocalprodutovariacao
from tblnotafiscal nf
inner join tblnotafiscalprodutobarra nfpb on (nfpb.codnotafiscal = nf.codnotafiscal)
inner join tblnaturezaoperacao no on (no.codnaturezaoperacao = nf.codnaturezaoperacao)
inner join tblprodutobarra pb on (pb.codprodutobarra = nfpb.codprodutobarra)
inner join tblproduto p on (p.codproduto = pb.codproduto)
inner join tbltipoproduto tp on (tp.codtipoproduto = p.codtipoproduto)
left join tblestoquelocalprodutovariacao elpv on (elpv.codprodutovariacao = pb.codprodutovariacao and elpv.codestoquelocal = nf.codestoquelocal)
where nf.saida between '2016-01-01 00:00:00.0' and '2016-12-31 23:59:59.9'
and (nf.emitida = false
	or (nf.emitida = true and nf.nfeautorizacao is not null and nf.nfecancelamento is null and nf.nfeinutilizacao is null )
	)
and no.estoque = true
and tp.estoque = true
and elpv.codestoquelocalprodutovariacao is null

-- Popula Estoque Saldo
INSERT INTO tblestoquesaldo (codestoquelocalprodutovariacao, fiscal, saldoquantidade)
select distinct elpv.codestoquelocalprodutovariacao, true, 0
from tblnotafiscal nf
inner join tblnotafiscalprodutobarra nfpb on (nfpb.codnotafiscal = nf.codnotafiscal)
inner join tblnaturezaoperacao no on (no.codnaturezaoperacao = nf.codnaturezaoperacao)
inner join tblprodutobarra pb on (pb.codprodutobarra = nfpb.codprodutobarra)
inner join tblproduto p on (p.codproduto = pb.codproduto)
inner join tbltipoproduto tp on (tp.codtipoproduto = p.codtipoproduto)
inner join tblestoquelocalprodutovariacao elpv on (elpv.codprodutovariacao = pb.codprodutovariacao and elpv.codestoquelocal = nf.codestoquelocal)
left join tblestoquesaldo es on (es.codestoquelocalprodutovariacao = elpv.codestoquelocalprodutovariacao and es.fiscal = true)
where nf.saida between '2016-01-01 00:00:00.0' and '2016-12-31 23:59:59.9'
and (nf.emitida = false
	or (nf.emitida = true and nf.nfeautorizacao is not null and nf.nfecancelamento is null and nf.nfeinutilizacao is null )
	)
and no.estoque = true
and tp.estoque = true
and es.codestoquesaldo is null

-- Popula Estoque Mes
INSERT INTO tblestoquemes (codestoquesaldo, mes)
select distinct es.codestoquesaldo, '2016-12-01'::date
from tblnotafiscal nf
inner join tblnotafiscalprodutobarra nfpb on (nfpb.codnotafiscal = nf.codnotafiscal)
inner join tblnaturezaoperacao no on (no.codnaturezaoperacao = nf.codnaturezaoperacao)
inner join tblprodutobarra pb on (pb.codprodutobarra = nfpb.codprodutobarra)
inner join tblproduto p on (p.codproduto = pb.codproduto)
inner join tbltipoproduto tp on (tp.codtipoproduto = p.codtipoproduto)
inner join tblestoquelocalprodutovariacao elpv on (elpv.codprodutovariacao = pb.codprodutovariacao and elpv.codestoquelocal = nf.codestoquelocal)
inner join tblestoquesaldo es on (es.codestoquelocalprodutovariacao = elpv.codestoquelocalprodutovariacao and es.fiscal = true)
left join tblestoquemes em on (em.codestoquesaldo = es.codestoquesaldo and em.mes = '2016-12-01')
where nf.saida between '2016-01-01 00:00:00.0' and '2016-12-31 23:59:59.9'
and (nf.emitida = false
	or (nf.emitida = true and nf.nfeautorizacao is not null and nf.nfecancelamento is null and nf.nfeinutilizacao is null )
	)
and no.estoque = true
and tp.estoque = true
and em.codestoquemes is null

-- Popula Estoque Movimento
INSERT INTO tblestoquemovimento(
            codestoquemovimentotipo
            , entradaquantidade
            , entradavalor
            , saidaquantidade
            , saidavalor
            , codnegocioprodutobarra
            , codnotafiscalprodutobarra
            , codestoquemes
            , manual
            , data
            )
select 
	no.codestoquemovimentotipo
	, case when nf.codoperacao = 1 then nfpb.quantidade * coalesce(pe.quantidade, 1) else null end
	, case when nf.codoperacao = 1 then nfpb.valortotal else null end
	, case when nf.codoperacao = 2 then nfpb.quantidade * coalesce(pe.quantidade, 1) else null end
	, case when nf.codoperacao = 2 then nfpb.valortotal else null end
	, null as codnegocioprodutobarra
	, nfpb.codnotafiscalprodutobarra
	, em.codestoquemes
	, false as manual
	, nf.saida as data
from tblnotafiscal nf
inner join tblnotafiscalprodutobarra nfpb on (nfpb.codnotafiscal = nf.codnotafiscal)
inner join tblnaturezaoperacao no on (no.codnaturezaoperacao = nf.codnaturezaoperacao)
inner join tblprodutobarra pb on (pb.codprodutobarra = nfpb.codprodutobarra)
left join tblprodutoembalagem pe on (pe.codprodutoembalagem = pb.codprodutoembalagem)
inner join tblproduto p on (p.codproduto = pb.codproduto)
inner join tbltipoproduto tp on (tp.codtipoproduto = p.codtipoproduto)
inner join tblestoquelocalprodutovariacao elpv on (elpv.codprodutovariacao = pb.codprodutovariacao and elpv.codestoquelocal = nf.codestoquelocal)
inner join tblestoquesaldo es on (es.codestoquelocalprodutovariacao = elpv.codestoquelocalprodutovariacao and es.fiscal = true)
inner join tblestoquemes em on (em.codestoquesaldo = es.codestoquesaldo and em.mes = '2016-12-01')
left join tblestoquemovimento mov on (mov.codnotafiscalprodutobarra = nfpb.codnotafiscalprodutobarra)
where nf.saida between '2016-01-01 00:00:00.0' and '2016-12-31 23:59:59.9'
and (nf.emitida = false
	or (nf.emitida = true and nf.nfeautorizacao is not null and nf.nfecancelamento is null and nf.nfeinutilizacao is null )
	)
and no.estoque = true
and tp.estoque = true
and mov.codestoquemovimento is null

-- Ajusta codestoquemovimentoorigem Baseado na nfpb.codnotafiscalprodutobarraorigem
update tblestoquemovimento
set codestoquemovimentoorigem = movorigem.codestoquemovimento
from tblestoquemovimentotipo, tblnotafiscalprodutobarra, tblestoquemovimento movorigem 
where tblestoquemovimentotipo.codestoquemovimentotipo = tblestoquemovimento.codestoquemovimentotipo
and tblnotafiscalprodutobarra.codnotafiscalprodutobarra = tblestoquemovimento.codnotafiscalprodutobarra
and movorigem.codnotafiscalprodutobarra = tblnotafiscalprodutobarra.codnotafiscalprodutobarraorigem
and tblestoquemovimentotipo.preco = 3
and tblestoquemovimento.codestoquemovimentoorigem is null
and tblestoquemovimento.data >= '2016-01-01 00:00:00.0'
and tblestoquemovimento.codnotafiscalprodutobarra is not null

-- Ajusta codestoquemovimentoorigem Baseado na chave da nota referenciada
update tblestoquemovimento
set codestoquemovimentoorigem = tblestoquemovimento_origem.codestoquemovimento
from 
	tblestoquemovimentotipo, 
	tblnotafiscalprodutobarra, 
	tblnotafiscalreferenciada, 
	tblnotafiscal tblnotafiscal_origem,
	tblnotafiscalprodutobarra tblnotafiscalprodutobarra_origem,
	tblestoquemovimento tblestoquemovimento_origem
where tblestoquemovimentotipo.codestoquemovimentotipo = tblestoquemovimento.codestoquemovimentotipo
and tblnotafiscalprodutobarra.codnotafiscalprodutobarra = tblestoquemovimento.codnotafiscalprodutobarra
and tblnotafiscalreferenciada.codnotafiscal = tblnotafiscalprodutobarra.codnotafiscal
and tblnotafiscal_origem.nfechave = tblnotafiscalreferenciada.nfechave
and tblnotafiscalprodutobarra_origem.codnotafiscal = tblnotafiscal_origem.codnotafiscal
and tblnotafiscalprodutobarra_origem.codprodutobarra = tblnotafiscalprodutobarra.codprodutobarra
and tblestoquemovimento_origem.codnotafiscalprodutobarra = tblnotafiscalprodutobarra_origem.codnotafiscalprodutobarra
and tblestoquemovimentotipo.preco = 3
and tblestoquemovimento.codestoquemovimentoorigem is null
and tblestoquemovimento.data >= '2016-01-01 00:00:00.0'
and tblestoquemovimento.codnotafiscalprodutobarra is not null

-- Ajusta codestoquemovimentoorigem Baseado na chave da nota 
update tblestoquemovimento
set codestoquemovimentoorigem = tblestoquemovimento_origem.codestoquemovimento
from 
	tblestoquemovimentotipo, 
	tblnotafiscalprodutobarra, 
	tblnotafiscal, 
	tblnotafiscal tblnotafiscal_origem,
	tblnotafiscalprodutobarra tblnotafiscalprodutobarra_origem,
	tblestoquemovimento tblestoquemovimento_origem
where tblestoquemovimentotipo.codestoquemovimentotipo = tblestoquemovimento.codestoquemovimentotipo
and tblnotafiscalprodutobarra.codnotafiscalprodutobarra = tblestoquemovimento.codnotafiscalprodutobarra
and tblnotafiscal.codnotafiscal = tblnotafiscalprodutobarra.codnotafiscal
and tblnotafiscal_origem.nfechave = tblnotafiscal.nfechave
and tblnotafiscal_origem.codoperacao != tblnotafiscal.codoperacao
and tblnotafiscalprodutobarra_origem.codnotafiscal = tblnotafiscal_origem.codnotafiscal
and tblnotafiscalprodutobarra_origem.codprodutobarra = tblnotafiscalprodutobarra.codprodutobarra
and tblestoquemovimento_origem.codnotafiscalprodutobarra = tblnotafiscalprodutobarra_origem.codnotafiscalprodutobarra
and tblestoquemovimentotipo.preco = 3
and tblestoquemovimento.codestoquemovimentoorigem is null
and tblestoquemovimento.data >= '2016-01-01 00:00:00.0'
and tblestoquemovimento.codnotafiscalprodutobarra is not null

-- Ajusta Movimento origem com base no codnegocioprodutobarra
update tblestoquemovimento
set codestoquemovimentoorigem = 
	(
	select mov_origem.codestoquemovimento
	from tblnotafiscalprodutobarra nfpb
	inner join tblnotafiscal nf on (nf.codnotafiscal = nfpb.codnotafiscal)
	inner join tblnotafiscalprodutobarra nfpb_origem on (nfpb_origem.codnegocioprodutobarra = nfpb.codnegocioprodutobarra)
	inner join tblnotafiscal nf_origem on (nf_origem.codnotafiscal = nfpb_origem.codnotafiscal and nf_origem.codoperacao != nf.codoperacao and nf_origem.codfilial = nf.codfilial)
	inner join tblestoquemovimento mov_origem on (mov_origem.codnotafiscalprodutobarra = nfpb_origem.codnotafiscalprodutobarra)
	where nfpb.codnotafiscalprodutobarra = tblestoquemovimento.codnotafiscalprodutobarra
	)
from tblestoquemovimentotipo
where tblestoquemovimentotipo.codestoquemovimentotipo = tblestoquemovimento.codestoquemovimentotipo
and tblestoquemovimentotipo.preco = 3
and tblestoquemovimento.data >= '2016-01-01 00:00:00.0'
and tblestoquemovimento.codnotafiscalprodutobarra is not null
and tblestoquemovimento.codestoquemovimentoorigem is null

-- verifica se ficou algum registro apontado pra ele mesmo
update tblestoquemovimento set codestoquemovimentoorigem = null where codestoquemovimentoorigem = codestoquemovimento

-- notas que ficaram sem origem
select distinct nfpb.codnotafiscal
from tblestoquemovimento 
inner join tblestoquemovimentotipo on (tblestoquemovimentotipo.codestoquemovimentotipo = tblestoquemovimento.codestoquemovimentotipo)
inner join tblnotafiscalprodutobarra nfpb on (nfpb.codnotafiscalprodutobarra = tblestoquemovimento.codnotafiscalprodutobarra)
where tblestoquemovimentotipo.preco = 3
and tblestoquemovimento.codestoquemovimentoorigem is null
and tblestoquemovimento.data >= '2016-01-01 00:00:00.0'
and tblestoquemovimento.codnotafiscalprodutobarra is not null
limit 100

-- Comando Wget para Recalcular Custo Medio
select distinct 'wget ''http://localhost/MGLara/estoque/calcula-custo-medio/' || mes.codestoquemes::text ||''' -O /dev/null'
from tblestoquesaldo sld
inner join tblestoquemes mes on (mes.codestoquesaldo = sld.codestoquesaldo)
inner join tblestoquemovimento mov on (mov.codestoquemes = mes.codestoquemes)
where sld.fiscal = true
and mes.mes = '2016-12-01'
and mov.data >= '2016-01-01'
--order by mes.codestoquemes
--limit 100

select count(*) from tbljobs