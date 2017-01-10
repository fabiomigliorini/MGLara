select tblgrupoproduto.codgrupoproduto
     , tblgrupoproduto.grupoproduto
     , tblsubgrupoproduto.codsubgrupoproduto
     , tblsubgrupoproduto.subgrupoproduto
     , tblmarca.codmarca
     , tblmarca.marca
     , tblfilial.codfilial
     , tblfilial.filial
     , tblproduto.codproduto
     , tblproduto.produto
     , tblnegocio.codnegocio
     , tblnegocio.lancamento
     , tblnegocioprodutobarra.quantidade * coalesce(tblprodutoembalagem.quantidade, 1) as quantidade
     , tblnegocioprodutobarra.valortotal
  from tblnegocio 
  left join tblnegocioprodutobarra on (tblnegocioprodutobarra.codnegocio       = tblnegocio.codnegocio)
  left join tblfilial              on (tblfilial.codfilial                     = tblnegocio.codfilial)
  left join tblprodutobarra        on (tblprodutobarra.codprodutobarra         = tblnegocioprodutobarra.codprodutobarra)
  left join tblproduto             on (tblproduto.codproduto                   = tblprodutobarra.codproduto)
  left join tblsubgrupoproduto     on (tblsubgrupoproduto.codsubgrupoproduto   = tblproduto.codsubgrupoproduto)
  left join tblgrupoproduto        on (tblgrupoproduto.codgrupoproduto         = tblsubgrupoproduto.codgrupoproduto)
  left join tblmarca               on (tblmarca.codmarca                       = coalesce(tblprodutobarra.codmarca, tblproduto.codmarca))
  left join tblprodutoembalagem    on (tblprodutoembalagem.codprodutoembalagem = tblprodutobarra.codprodutoembalagem)
 where tblnegocio.codoperacao       = 2 -- saida
   and tblnegocio.codnegociostatus  = 2 -- fechado
   and tblnegocio.lancamento       >= '2013-06-01 00:00:00.0'
   and tblnegocio.lancamento       <= '2013-07-31 23:59:59.9'
   and tblnegocio.codpessoa         not in (select tblfilial.codpessoa from tblfilial)
 