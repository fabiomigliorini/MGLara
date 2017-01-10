--update tblnegocio set codnegociostatus = 3 where codnegociostatus = 2 and codnegocio = 10333899

select u.usuario, count(*)
from tblnegocio n
inner join tblusuario u on (u.codusuario = n.codusuario)
where codnegociostatus = 1
group by u.usuario
order by 1