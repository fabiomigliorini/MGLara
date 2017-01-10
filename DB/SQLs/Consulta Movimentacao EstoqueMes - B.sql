select em.codestoquemes, es.codestoquelocal, count(codestoquemovimento)
from tblestoquesaldo es
left join tblestoquemes em on (em.codestoquesaldo = es.codestoquesaldo)
left join tblestoquemovimento emov on (emov.codestoquemes = em.codestoquemes)
where es.codproduto =76
group by em.codestoquemes , es.codestoquelocal
order by 3 desc
--having count(codestoquemovimento) > 1