INSERT INTO tblmovimentotitulo(
            codtipomovimentotitulo
            , codtitulo
            , codportador
            , codtitulorelacionado
            , debito
            , credito
            , historico
            , transacao
            , sistema
            , codliquidacaotitulo
            , codtituloagrupamento
            , codboletoretorno
            , codcobranca
            , alteracao
            , codusuarioalteracao
            , criacao
            , codusuariocriacao)
select 
            00000992 as codtipomovimentotitulo
            , t.codtitulo
            , 00101001 as codportador
            , null as codtitulorelacionado
            , null as debito
            , t.saldo as credito
            , null as historico
            , t.emissao as transacao
            , t.sistema
            , null as codliquidacaotitulo
            , null as codtituloagrupamento
            , null as codboletoretorno
            , null as codcobranca
            , now() as alteracao
            , 1 as codusuarioalteracao
            , now() as criacao
            , 1 as codusuariocriacao
            /*
            */
from tbltitulo t 
where t.codpessoa  = 3555--in (select f.codpessoa from tblfilial f)
and saldo <> 0
and t.emissao <= '2015-11-30'
--and t.codtitulo = 12868
order by t.sistema asc
--limit 1
/*
select * from tblmovimentotitulo limit 10


*/