select usuario, sum(valorpagamentoavista) as pagamentoavista, sum(valortroco) as troco, sum(valorpagamentoavista) - sum(valortroco) as saldo
from vwnegocio
where codnegociostatus = 2
and lancamento >= '2011-05-13 00:00:00'
and lancamento <= '2011-05-13 23:59:00'
group by usuario


--select * from vwnegocio
