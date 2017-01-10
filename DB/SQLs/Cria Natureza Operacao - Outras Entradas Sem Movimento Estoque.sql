insert into tblnaturezaoperacao
(naturezaoperacao, codoperacao, emitida, 
       observacoesnf, alteracao, codusuarioalteracao, criacao, codusuariocriacao, 
       mensagemprocom, codnaturezaoperacaodevolucao, codtipotitulo, 
       codcontacontabil, finnfe, ibpt, codestoquemovimentotipo, estoque)
SELECT 'Outras Entradas S/movimento Estoque' as naturezaoperacao, codoperacao, emitida, 
       observacoesnf, now() as alteracao, 1 as codusuarioalteracao, now() as criacao, 1 as codusuariocriacao, 
       mensagemprocom, codnaturezaoperacaodevolucao, codtipotitulo, 
       codcontacontabil, finnfe, ibpt, codestoquemovimentotipo, false as estoque
  FROM tblnaturezaoperacao
where codnaturezaoperacao = 6

select * from tblnaturezaoperacao order by codnaturezaoperacao desc

insert into tbltributacaonaturezaoperacao (codtributacao, codnaturezaoperacao, 
       codcfop, icmsbase, icmspercentual, codestado, csosn, codtipoproduto, 
       acumuladordominiovista, acumuladordominioprazo, historicodominio, 
       movimentacaofisica, movimentacaocontabil, alteracao, codusuarioalteracao, 
       criacao, codusuariocriacao, ncm, icmscst, icmslpbase, icmslppercentual, 
       ipicst, piscst, pispercentual, cofinscst, cofinspercentual, csllpercentual, 
       irpjpercentual)
SELECT codtributacao, 20 as codnaturezaoperacao, 
       codcfop, icmsbase, icmspercentual, codestado, csosn, codtipoproduto, 
       acumuladordominiovista, acumuladordominioprazo, historicodominio, 
       movimentacaofisica, movimentacaocontabil, now() as alteracao, 1 as codusuarioalteracao, 
       now() as criacao, 1 as codusuariocriacao, ncm, icmscst, icmslpbase, icmslppercentual, 
       ipicst, piscst, pispercentual, cofinscst, cofinspercentual, csllpercentual, 
       irpjpercentual
  FROM tbltributacaonaturezaoperacao
where codnaturezaoperacao = 6

update tblnotafiscal
set codnaturezaoperacao = 20
where codpessoa = 10000276
and codnaturezaoperacao = 6

