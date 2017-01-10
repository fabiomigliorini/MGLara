select 
       tblproduto.codproduto
     , PRODUTO
     , ncm
     , case when tblproduto.importado then tblibptax.aliqimp else tblibptax.aliqnac end as aliquota
from tblproduto 
left join tblibptax on (cast(tblproduto.ncm as varchar) like tblibptax.codigo || '%' and tblibptax.ex is null and tblibptax.tabela = 0) 
where tblproduto.inativo is null
and tblibptax.codibptax is null
--limit 10

/*
update tblproduto
set ncm = 48191000 
where ncm = 49191000
*/