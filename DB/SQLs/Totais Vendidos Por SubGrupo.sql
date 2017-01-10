--select * from tblgrupoproduto order by codgrupoproduto

select 
       tblgrupoproduto.codgrupoproduto
     , tblgrupoproduto.grupoproduto
     , tblsubgrupoproduto.codsubgrupoproduto
     , tblsubgrupoproduto.subgrupoproduto
     , iq.quant
     , iq.val
from tblsubgrupoproduto
left join tblgrupoproduto        on (tblgrupoproduto.codgrupoproduto         = tblsubgrupoproduto.codgrupoproduto)
left join 
	(
	select tblproduto.codsubgrupoproduto
	     , sum(tblnegocioprodutobarra.quantidade * coalesce(tblprodutoembalagem.quantidade, 1)) as quant 
	     , sum(tblnegocioprodutobarra.valortotal) as val
	  from tblnegocio 
	  left join tblnegocioprodutobarra on (tblnegocioprodutobarra.codnegocio       = tblnegocio.codnegocio)
	  left join tblprodutobarra        on (tblprodutobarra.codprodutobarra         = tblnegocioprodutobarra.codprodutobarra)
	  left join tblproduto             on (tblproduto.codproduto                   = tblprodutobarra.codproduto)
	  left join tblprodutoembalagem    on (tblprodutoembalagem.codprodutoembalagem = tblprodutobarra.codprodutoembalagem)
	 where tblnegocio.codoperacao       = 2 -- saida
	   and tblnegocio.codnegociostatus  = 2 -- fechado
	   and tblnegocio.lancamento       >= '2013-01-01 00:00:00.0'
	 group by tblproduto.codsubgrupoproduto
	 ) iq on (iq.codsubgrupoproduto = tblsubgrupoproduto.codsubgrupoproduto)
  where tblsubgrupoproduto.codgrupoproduto = 3
  order by grupoproduto asc, val desc, subgrupoproduto asc