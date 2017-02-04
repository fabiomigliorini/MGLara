select 'total' as ncm, count(*)
from tblestoquesaldo es 
inner join tblestoquemes em on (em.codestoquesaldo = es.codestoquesaldo)
where es.fiscal = true
and em.saldoquantidade < 0
and em.mes <= '2016-12-31 23:59:59.9'

union all

select n.ncm, count(*)
from tblestoquesaldo es 
inner join tblestoquemes em on (em.codestoquesaldo = es.codestoquesaldo)
inner join tblestoquelocalprodutovariacao elpv on (elpv.codestoquelocalprodutovariacao = es.codestoquelocalprodutovariacao)
inner join tblprodutovariacao pv on (pv.codprodutovariacao = elpv.codprodutovariacao)
inner join tblproduto p on (p.codproduto = pv.codproduto)
inner join tblncm n on (n.codncm = p.codncm)
where es.fiscal = true
and em.saldoquantidade < 0
and em.mes <= '2016-12-31 23:59:59.9'
group by n.ncm
order by 1 asc
--order by 2 desc
/*
select nf.emissao, nf.codnotafiscal, pb.codproduto from tblnotafiscalprodutobarra nfpb 
inner join tblnotafiscal nf on (nf.codnotafiscal = nfpb.codnotafiscal)
inner join tblprodutobarra pb on (pb.codprodutobarra = nfpb.codprodutobarra)
where nfpb.codnotafiscalprodutobarra = 1896275


select nf.lancamento, nf.codnegocio, pb.codproduto from tblnegocioprodutobarra nfpb 
inner join tblnegocio nf on (nf.codnegocio = nfpb.codnegocio)
inner join tblprodutobarra pb on (pb.codprodutobarra = nfpb.codprodutobarra)
where nfpb.codnegocioprodutobarra = 1733615

select count(*) from tbljobs

delete from tbljobs
*/