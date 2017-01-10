-- Cria Tabela com codbarras
drop table marcabarratemp;

create table marcabarratemp as 
select iniciobarras, min(codmarca) as codmarcabarras from (
select substring(barras from 1 for 9) as iniciobarras, tblmarca.codmarca, tblmarca.marca, count(*) as produtos
from tblproduto
inner join tblprodutobarra on (tblprodutobarra.codproduto = tblproduto.codproduto)
inner join tblmarca on (tblmarca.codmarca = coalesce(tblprodutobarra.codmarca, tblproduto.codmarca))
where char_length(barras) > 12
and  barras not like '00%'
group by substring(barras from 1 for 9), tblmarca.codmarca, tblmarca.marca
) x
group by iniciobarras
having count(*) = 1;

delete from marcabarratemp where iniciobarras = 'DD123;17X';
delete from marcabarratemp where iniciobarras = 'E-122-50X';

update tblproduto 
set codmarca = marcabarratemp.codmarcabarras
from tblprodutobarra 
	, marcabarratemp 
where tblproduto.codmarca is null
and tblprodutobarra.codproduto = tblproduto.codproduto
and tblprodutobarra.barras like marcabarratemp.iniciobarras || '%';

drop table marcabarratemp;
