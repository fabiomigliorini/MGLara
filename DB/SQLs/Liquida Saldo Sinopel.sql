/*
INSERT INTO tblnotafiscalprodutobarra(
            codnotafiscal
            , codprodutobarra
            , codcfop
            , quantidade
            , valorunitario
            , valortotal
            , alteracao
            , codusuarioalteracao
            , criacao
            , codusuariocriacao
            )
*/
select sum(valortotal) as valor_transferencia_total, count(*) from (
select 
	420126 as codnotafiscal
	, (
		select pb.codprodutobarra 
		from tblprodutobarra pb
		where pb.codproduto = iq.codproduto
		and pb.codprodutoembalagem is null
		order by pb.criacao nulls first
		, pb.codprodutobarra
		limit 1
	) as codprodutobarra
	, 5927 as codcfop	
	, least(coalesce(saldoquantidade_fiscal_sin, 0), coalesce(deficit_fiscal_mig, 0)) as quantidade
	, round((coalesce(saldovalor_fiscal_sin, 0) / coalesce(saldoquantidade_fiscal_sin, 1)) * .7, 2) as valorunitario
	, round((coalesce(saldovalor_fiscal_sin, 0) / coalesce(saldoquantidade_fiscal_sin, 1)) * .7, 2) * least(coalesce(saldoquantidade_fiscal_sin, 0), coalesce(deficit_fiscal_mig, 0)) as valortotal
	, '2016-11-30 12:30'::timestamp as alteracao
	, 1 as codusuarioalteracao
	, '2016-11-30 12:30'::timestamp criacao
	, 1 as codusuariocriacao
from (
select 
	p.codproduto
	, p.produto
	, m.marca
	, p.preco
	, p.inativo is null as ativo
	, case when (coalesce(fisico_mig.saldoquantidade, 0) - coalesce(fiscal_mig.saldoquantidade, 0)) > 0 then coalesce(fisico_mig.saldoquantidade, 0) - coalesce(fiscal_mig.saldoquantidade, 0) else null end as deficit_fiscal_mig
	, fisico_mig.saldoquantidade as saldoquantidade_fisico_mig
	, fisico_mig.saldovalor as saldovalor_fisico_mig
	, fiscal_mig.saldoquantidade as saldoquantidade_fiscal_mig
	, fiscal_mig.saldovalor as saldovalor_fiscal_mig
	, fiscal_fdf.saldoquantidade as saldoquantidade_fiscal_fdf
	, fiscal_fdf.saldovalor as saldovalor_fiscal_fdf
	, fiscal_sin.saldoquantidade as saldoquantidade_fiscal_sin
	, fiscal_sin.saldovalor as saldovalor_fiscal_sin
from tblproduto p
left join tblmarca m on (m.codmarca = p.codmarca)
left join (
	select pv.codproduto, sum(es.saldoquantidade) as saldoquantidade, sum(es.saldovalor) as saldovalor
	from tblestoquesaldo es
	inner join tblestoquelocalprodutovariacao elpv on (elpv.codestoquelocalprodutovariacao = es.codestoquelocalprodutovariacao)
	inner join tblestoquelocal el on (el.codestoquelocal = elpv.codestoquelocal)
	inner join tblprodutovariacao pv on (pv.codprodutovariacao = elpv.codprodutovariacao)
	inner join tblfilial f on (f.codfilial = el.codfilial)
	where es.fiscal = true
	and es.saldoquantidade <> 0
	and f.codempresa = 1 
	group by pv.codproduto
	order by pv.codproduto
	) fiscal_mig on (fiscal_mig.codproduto = p.codproduto)
left join (
	select pv.codproduto, sum(es.saldoquantidade) as saldoquantidade, sum(es.saldovalor) as saldovalor
	from tblestoquesaldo es
	inner join tblestoquelocalprodutovariacao elpv on (elpv.codestoquelocalprodutovariacao = es.codestoquelocalprodutovariacao)
	inner join tblestoquelocal el on (el.codestoquelocal = elpv.codestoquelocal)
	inner join tblprodutovariacao pv on (pv.codprodutovariacao = elpv.codprodutovariacao)
	inner join tblfilial f on (f.codfilial = el.codfilial)
	where es.fiscal = false
	and es.saldoquantidade <> 0
	and f.codempresa = 1 
	group by pv.codproduto
	order by pv.codproduto
	) fisico_mig on (fisico_mig.codproduto = p.codproduto)
left join (
	select pv.codproduto, sum(es.saldoquantidade) as saldoquantidade, sum(es.saldovalor) as saldovalor
	from tblestoquesaldo es
	inner join tblestoquelocalprodutovariacao elpv on (elpv.codestoquelocalprodutovariacao = es.codestoquelocalprodutovariacao)
	inner join tblestoquelocal el on (el.codestoquelocal = elpv.codestoquelocal)
	inner join tblprodutovariacao pv on (pv.codprodutovariacao = elpv.codprodutovariacao)
	inner join tblfilial f on (f.codfilial = el.codfilial)
	where es.fiscal = true
	and es.saldoquantidade <> 0
	and f.codempresa = 2 
	group by pv.codproduto
	order by pv.codproduto
	) fiscal_fdf on (fiscal_fdf.codproduto = p.codproduto)
left join (
	select pv.codproduto, sum(es.saldoquantidade) as saldoquantidade, sum(es.saldovalor) as saldovalor
	from tblestoquesaldo es
	inner join tblestoquelocalprodutovariacao elpv on (elpv.codestoquelocalprodutovariacao = es.codestoquelocalprodutovariacao)
	inner join tblestoquelocal el on (el.codestoquelocal = elpv.codestoquelocal)
	inner join tblprodutovariacao pv on (pv.codprodutovariacao = elpv.codprodutovariacao)
	inner join tblfilial f on (f.codfilial = el.codfilial)
	where es.fiscal = true
	and es.saldoquantidade <> 0
	and f.codempresa = 3
	group by pv.codproduto
	order by pv.codproduto
	) fiscal_sin on (fiscal_sin.codproduto = p.codproduto)
where
	fisico_mig.saldoquantidade != 0
	or fiscal_mig.saldoquantidade != 0
	or fiscal_fdf.saldoquantidade != 0 
	or fiscal_sin.saldoquantidade != 0
) iq
where saldoquantidade_fiscal_sin > 0
--and (coalesce(deficit_fiscal_mig, 0) = 0 and coalesce(saldoquantidade_fisico_mig, 0) <= 0)
--and codproduto = 2314
--and ativo = false
and deficit_fiscal_mig > 0
--and round((coalesce(saldovalor_fiscal_sin, 0) / coalesce(saldoquantidade_fiscal_sin, 1)) * .7, 2) <= .01
--and codproduto between 315001 and 931500
--and codproduto >= 931501 
) iq2