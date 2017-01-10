
select 
	  t.codtitulo
	, f.filial
	, gerencial
	, cc.contacontabil
	, t.numero
	, t.fatura
	, pe.pessoa
	, t.emissao
	, t.vencimento
	, mt.debito
	, mt.credito
	, p.portador
	, mt.transacao
	, t.observacao
	, tt.tipotitulo
from tblmovimentotitulo mt
left join tbltitulo t on (t.codtitulo = mt.codtitulo)
left join tblfilial f on (f.codfilial = t.codfilial)
left join tblportador p on (p.codportador = mt.codportador)
left join tblpessoa pe on (pe.codpessoa = t.codpessoa)
left join tbltipotitulo tt on (tt.codtipotitulo = t.codtipotitulo)
left join tblcontacontabil cc on (cc.codcontacontabil = t.codcontacontabil)
left join tblliquidacaotitulo lt on (lt.codliquidacaotitulo = mt.codliquidacaotitulo)
where mt.codtipomovimentotitulo = 600
and mt.transacao between '2016-03-01 00:00:00.0' and '2016-03-31 23:59:59.9'
and tt.pagar = true
and lt.estornado is null
--and t.codfilial = 101
order by mt.transacao, p.portador, mt.debito, mt.credito
--and t.codtitulo = 61111
--limit 50