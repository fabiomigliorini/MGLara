
select sum(coalesce(valoravista, 0)), sum(coalesce(valordesconto, 0)), sum(coalesce(valordesconto, 0))/sum(coalesce(valortotal, 0)) *100, sum(coalesce(valortotal, 0)), usuario 
from tblnegocio n
left join tblusuario u on (u.codusuario = n.codusuario)
where lancamento >= '2015-02-18 00:00:00.0'
and lancamento <= '2015-02-18 17:25:04'
and n.codnegociostatus = 2
group by u.usuario
order by u.usuario

--select * from 

select sum(coalesce(valoravista, 0)), sum(coalesce(valordesconto, 0)), sum(coalesce(valordesconto, 0))/sum(coalesce(valoravista, 0)) *100, sum(coalesce(valortotal, 0)), usuario, n.codnegocio 
from tblnegocio n
left join tblusuario u on (u.codusuario = n.codusuario)
where lancamento >= '2015-02-18 00:00:00.0'
and lancamento <= '2015-02-18 17:25:04'
and n.codnegociostatus = 2
and n.valoravista > 0
and u.usuario = 'cxamig01'
group by u.usuario, n.codnegocio 
order by 3 desc --u.usuario, n.codnegocio 
