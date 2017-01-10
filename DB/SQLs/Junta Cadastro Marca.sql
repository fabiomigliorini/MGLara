declare @apagar, @manter, @reg_apagar, @reg_manter; 

begin transaction;

set @apagar = 10000473; 
set @manter = 30000022; 

update tblproduto set codmarca = @manter where codmarca = @apagar;
update tblprodutovariacao set codmarca = @manter where codmarca = @apagar;
update tblprodutobarra set codmarca = @manter where codmarca = @apagar;

update tblproduto 
set produto = replace(produto, 
	(select marca from tblmarca where codmarca = @apagar), 
	(select marca from tblmarca where codmarca = @manter)
	) 
where codmarca = @manter 
and produto not ilike '%' || 
	(select marca from tblmarca where codmarca = @manter)
	|| '%';


update tblproduto 
set produto = produto || ' ' || (select marca from tblmarca where codmarca = @manter)
where codmarca = @manter 
and produto not ilike '%' || 
	(select marca from tblmarca where codmarca = @manter)
	|| '%';

delete from tblmarca where codmarca = @apagar;

commit;

/*
declare @mytbl, @maxid; 

set @mytbl = 'tblpessoa'; 
set @maxid = 50; 

select count(*) from @mytbl where id <= @maxid; 




delete from tblmarca where codmarca = 30000052;
*/
--SELECT current_setting('foo.apagar');

--select url;
