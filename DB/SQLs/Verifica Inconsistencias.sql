--titulos estornados com vinculo ao negocio
select codtitulo
from tbltitulo
where codnegocioformapagamento is not null
and estornado is not null

--conferencia titulos x forma pagamento
select tblnegocio.codnegocio, tblformapagamento.formapagamento, tblnegocioformapagamento.valorpagamento, sum(tbltitulo.debito) as total
from tblnegocio 
inner join tblnegocioformapagamento on (tblnegocioformapagamento.codnegocio = tblnegocio.codnegocio)
inner join tblformapagamento on (tblformapagamento.codformapagamento = tblnegocioformapagamento.codformapagamento)
left join tbltitulo on (tbltitulo.codnegocioformapagamento = tblnegocioformapagamento.codnegocioformapagamento and tbltitulo.estornado is null)
where tblnegocio.codnegociostatus = 2
and tblformapagamento.avista = false
and tblnegocio.codoperacao = 2 
group by
	  tblnegocio.codnegocio
	, tblformapagamento.formapagamento
	, tblnegocioformapagamento.valorpagamento
having tblnegocioformapagamento.valorpagamento <> (sum(coalesce(tbltitulo.debito, 0)))



				