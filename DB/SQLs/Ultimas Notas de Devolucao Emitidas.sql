
/*
select * 
from tblregulamentoicmsstmt 
where ncmexceto is not null limit 50

select * from tblibptax where codigo = '48202000'

5487;"48202000";"";0;32.09;48.69
*/

select nf.codfilial, nf.emissao, nf.nfedataautorizacao, p.pessoa 
from tblnotafiscal nf
left join tblpessoa p on (p.codpessoa = nf.codpessoa)
where codnaturezaoperacao = 2
order by nf.criacao desc nulls last
limit 20

--select * from tblnaturezaoperacao