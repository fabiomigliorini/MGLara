select 
	p.codproduto
	, sin_fiscal.codprodutovariacao
	, p.produto
	, m.marca
	, p.inativo
	, sin_fiscal.saldoquantidade as qtd_fiscal_sin 
	, sin_fiscal.saldovalor as val_fiscal_sin 
	, mig_fiscal.saldoquantidade as qtd_fiscal_mig 
	, mig_fisico.saldoquantidade as qtd_fisico_mig 
from tblproduto p
inner join (
	select pv.codproduto, sum(es.saldoquantidade) as saldoquantidade, sum(es.saldovalor) as saldovalor, min(elpv.codprodutovariacao) as codprodutovariacao
	from tblprodutovariacao pv
	inner join tblestoquelocalprodutovariacao elpv on (elpv.codprodutovariacao = pv.codprodutovariacao)
	inner join tblestoquelocal el on (el.codestoquelocal = elpv.codestoquelocal)
	inner join tblfilial f on (f.codfilial = el.codfilial and f.codempresa = 3) -- Sinopel
	inner join tblestoquesaldo es on (es.codestoquelocalprodutovariacao = elpv.codestoquelocalprodutovariacao and es.fiscal = true) -- Fiscal
	group by pv.codproduto	
) sin_fiscal on (sin_fiscal.codproduto = p.codproduto and sin_fiscal.saldoquantidade > 0)
left join tblmarca m on (m.codmarca = p.codmarca)
left join (
	select pv.codproduto, sum(es.saldoquantidade) as saldoquantidade
	from tblprodutovariacao pv
	inner join tblestoquelocalprodutovariacao elpv on (elpv.codprodutovariacao = pv.codprodutovariacao)
	inner join tblestoquelocal el on (el.codestoquelocal = elpv.codestoquelocal)
	inner join tblfilial f on (f.codfilial = el.codfilial and f.codempresa = 1) -- Migliorini
	inner join tblestoquesaldo es on (es.codestoquelocalprodutovariacao = elpv.codestoquelocalprodutovariacao and es.fiscal = true) -- Fiscal
	group by pv.codproduto	
) mig_fiscal on (mig_fiscal.codproduto = p.codproduto)
left join (
	select pv.codproduto, sum(es.saldoquantidade) as saldoquantidade
	from tblprodutovariacao pv
	inner join tblestoquelocalprodutovariacao elpv on (elpv.codprodutovariacao = pv.codprodutovariacao)
	inner join tblestoquelocal el on (el.codestoquelocal = elpv.codestoquelocal)
	inner join tblfilial f on (f.codfilial = el.codfilial and f.codempresa = 1) -- Migliorini
	inner join tblestoquesaldo es on (es.codestoquelocalprodutovariacao = elpv.codestoquelocalprodutovariacao and es.fiscal = false) -- Fisico
	group by pv.codproduto	
) mig_fisico on (mig_fisico.codproduto = p.codproduto)
--where p.codproduto = 1146


