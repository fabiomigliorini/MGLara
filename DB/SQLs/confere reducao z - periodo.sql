select 
       tblecfreducaoz.codecf 
     , tblecfreducaoz.movimento 
     , tblecfreducaoz.crz 
     , tblecfreducaoz.grandetotal
     , tblecfreducaozanterior.grandetotal as grandetotalanterior
     , (tblecfreducaoz.grandetotal - coalesce(tblecfreducaozanterior.grandetotal, 0)) as grandetotaldiferenca
     , (tblecfreducaoz.grandetotal - coalesce(tblecfreducaozanterior.grandetotal, 0)) - iqtotal.total as Diferenca
     , iqtotal.total
     , iqdesc.desc
     , iqtotal.canc
  from tblecfreducaoz 
  left join tblecfreducaoz as tblecfreducaozanterior on (tblecfreducaozanterior.codecf = tblecfreducaoz.codecf and tblecfreducaozanterior.crz = (tblecfreducaoz.crz - 1)) 
  left join (
		select
		       tblcupomfiscal.codecf
		     , tblcupomfiscal.datamovimento
		     , round(sum(coalesce(quantidade, 0) * coalesce(valorunitario)), 2) as total
		     , round(sum(case when (tblcupomfiscal.cancelado = true) 
		                      then coalesce(quantidade, 0) * coalesce(valorunitario) 
		                      else 0 end), 2) as canc

		  from tblcupomfiscal 
		 inner join tblcupomfiscalprodutobarra on (tblcupomfiscalprodutobarra.codcupomfiscal = tblcupomfiscal.codcupomfiscal)
		 group by tblcupomfiscal.codecf
			, tblcupomfiscal.datamovimento
  
		) as iqtotal on (iqtotal.codecf = tblecfreducaoz.codecf and iqtotal.datamovimento = tblecfreducaoz.movimento)
  left join (
		select
		       tblcupomfiscal.codecf
		     , tblcupomfiscal.datamovimento
		     , round(sum(tblcupomfiscal.descontoacrescimo), 2) as desc
		  from tblcupomfiscal 
		 where tblcupomfiscal.cancelado = false
		 group by tblcupomfiscal.codecf
			, tblcupomfiscal.datamovimento
		) as iqdesc on (iqdesc.codecf = tblecfreducaoz.codecf and iqdesc.datamovimento = tblecfreducaoz.movimento)
 where tblecfreducaoz.movimento between '2014-06-01' and '2014-06-30'
   --and tblecfreducaoz.codecf in (30104)
   --and tblecfreducaoz.codecf = 30103
   --and tblecfreducaoz.crz = 22
   --and ((tblecfreducaoz.grandetotal - coalesce(tblecfreducaozanterior.grandetotal, 0)) - iqtotal.total) <> 0
 order by tblecfreducaoz.codecf asc, tblecfreducaoz.crz desc

 --select * from tblcupomfiscal where codecf  = 30104 order by codcupomfiscal desc


