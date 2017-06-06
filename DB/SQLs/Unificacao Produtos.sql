select 
	p.inativo
	, (select count(pv.codprodutovariacao) from tblprodutovariacao pv where pv.codproduto = p.codproduto) as qtdvriacoes
	, * 
from tblproduto p
where p.produto ilike '%scrity%11%16%'
order by p.produto asc

/*
update tblnegocioprodutobarra set codprodutobarra = 52123 where codprodutobarra in (52159, 52135, 52151, 52131, 944841, 52167, 52127, 52143, 30010478, 52139, 52155, 52147, 52163);

update tblnotafiscalprodutobarra set codprodutobarra = 52123 where codprodutobarra in (52159, 52135, 52151, 52131, 944841, 52167, 52127, 52143, 30010478, 52139, 52155, 52147, 52163);

update tblcupomfiscalprodutobarra set codprodutobarra = 52123 where codprodutobarra in (52159, 52135, 52151, 52131, 944841, 52167, 52127, 52143, 30010478, 52139, 52155, 52147, 52163);

update tblnfeterceiroitem set codprodutobarra = 52123 where codprodutobarra in (52159, 52135, 52151, 52131, 944841, 52167, 52127, 52143, 30010478, 52139, 52155, 52147, 52163);
*/