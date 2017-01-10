select 
       extract(month from vwnegocio.lancamento) as mes
     , extract(  day from vwnegocio.lancamento) as dia
--     , filial
     , sum(vwnegocio.valorpagamentoavista) as avista
     , sum(vwnegocio.valorpagamentoaprazo) as aprazo 
  from vwnegocio 
 where vwnegocio.codnegociostatus = 2 
   and vwnegocio.codoperacao = 2
   and vwnegocio.codpessoa not in (select tblfilial.codpessoa from tblfilial)
   and vwnegocio.lancamento >= '2013-01-01 00:00:00.0'  
--   and vwnegocio.lancamento <= '2013-02-28 23:59:59.9'  
 group by 
       extract(month from vwnegocio.lancamento)
     , extract(  day from vwnegocio.lancamento)
--     , filial
order by 1 asc, 2 asc
--limit 10