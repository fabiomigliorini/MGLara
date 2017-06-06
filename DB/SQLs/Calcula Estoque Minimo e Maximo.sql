-- Inicializa
update tblestoquelocalprodutovariacao
set estoqueminimo = null
, estoquemaximo = null
where estoqueminimo is not null
or estoquemaximo is not null

-- Calcula Lojas
update tblestoquelocalprodutovariacao
set estoqueminimo = ceil(vendadiaquantidadeprevisao * 15) -- 15 dias
, estoquemaximo = ceil(vendadiaquantidadeprevisao * 60) -- 60 dias
where codestoquelocal != 101001

-- Calcula Deposito pela venda das lojas
update tblestoquelocalprodutovariacao
set estoqueminimo = ceil(iq.vendadiaquantidadeprevisao * 15) -- 15 dias
, estoquemaximo = ceil(iq.vendadiaquantidadeprevisao * 60) -- 60 dias
from (
	select elpv_iq.codprodutovariacao, sum(coalesce(elpv_iq.vendadiaquantidadeprevisao, 0)) as vendadiaquantidadeprevisao
	from tblestoquelocalprodutovariacao elpv_iq
	where elpv_iq.codestoquelocal != 101001 -- deposito
	group by elpv_iq.codprodutovariacao
	) iq
where tblestoquelocalprodutovariacao.codprodutovariacao = iq.codprodutovariacao
and tblestoquelocalprodutovariacao.codestoquelocal = 101001

-- Coloca estoque maximo como dobro do minimo quando maximo igual a minimo
update tblestoquelocalprodutovariacao
set estoquemaximo = estoqueminimo * 2
where estoquemaximo <= estoqueminimo

