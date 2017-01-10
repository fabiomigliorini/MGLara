-- Function: fnListaTotaisEcfReducaoZ()

-- DROP FUNCTION fnListaTotaisEcfReducaoZPeriodo(pinicial date, pfinal date, pcodecf bigint)

CREATE OR REPLACE FUNCTION fnListaTotaisEcfReducaoZPeriodo(pinicial date, pfinal date, pcodecf bigint)
  RETURNS TABLE(codecf bigint, movimento date, crz bigint, totalcalc numeric(20,3), totalmov numeric(20,3), totalliq numeric(20,3), totalct numeric(20,3), totaldt numeric(20,3) ) AS
$BODY$
DECLARE
	/*pcodecf bigint;*/
BEGIN

	CREATE TEMPORARY TABLE tmpListaTotaisEcfReducaoZPeriodo (codecf bigint, movimento date, crz bigint, totalcalc numeric(20,3), totalmov numeric(20,3), totalliq numeric(20,3), totalct numeric(20,3), totaldt numeric(20,3) ) ON COMMIT DROP;
/*
	INSERT INTO tmpListaTotaisEcfReducaoZ 
	select 
	       'Geral' as tipo
	     , tblecfreducaoz.codecf
	     , tblecfreducaoz.movimento
	     , sum(coalesce(quantidade, 0) * coalesce(valorunitario))
	     , pdesejado - (sum(coalesce(quantidade, 0) * coalesce(valorunitario)))
	  from tblecfreducaoz
	 inner join tblcupomfiscal on (tblcupomfiscal.codecf = tblecfreducaoz.codecf and tblcupomfiscal.datamovimento = tblecfreducaoz.movimento)
	 inner join tblcupomfiscalprodutobarra on (tblcupomfiscalprodutobarra.codcupomfiscal = tblcupomfiscal.codcupomfiscal)
	 where tblecfreducaoz.movimento = pmovimento
	   and tblecfreducaoz.crz = pcrz
	 group by 
	       tblecfreducaoz.codecfreducaoz
	     , tblecfreducaoz.codecf
	     , tblecfreducaoz.movimento
	     ;

	SELECT INTO pCodEcf max(tmpListaTotaisEcfReducaoZ.codEcf) FROM tmpListaTotaisEcfReducaoZ;


	INSERT INTO tmpListaTotaisEcfReducaoZ 
	select 
	       'Cancelado' as tipo
	     , tblcupomfiscal.codecf
	     , tblcupomfiscal.datamovimento
	     , sum(coalesce(quantidade, 0) * coalesce(valorunitario))  as total
	     , null
	  from tblcupomfiscal 
	 inner join tblcupomfiscalprodutobarra on (tblcupomfiscalprodutobarra.codcupomfiscal = tblcupomfiscal.codcupomfiscal)
	 where tblcupomfiscal.datamovimento = pmovimento
	   and tblcupomfiscal.codecf = pcodecf
	   and tblcupomfiscal.cancelado = true
	 group by tblcupomfiscal.codecf
	     , tblcupomfiscal.datamovimento
	     ;

	INSERT INTO tmpListaTotaisEcfReducaoZ 
	select 
	       'Desconto' as tipo
	     , tblcupomfiscal.codecf
	     , tblcupomfiscal.datamovimento
	     , sum(descontoacrescimo)  as total
	     , null
	  from tblcupomfiscal 
	 where tblcupomfiscal.datamovimento = pmovimento
	   and tblcupomfiscal.codecf = pcodecf
	   and tblcupomfiscal.cancelado = false
	 group by tblcupomfiscal.codecf
	     , tblcupomfiscal.datamovimento
	     ;
*/
	RETURN QUERY SELECT * FROM tmpListaTotaisEcfReducaoZPeriodo;
         
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100
  ROWS 1000;

--update tblcupomfiscalprodutobarra set codcupomfiscal = 30127162 where codcupomfiscalprodutobarra = 10197882

select * from fnListaTotaisEcfReducaoZPeriodo('2013-05-01', '2013-05-31', 30101)

