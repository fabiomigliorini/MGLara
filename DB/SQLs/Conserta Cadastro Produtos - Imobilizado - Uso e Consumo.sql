-- VERIFICA USO E CONSUMO COM TIPO INCORRETO
select distinct codtipoproduto from tblproduto where produto ilike '%imobilizado%'

-- VERIFICA IMOBILIZADO COM TIPO INCORRETO
select distinct codtipoproduto from tblproduto where produto ilike '%uso%consumo%'

-- VERIFICA USO E CONSUMO COM DESCRICAO INCORRETA
select * 
from tblproduto p
left join tblncm n on (n.codncm = p.codncm)
where p.codtipoproduto = 7 
and (
	(p.produto not ilike '# Uso Consumo Diversos ' || n.ncm || '%' )
	or p.referencia <> (substring(n.ncm from 1 for 4) || '.' || substring(n.ncm from 5 for 2) || '.' || substring(n.ncm from 7 for 2))
	or codsubgrupoproduto <> 18001
	or codmarca <> 10000299
	or preco <> 999999.99
	or inativo is not null
	)
order by p.produto

-- VERIFICA USO E CONSUMO COM DESCRICAO INCORRETA
select * 
from tblproduto p
left join tblncm n on (n.codncm = p.codncm)
where p.codtipoproduto = 8
and (
	(p.produto not ilike '# Imobilizado Diversos ' || n.ncm || '%' )
	or p.referencia <> (substring(n.ncm from 1 for 4) || '.' || substring(n.ncm from 5 for 2) || '.' || substring(n.ncm from 7 for 2))
	or codsubgrupoproduto <> 18001
	or codmarca <> 10000299
	or preco <> 999999.99
	or inativo is not null
	)
order by p.produto

/*
-- AJUSTA DESCRICAO IMOBILIZADO
update tblproduto
set produto = replace('# Imobilizado Diversos ' || n.ncm || ' ' || replace(replace(replace(replace(TBLPRODUTO.produto, '# Imobilizado Diversos ', ''), 'Imobilizado - Diversos - ', ''), n.ncm , ''), ' - ', ' '), '   ', ' ')
, referencia = substring(n.ncm from 1 for 4)	|| '.' || substring(n.ncm from 5 for 2)	|| '.' || substring(n.ncm from 7 for 2)
, codsubgrupoproduto = 18001
, codmarca = 10000299
, preco = 999999.99
, inativo = null
from tblncm n 
where n.codncm = tblproduto.codncm
and tblproduto.codtipoproduto = 8

-- AJUSTA DESCRICAO USO E CONSUMO
update tblproduto
set produto = replace('# Uso Consumo Diversos ' || n.ncm || ' ' || replace(replace(replace(replace(tblproduto.produto, '# Uso Consumo Diversos ', ''), 'Uso e Consumo - Diversos - ', ''), n.ncm , ''), ' - ', ' '), '   ', ' ')
, referencia = substring(n.ncm from 1 for 4)	|| '.' || substring(n.ncm from 5 for 2)	|| '.' || substring(n.ncm from 7 for 2)
, codsubgrupoproduto = 18001
, codmarca = 10000299
, preco = 999999.99
, inativo = null
from tblncm n 
where n.codncm = tblproduto.codncm
and tblproduto.codtipoproduto = 7
*/

-- PROCURA CADASTROS DUPLICADOS DE USO E CONSUMO
select p.codncm, count(*), min(p.codproduto), max(p.codproduto) 
from tblproduto p
where p.codtipoproduto = 7
group by p.codncm
having count(*) > 1

-- PROCURA CADASTROS DUPLICADOS DE IMOBILIZADO
select p.codncm, count(*), min(p.codproduto), max(p.codproduto) 
from tblproduto p
where p.codtipoproduto = 8
group by p.codncm
having count(*) > 1

-- APAGA MOVIMENTACAO DE ESTOQUE DE UM PRODUTO
/*
delete from tblproduto where codproduto = 932413
delete from tblestoquelocalproduto where codproduto = 932413
delete from tblestoquesaldo where codestoquelocalproduto = 57634
delete from tblestoquemes where codestoquesaldo = 21761
delete from tblestoquemovimento where codestoquemes = 22253
*/

-- REMOVE ESPACOS DUPLICADOS DA DESCRICAO
update tblproduto set produto = replace(produto, '  ', ' ') where produto ilike '%  %'

-- VERIFICA BARRAS
select p.codproduto, pb.barras, lpad(cast(p.codproduto as varchar), 6, '0')
from tblproduto p
left join tblprodutobarra pb on (pb.codproduto = p.codproduto)
where p.codtipoproduto in (7,8)
and lpad(cast(p.codproduto as varchar), 6, '0') <> coalesce(pb.barras, '')
