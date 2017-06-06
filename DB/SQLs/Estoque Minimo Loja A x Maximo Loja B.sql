select 
	p.codmarca
	, m.marca
	, p.codproduto
	, p.produto
	, pv.variacao
	, array_to_string(array(
		select pb.barras
		from tblprodutobarra pb 
		where pb.codproduto = p.codproduto 
		and pb.codprodutovariacao = pv.codprodutovariacao
		and pb.codprodutoembalagem is null 
		--group by pb.codproduto
	), ', ') as barras
	, es_orig.saldoquantidade saldo_origem
	, elpv_orig.estoquemaximo maximo_origem
	, es_dest.saldoquantidade saldo_destino
	, elpv_dest.estoqueminimo minimo_destino
from tblproduto p
inner join tblmarca m on (m.codmarca = p.codmarca)
inner join tblprodutovariacao pv on (pv.codproduto = p.codproduto)
inner join tblestoquelocalprodutovariacao elpv_orig on (elpv_orig.codprodutovariacao = pv.codprodutovariacao and elpv_orig.codestoquelocal = 102001)
inner join tblestoquesaldo es_orig on (es_orig.codestoquelocalprodutovariacao = elpv_orig.codestoquelocalprodutovariacao and es_orig.fiscal = false)
inner join tblestoquelocalprodutovariacao elpv_dest on (elpv_dest.codprodutovariacao = pv.codprodutovariacao and elpv_dest.codestoquelocal = 104001)
inner join tblestoquesaldo es_dest on (es_dest.codestoquelocalprodutovariacao = elpv_dest.codestoquelocalprodutovariacao and es_dest.fiscal = false)
where p.inativo is null
and es_orig.saldoquantidade > coalesce(elpv_orig.estoquemaximo, 0)
and es_dest.saldoquantidade < coalesce(elpv_dest.estoqueminimo, 0)
--and p.codmarca = 19
order by
	m.marca
	, p.produto
	, pv.variacao
	
--limit 100