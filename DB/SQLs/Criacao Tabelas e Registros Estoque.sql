delete from tblestoquesaldo

delete from tblestoquemovimento

select * from tblestoquemovimentotipo

select * from tblnaturezaoperacao order by codoperacao, codnaturezaoperacao


select * from tblestoquemovimentotipo

INSERT INTO tblestoquemovimentotipo (codestoquemovimentotipo, descricao, sigla) values (1001, 'Saldo Inicial', 'SLD');
INSERT INTO tblestoquemovimentotipo (codestoquemovimentotipo, descricao, sigla) values (1002, 'Ajuste', 'AJU');

INSERT INTO tblestoquemovimentotipo (codestoquemovimentotipo, descricao, sigla) values (2001, 'Compra', 'CMP');
UPDATE tblnaturezaoperacao SET codestoquemovimentotipo = 2001 WHERE codnaturezaoperacao = 4; --"Compra"

INSERT INTO tblestoquemovimentotipo (codestoquemovimentotipo, descricao, sigla) values (2002, 'Devolucao de Compra', 'DVC');
UPDATE tblnaturezaoperacao SET codestoquemovimentotipo = 2002 WHERE codnaturezaoperacao = 3; --"Devolucao de Compra"

INSERT INTO tblestoquemovimentotipo (codestoquemovimentotipo, descricao, sigla) values (2101, 'Outras Entradas', 'OUE');
UPDATE tblnaturezaoperacao SET codestoquemovimentotipo = 2101 WHERE codnaturezaoperacao = 6; --"Outras Entradas"

INSERT INTO tblestoquemovimentotipo (codestoquemovimentotipo, descricao, sigla) values (2102, 'Entrada Bonificacao, Doacao, Brinde', 'BRI');
UPDATE tblnaturezaoperacao SET codestoquemovimentotipo = 2102 WHERE codnaturezaoperacao = 8; --"Entrada Bonificacao, Doacao, Brinde"

INSERT INTO tblestoquemovimentotipo (codestoquemovimentotipo, descricao, sigla) values (2103, 'Entrada Simples Lancamento', 'ESL');
UPDATE tblnaturezaoperacao SET codestoquemovimentotipo = 2103 WHERE codnaturezaoperacao = 14; --"Simples Lancamento"

INSERT INTO tblestoquemovimentotipo (codestoquemovimentotipo, descricao, sigla) values (2201, 'Entrada de Comodato', 'ECO');
UPDATE tblnaturezaoperacao SET codestoquemovimentotipo = 2201 WHERE codnaturezaoperacao = 7; --"Entrada de Comodato"

INSERT INTO tblestoquemovimentotipo (codestoquemovimentotipo, descricao, sigla) values (3001, 'Venda', 'VDA');
UPDATE tblnaturezaoperacao SET codestoquemovimentotipo = 3001 WHERE codnaturezaoperacao = 1; --"Venda"
UPDATE tblnaturezaoperacao SET codestoquemovimentotipo = 3001 WHERE codnaturezaoperacao = 5; --"Venda C/cupom Fiscal"

INSERT INTO tblestoquemovimentotipo (codestoquemovimentotipo, descricao, sigla) values (3101, 'Saida Complemento', 'SCO');
UPDATE tblnaturezaoperacao SET codestoquemovimentotipo = 3101 WHERE codnaturezaoperacao = 13; --"Nfe Complementar"

INSERT INTO tblestoquemovimentotipo (codestoquemovimentotipo, descricao, sigla) values (3002, 'Devolucao de Venda', 'DVV');
UPDATE tblnaturezaoperacao SET codestoquemovimentotipo = 3002 WHERE codnaturezaoperacao = 2; --"Devolucao de Venda"

INSERT INTO tblestoquemovimentotipo (codestoquemovimentotipo, descricao, sigla) values (3102, 'Saida Bonificacao, Doacao, Brinde', 'SBR');
UPDATE tblnaturezaoperacao SET codestoquemovimentotipo = 3102 WHERE codnaturezaoperacao = 17; --"Saida Bonificacao, Doacao, Brinde"

INSERT INTO tblestoquemovimentotipo (codestoquemovimentotipo, descricao, sigla) values (3103, 'Saida Perda, Roubo, Deterioracao', 'SPE');
UPDATE tblnaturezaoperacao SET codestoquemovimentotipo = 3103 WHERE codnaturezaoperacao = 18; --"Saida Perda, Roubo, Deterioracao"

