delete from TBLCOBRANCAHISTORICO where TBLCOBRANCAHISTORICOcodcobrancahistorico in
(
	SELECT IQ.CODCOBRANCAHISTORICO FROM TBLCOBRANCAHISTORICO IQ 
	WHERE 
	(

	  IQ.HISTORICO ILIKE '%deivismathiasinop@gmail.com.br%'
	 OR IQ.HISTORICO ILIKE '%ro_giacomelli@hotamil.com%'
	 OR IQ.HISTORICO ILIKE '%rhuan_vicente_araujo@cargil.com%'
	 OR IQ.HISTORICO ILIKE '%crecheuniao92@hotmail.com%'
	 OR IQ.HISTORICO ILIKE '%construforte.sinop@hotmail.com%'
	 OR IQ.HISTORICO ILIKE '%ivonete_giachini@hotmail.com.br%'
	 OR IQ.HISTORICO ILIKE '%financeiro3wdp@terra.om.br%'
	 OR IQ.HISTORICO ILIKE '%olvidoria@machadonet.com.br%'
	 OR IQ.HISTORICO ILIKE '%carla@groinsumosmt.com.br%'
	 OR IQ.HISTORICO ILIKE '%maisvoce_importados@hotmail.com%'
	 OR IQ.HISTORICO ILIKE '%jose.garces@sadia.com.br%'
	 OR IQ.HISTORICO ILIKE '%cpiotosieidi@hotmail.com.br%'
	 OR IQ.HISTORICO ILIKE '%sintra.postos.sinop@hotmail.com%'
	 OR IQ.HISTORICO ILIKE '%charmeperfumariafinan_@hotmail.com%'
	 OR IQ.HISTORICO ILIKE '%com.nsa.aparecida@gmail.com%'
	 OR IQ.HISTORICO ILIKE '%contamar_recpecao@hotmail.com%'
	 OR IQ.HISTORICO ILIKE '%concretossinop@concremax.com.br%'
	 OR IQ.HISTORICO ILIKE '%carlosalexandre@frialto.com.br%'
	 OR IQ.HISTORICO ILIKE '%nfee@frialto.com.br%'
	 OR IQ.HISTORICO ILIKE '%alcimarmanuel@hotmail.com%'
	 OR IQ.HISTORICO ILIKE '%grupoferro.transwood@hotmail.com.br%'
	 OR IQ.HISTORICO ILIKE '%danieli@terravivaagropecuaria.com.br%'
	 OR IQ.HISTORICO ILIKE '%autorizacao@saocamiloclinica.com.br%'


	)
	AND EMAILAUTOMATICO = TRUE
)