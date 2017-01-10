SELECT
   tblnegocio.codnegocio
 , tblnegocio.codpessoa
 , tblnegocio.lancamento
-- , now()
-- , extract(epoch from (now() - tblnegocio.lancamento)) as segundos
 , now() - tblnegocio.lancamento
 , tblpessoa.fantasia
 , tblpessoa.pessoa
 , tblpessoa.telefone1
 , tblpessoa.telefone2
 , tblpessoa.telefone3
 , tblnegocio.codfilial
 , tblfilial.filial
 , tblfilial.codpessoa AS codpessoafilial
 , tblnegocio.codoperacao
 , tbloperacao.operacao
 , tblnegocio.codusuario
 , tblusuario.usuario
 , vwnegocioprodutobarratotais.valortotal
 , tblnegocio.valordesconto
 , vwnegocioformapagamentototais.valorpagamentoaprazo
 , coalesce(vwnegocioprodutobarratotais.valortotal, 0) - coalesce(vwnegocioformapagamentototais.valorpagamentoaprazo, 0) - coalesce(tblnegocio.valordesconto, 0) as valorpagamentoavista
  FROM tblnegocio
  LEFT JOIN tblpessoa ON tblpessoa.codpessoa = tblnegocio.codpessoa
  LEFT JOIN tblfilial ON tblfilial.codfilial = tblnegocio.codfilial
  LEFT JOIN tbloperacao ON tbloperacao.codoperacao = tblnegocio.codoperacao
  LEFT JOIN tblusuario ON tblusuario.codusuario = tblnegocio.codusuario
  LEFT JOIN vwnegocioprodutobarratotais ON vwnegocioprodutobarratotais.codnegocio = tblnegocio.codnegocio
  LEFT JOIN vwnegocioformapagamentototais ON vwnegocioformapagamentototais.codnegocio = tblnegocio.codnegocio
 WHERE tblNegocio.codNegocioStatus = 2
 and tblnegocio.entrega = true
 order by codnegocio 
 --LIMIT 5000