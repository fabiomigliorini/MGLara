select n.naturezaoperacao, t.tributacao, tp.tipoproduto, e.sigla, tn.ncm, tn.codcfop--, tn.*
from tbltributacaonaturezaoperacao tn
left join tblnaturezaoperacao n on (n.codnaturezaoperacao = tn.codnaturezaoperacao)
left join tbltributacao t on (t.codtributacao = tn.codtributacao)
left join tblestado e on (e.codestado = tn.codestado)
left join tbltipoproduto tp on (tp.codtipoproduto = tn.codtipoproduto)
where icmscst is null 
or icmscst = 0
or ipicst is null
or ipicst = 0
or piscst is null
or piscst = 0
or cofinscst is null
or cofinscst = 0
order by n.naturezaoperacao, t.tributacao, tp.tipoproduto, codcfop, e.sigla