INSERT INTO tblnegocioprodutobarra(
            codnegocio, quantidade, valorunitario, 
            valortotal, codprodutobarra, alteracao, codusuarioalteracao, 
            criacao, codusuariocriacao)
select
	00546090,
	quantidade,
	valorunitario,
	valortotal,
	codprodutobarra,
	alteracao,
	codusuarioalteracao,
	criacao,
	codusuariocriacao
from tblnotafiscalprodutobarra 
where codnotafiscal = 406715

update tblnotafiscalprodutobarra
set codnegocioprodutobarra = (
	select n.codnegocioprodutobarra
	from tblnegocioprodutobarra n
	where n.codnegocio = 546090
	and n.codprodutobarra = tblnotafiscalprodutobarra.codprodutobarra
	and n.quantidade = tblnotafiscalprodutobarra.quantidade
	and n.valortotal = tblnotafiscalprodutobarra.valortotal
)
where codnotafiscal = 406715