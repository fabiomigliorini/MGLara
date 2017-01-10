select 
 --   *
        tblcobranca.agendamento
      , tblpessoa.codpessoa
      , tblpessoa.pessoa
      , sum(tblcobranca.creditoacerto) as pagamento
      , sum(tbltitulo.debito) as valor
from tblcobranca
left join tbltitulo on (tbltitulo.codtitulo = tblcobranca.codtitulo)
left join tblpessoa on (tblpessoa.codpessoa = tbltitulo.codpessoa)
group by 
      tblcobranca.agendamento
    , tblpessoa.codpessoa
    , tblpessoa.pessoa
--limit 100