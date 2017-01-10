/*

select * from tblmarca where marca ilike '%queen%'

update tblproduto set codmarca = 30000141 where codmarca in (
30010280
,10000430
,10000431
)


"Mc Queen"
"Queen"


select produto, replace(produto, 'Queen', 'Mc Queen') from tblproduto 
where produto ilike '%Queen%'
and produto not ilike '%mc Queen%'
and codmarca = 30000141

update tblproduto
set produto = replace(produto, 'Queen', 'Mc Queen') 
where produto ilike '%Queen%'
and produto not ilike '%mc Queen%'
and codmarca = 30000141


select 
produto,
replace(produto, 'Caderno Tilibra Brochura 1/4 96fls', 'Caderno Tilibra 1/4 96fls Cd Cost Broch')
from tblproduto
where produto ilike 'Caderno Tilibra Brochura 1/4 96fls Cd Cost%'

update tblproduto
set produto = replace(produto, 'Caderno Tilibra Cost Cd 1/4 96fls', 'Caderno Tilibra 1/4 96fls Cd Cost')
where produto ilike 'Caderno Tilibra Cost Cd 1/4 96fls%'

update tblnfeterceiroitem set margem = 55 where codnfeterceiro = 3688

*/

/*
select * 
from tblnotafiscalprodutobarra nfpb
where nfpb.codnotafiscal in (
	select nf.codnotafiscal
	from tblnotafiscal nf
	where nf.nfeautorizacao is null
	and nf.nfecancelamento is null
	and nf.nfeinutilizacao is null
	and nf.emissao >= '2015-12-01 00:00:00.0'
)
and nfpb.codcfop = 5102
and nfpb.csosn = '500'

update tblnotafiscalprodutobarra nfpb
set csosn = '900'
where nfpb.codnotafiscal in (
	select nf.codnotafiscal
	from tblnotafiscal nf
	where nf.nfeautorizacao is null
	and nf.nfecancelamento is null
	and nf.nfeinutilizacao is null
	and nf.emissao >= '2015-12-01 00:00:00.0'
)
and nfpb.codcfop = 5102
and nfpb.csosn = '500'

*/
/*
update tblproduto
set produto = replace(produto, '20X1', '20x1') 
where produto ilike '%20X1%'
*/

