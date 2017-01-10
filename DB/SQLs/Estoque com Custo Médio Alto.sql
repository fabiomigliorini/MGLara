select codestoquesaldo, (select mes.codestoquemes from tblestoquemes mes where mes.codestoquesaldo = es.codestoquesaldo order by mes desc limit 1), saldovalor / coalesce(saldoquantidade, 1)
from tblestoquesaldo es
where saldovalor / coalesce(saldoquantidade, 1) > 1000
and saldoquantidade !=0

/*
update tblestoquemovimento
set saidavalor = null, entradavalor = null
where codestoquemes = 264694
and codestoquemovimentotipo in (select t.codestoquemovimentotipo from tblestoquemovimentotipo t where preco != 1)
*/