update tblproduto
set site = true
where site = false
and codproduto in (select codproduto from tblprodutoimagem)

update tblproduto
set site = false
where site = true
and codproduto not in (select codproduto from tblprodutoimagem)