INSERT INTO tblestoquemovimentotipo (codestoquemovimentotipo, descricao, sigla) values (3104, 'Saida Uso e Consumo', 'SUS');
UPDATE tblnaturezaoperacao SET codestoquemovimentotipo = 3104 WHERE codnaturezaoperacao = 19; --"Saida Uso e Consumo"

INSERT INTO tblestoquemovimentotipo (codestoquemovimentotipo, descricao, sigla) values (3201, 'Remessa Para Conserto', 'RCO');
UPDATE tblnaturezaoperacao SET codestoquemovimentotipo = 3201 WHERE codnaturezaoperacao = 9; --"Remessa Para Conserto"

INSERT INTO tblestoquemovimentotipo (codestoquemovimentotipo, descricao, sigla) values (3202, 'Remessa Para Analise', 'RAN');
UPDATE tblnaturezaoperacao SET codestoquemovimentotipo = 3202 WHERE codnaturezaoperacao = 10; --"Remessa Para Analise"

INSERT INTO tblestoquemovimentotipo (codestoquemovimentotipo, descricao, sigla) values (3203, 'Remessa Para Troca', 'RTR');
UPDATE tblnaturezaoperacao SET codestoquemovimentotipo = 3203 WHERE codnaturezaoperacao = 11; --"Remessa Para Troca"

INSERT INTO tblestoquemovimentotipo (codestoquemovimentotipo, descricao, sigla) values (3204, 'Remessa em Garantia', 'RGR');
UPDATE tblnaturezaoperacao SET codestoquemovimentotipo = 3204 WHERE codnaturezaoperacao = 12; --"Remessa em Garantia"

INSERT INTO tblestoquemovimentotipo (codestoquemovimentotipo, descricao, sigla) values (4101, 'Transferencia Saida', 'TRS');
UPDATE tblnaturezaoperacao SET codestoquemovimentotipo = 4101 WHERE codnaturezaoperacao = 15; --"Transferencia Saida"

INSERT INTO tblestoquemovimentotipo (codestoquemovimentotipo, descricao, sigla) values (4201, 'Transferencia Entrada', 'TRS');
UPDATE tblnaturezaoperacao SET codestoquemovimentotipo = 4201 WHERE codnaturezaoperacao = 16; --"Transferencia Entrada"

select nat.codnaturezaoperacao, nat.naturezaoperacao, nat.codoperacao, emt.codestoquemovimentotipo, emt.sigla, emt.descricao
from tblnaturezaoperacao nat
left join tblestoquemovimentotipo emt on (emt.codestoquemovimentotipo = nat.codestoquemovimentotipo)
order by codoperacao, descricao

insert into tblestoquelocal (codestoquelocal, estoquelocal, codfilial) select (codfilial * 1000) + 1, filial, codfilial from tblfilial

--update tblnegocio set codestoquelocal = 101001 where codestoquelocal is null and codfilial = 101
--update tblnegocio set codestoquelocal = 102001 where codestoquelocal is null and codfilial = 102
--update tblnegocio set codestoquelocal = 103001 where codestoquelocal is null and codfilial = 103
--update tblnegocio set codestoquelocal = 104001 where codestoquelocal is null and codfilial = 104
--update tblnegocio set codestoquelocal = 201001 where codestoquelocal is null and codfilial = 201
--update tblnegocio set codestoquelocal = 202001 where codestoquelocal is null and codfilial = 202
--update tblnegocio set codestoquelocal = 301001 where codestoquelocal is null and codfilial = 301

update tblnegocio set codestoquelocal = (codfilial * 1000) + 1 where codestoquelocal is null

--update tblnotafiscal set codestoquelocal = 101001 where codestoquelocal is null and codfilial = 101
--update tblnotafiscal set codestoquelocal = 201001 where codestoquelocal is null and codfilial = 201
--update tblnotafiscal set codestoquelocal = 202001 where codestoquelocal is null and codfilial = 202
--update tblnotafiscal set codestoquelocal = 102001 where codestoquelocal is null and codfilial = 102
--update tblnotafiscal set codestoquelocal = 103001 where codestoquelocal is null and codfilial = 103
--update tblnotafiscal set codestoquelocal = 104001 where codestoquelocal is null and codfilial = 104
--update tblnotafiscal set codestoquelocal = 301001 where codestoquelocal is null and codfilial = 301

update tblnotafiscal set codestoquelocal = (codfilial * 1000) + 1 where codestoquelocal is null


