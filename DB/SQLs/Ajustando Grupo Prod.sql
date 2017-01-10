select count(*) from tblproduto where codsubgrupoproduto is not null

update tblproduto set codsubgrupoproduto = 2004 where produto ilike 'lembrancinha%' and codsubgrupoproduto is null
--update tblproduto set codsubgrupoproduto = 11999 where codmarca = 21 and codsubgrupoproduto is null

--update tblproduto set codsubgrupoproduto = 4057 where codsubgrupoproduto = 4074 and produto ilike '%flip%chart%'

select * from tblproduto where produto ilike '%Caichinhos%' order by produto

update tblproduto set produto = replace(produto, 'Livros', 'Livro') where produto ilike 'Livros%' 
update tblproduto set produto = replace(produto, '  ', ' ') where produto ilike '%  %' 

select 'C/Grupo', count(*) from tblproduto where codsubgrupoproduto is not null
union all
select 'S/Grupo', count(*) from tblproduto where codsubgrupoproduto is null

select * from tblsubgrupoproduto where subgrupoproduto ilike '%lix%'