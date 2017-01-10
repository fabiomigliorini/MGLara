
delete from tblnegocioprodutobarra where tblnegocioprodutobarra.codnegocio in 
(
	select tblnegocio.codnegocio 
	from tblnegocio 
	where tblnegocio.codnegociostatus = 1
	and tblnegocio.lancamento < (current_date - interval '30 day')
	--and tblnegocio.codusuario in (select tblusuario.codusuario from tblusuario where usuario ilike 'esc%')
)

update tblnegocio 
set codnegociostatus = 3 
where tblnegocio.codnegociostatus = 1
and tblnegocio.lancamento < (current_date - interval '30 day')
--and tblnegocio.codusuario in (select tblusuario.codusuario from tblusuario where usuario ilike 'esc%')

select * from tblnegocioprodutobarra where codnegocioprodutobarra = 20131435

update tblnegocio set codnegociostatus = 2 where codnegocio = 20049883
