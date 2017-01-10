/*
INSERT INTO tblestoquemovimento(
            codestoquemovimentotipo, entradaquantidade, 
            entradavalor, saidaquantidade, saidavalor, codnegocioprodutobarra, 
            codnotafiscalprodutobarra, codestoquemes, manual, data, alteracao, 
            codusuarioalteracao, criacao, codusuariocriacao, codestoquemovimentoorigem, 
            observacoes)
select
            1002 as codestoquemovimentotipo, --ajuste
            null as entradaquantidade, 
            null as entradavalor, 
            es.saldoquantidade as saidaquantidade, 
            es.saldovalor as saidavalor, 
            null as codnegocioprodutobarra, 
            null as codnotafiscalprodutobarra, 
            em.codestoquemes, 
            true as manual, 
            '2016-12-31 23:59:59' as data, 
            now() as alteracao, 
            1 as codusuarioalteracao, 
            now() as criacao, 
            1 as codusuariocriacao, 
            null codestoquemovimentoorigem, 
            'Zerando Nao Vendaveis' as observacoes
from tblestoquesaldo es
left join tblproduto p on (p.codproduto = es.codproduto)
left join tblestoquemes em on (em.codestoquesaldo = es.codestoquesaldo and em.mes = '2015-12-01')
where es.saldoquantidade > 0
and p.codsubgrupoproduto in (18001)

select * from tblestoquemovimentotipo

update tblestoquemovimento 
set alteracao = '2016-03-11 07:21:49'
, criacao = '2016-03-11 07:21:49'
where alteracao = '2016-03-11 07:21:49.350625'
*/