select 
  tblPessoaVendedor.Pessoa
  , tblFilial.Filial
, sum(coalesce(vwnegocioprodutobarratotais.valortotal, 0) - coalesce(tblnegocio.valordesconto, 0)) as total
from tblNegocio
left join vwnegocioprodutobarratotais on (vwnegocioprodutobarratotais.codnegocio = tblnegocio.codnegocio)
left join tblPessoa as tblPessoaVendedor on (tblPessoaVendedor.codPessoa = tblNegocio.codPessoaVendedor)
left join tblFilial on (tblFilial.codFilial = tblNegocio.codFilial)
where tblNegocio.codNegocioStatus = 2
and tblNegocio.lancamento between '2012-08-26 00:00:00.0' and '2012-09-25 23:59:59.9'
and tblNegocio.codPessoa not in (select tblFilial.codPessoa from tblFilial)
group by tblPessoaVendedor.Pessoa
  , tblFilial.Filial
-- tirar intercompany
--limit 30

-- 1/2 dia eleuda
-- 1/2 Priscila
-- 1 Maicon