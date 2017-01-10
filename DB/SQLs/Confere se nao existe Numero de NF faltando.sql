select nf.codfilial, nf.serie, nf.modelo, nf.numero, nf.numero -1
from tblnotafiscal nf
left join tblnotafiscal nfa 
	on (nfa.codfilial = nf.codfilial 
	AND nfa.serie = nf.serie
	AND nfa.modelo = nf.modelo
	AND nfa.numero = (nf.numero-1)
	AND nfa.emitida = true)
where nf.emissao between '2014-06-01' and '2014-06-30'
and nf.emitida = true
and nfa.codnotafiscal is null
