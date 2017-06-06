select 'wget http://192.168.1.205/MGLara/estoque/calcula-custo-medio/' || cast(em.codestoquemes as varchar), em.codestoquemovimento, em.*, nfpb.*
from tblnotafiscalprodutobarra nfpb
inner join tblestoquemovimento em on em.codnotafiscalprodutobarra = nfpb.codnotafiscalprodutobarra
where codnotafiscal = 516350

/*
delete from tblestoquemovimento where codestoquemovimento in 
(3964163
)
*/