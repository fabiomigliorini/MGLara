select u.usuario , count(npb.codnegocioprodutobarra), sum(npb.quantidade), sum(npb.valortotal), sum(npb.valortotal) / count(npb.codnegocioprodutobarra)
from tblnegocio n
left join tblnegocioprodutobarra npb on (npb.codnegocio = n.codnegocio)
left join tblusuario u on (n.codusuario = u.codusuario)
where n.lancamento >= '2014-10-01 00:00:00.0'
group by u.usuario
order by u.usuario