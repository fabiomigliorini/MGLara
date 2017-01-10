select 
	p2.codproduto
	, p2.produto
	, sldfiscal.sldfiscal
	, movfiscal.movfiscal
	, sldfisico.sldfisico
	, sldfiscal.sldfiscal + movfiscal.movfiscal - sldfisico.sldfisico as disponivel
	, p2.preco
	, (sldfiscal.sldfiscal + movfiscal.movfiscal - sldfisico.sldfisico) * p2.preco as valordisponivel
from tblproduto p2
left join (
	--movimentacao fiscal
	select pb.codproduto, sum(nfpb.quantidade * coalesce(pe.quantidade, 1) * case when nf.codoperacao = 1 then 1 else -1 end) as movfiscal
	from tblnotafiscalprodutobarra nfpb
	inner join tblnotafiscal nf on (nf.codnotafiscal = nfpb.codnotafiscal)
	inner join tblprodutobarra pb on (pb.codprodutobarra = nfpb.codprodutobarra)
	left join tblprodutoembalagem pe on (pe.codprodutoembalagem = pb.codprodutoembalagem)
	inner join tblfilial f on (f.codfilial = nf.codfilial)
	where nf.emissao >= '2016-01-01'
	and (nf.emitida = false or (nf.nfeautorizacao is not null and nf.nfeinutilizacao is null and nf.nfecancelamento is null))
	--and pb.codproduto = 100
	and f.codempresa = 1
	group by pb.codproduto
	--limit 50
	) movfiscal on (movfiscal.codproduto = p2.codproduto)
left join (
	-- saldo estoque fisico
	select pv.codproduto, sum(saldoquantidade) as sldfisico
	from tblestoquesaldo es
	inner join tblestoquelocalprodutovariacao elpv on (elpv.codestoquelocalprodutovariacao = es.codestoquelocalprodutovariacao)
	inner join tblprodutovariacao pv on (pv.codprodutovariacao = elpv.codprodutovariacao)
	inner join tblestoquelocal el on (el.codestoquelocal = elpv.codestoquelocal)
	inner join tblfilial f on (f.codfilial = el.codfilial)
	where es.fiscal = false
	and f.codempresa = 1
	--and pv.codproduto = 100
	group by pv.codproduto
	) sldfisico on (sldfisico.codproduto = p2.codproduto)
left join (
	-- saldo inicial estoque fiscal
	select pv.codproduto, sum(saldoquantidade) as sldfiscal
	from tblestoquesaldo es
	inner join tblestoquelocalprodutovariacao elpv on (elpv.codestoquelocalprodutovariacao = es.codestoquelocalprodutovariacao)
	inner join tblprodutovariacao pv on (pv.codprodutovariacao = elpv.codprodutovariacao)
	inner join tblestoquelocal el on (el.codestoquelocal = elpv.codestoquelocal)
	inner join tblfilial f on (f.codfilial = el.codfilial)
	where es.fiscal = true
	and f.codempresa = 1
	--and pv.codproduto = 100
	group by pv.codproduto
	) sldfiscal on (sldfiscal.codproduto = p2.codproduto)
--where p2.codproduto = 100

