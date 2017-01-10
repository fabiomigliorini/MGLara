set FOO.VCOD = 341540
;
UPDATE TBLNOTAFISCAL SET CODNATUREZAOPERACAO = 15 where codnotafiscal = CAST(current_setting('FOO.VCOD') AS BIGINT);
DELETE from tblnotafiscalDUPLICATAS where codnotafiscal = CAST(current_setting('FOO.VCOD') AS BIGINT);
UPDATE tblnotafiscalprodutobarra SET VALORUNITARIO = ROUND(VALORUNITARIO * .7, 2) where codnotafiscal = CAST(current_setting('FOO.VCOD') AS BIGINT);
UPDATE tblnotafiscalprodutobarra SET VALORTOTAL = VALORUNITARIO * QUANTIDADE where codnotafiscal = CAST(current_setting('FOO.VCOD') AS BIGINT);

--update tblnotafiscal set emissao = '2016-07-31 18:00:00', saida = '2016-07-31 18:00:00', numero = 30038 where codnotafiscal = CAST(current_setting('FOO.VCOD') AS BIGINT);


