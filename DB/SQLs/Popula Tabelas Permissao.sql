INSERT INTO tblgrupousuario (codgrupousuario, grupousuario, observacoes, alteracao, codusuarioalteracao, criacao, codusuariocriacao) VALUES (1, 'Administrador', 'Administrador do sistema', NULL, NULL, NULL, NULL);
INSERT INTO tblgrupousuario (codgrupousuario, grupousuario, observacoes, alteracao, codusuarioalteracao, criacao, codusuariocriacao) VALUES (2, 'Estoquista', 'Estoquista', NULL, NULL, NULL, NULL);
INSERT INTO tblgrupousuario (codgrupousuario, grupousuario, observacoes, alteracao, codusuarioalteracao, criacao, codusuariocriacao) VALUES (3, 'Financeiro', 'Financeiro', NULL, NULL, NULL, NULL);

INSERT INTO tblgrupousuariousuario (codgrupousuario, codusuario, codfilial) 
select 1, u.codusuario, f.codfilial
from tblusuario u
inner join tblfilial f on (1=1)
where u.inativo is null
and u.usuario in ('luciano', 'fabio')

INSERT INTO tblpermissao (codpermissao, permissao, observacoes, alteracao, codusuarioalteracao, criacao, codusuariocriacao) VALUES (1001001, 'usuario.consulta', 'Usuário / consultar', NULL, NULL, NULL, NULL);
INSERT INTO tblpermissao (codpermissao, permissao, observacoes, alteracao, codusuarioalteracao, criacao, codusuariocriacao) VALUES (1001101, 'usuario.inclusao', 'Usuário / incluir', NULL, NULL, NULL, NULL);
INSERT INTO tblpermissao (codpermissao, permissao, observacoes, alteracao, codusuarioalteracao, criacao, codusuariocriacao) VALUES (1001201, 'usuario.alteracao', 'Usuário / alterar', NULL, NULL, NULL, NULL);
INSERT INTO tblpermissao (codpermissao, permissao, observacoes, alteracao, codusuarioalteracao, criacao, codusuariocriacao) VALUES (1001301, 'usuario.exclusao', 'Usuário / excluir', NULL, NULL, NULL, NULL);
INSERT INTO tblpermissao (codpermissao, permissao, observacoes, alteracao, codusuarioalteracao, criacao, codusuariocriacao) VALUES (1002001, 'grupousuario.consulta', 'Grupo de usuário / consultar', NULL, NULL, NULL, NULL);
INSERT INTO tblpermissao (codpermissao, permissao, observacoes, alteracao, codusuarioalteracao, criacao, codusuariocriacao) VALUES (1002101, 'grupousuario.inclusao', 'Grupo de usuário / incluir', NULL, NULL, NULL, NULL);
INSERT INTO tblpermissao (codpermissao, permissao, observacoes, alteracao, codusuarioalteracao, criacao, codusuariocriacao) VALUES (1002201, 'grupousuario.alteracao', 'Grupo de usuário / alterar', NULL, NULL, NULL, NULL);
INSERT INTO tblpermissao (codpermissao, permissao, observacoes, alteracao, codusuarioalteracao, criacao, codusuariocriacao) VALUES (1002301, 'grupousuario.exclusao', 'Grupo de usuário / excluir', NULL, NULL, NULL, NULL);
INSERT INTO tblpermissao (codpermissao, permissao, observacoes, alteracao, codusuarioalteracao, criacao, codusuariocriacao) VALUES (1003001, 'permissao.consulta', 'Permissao / consultar', NULL, NULL, NULL, NULL);
INSERT INTO tblpermissao (codpermissao, permissao, observacoes, alteracao, codusuarioalteracao, criacao, codusuariocriacao) VALUES (1003101, 'permissao.inclusao', 'Permissao / incluir', NULL, NULL, NULL, NULL);
INSERT INTO tblpermissao (codpermissao, permissao, observacoes, alteracao, codusuarioalteracao, criacao, codusuariocriacao) VALUES (1003201, 'permissao.alteracao', 'Permissao / alterar', NULL, NULL, NULL, NULL);
INSERT INTO tblpermissao (codpermissao, permissao, observacoes, alteracao, codusuarioalteracao, criacao, codusuariocriacao) VALUES (1003301, 'permissao.exclusao', 'Permissao / excluir', NULL, NULL, NULL, NULL);


INSERT INTO tblgrupousuariopermissao (codgrupousuario, codpermissao) 
select 1, p.codpermissao
from tblpermissao p
where p.codpermissao not in (select gup.codpermissao from tblgrupousuariopermissao gup where gup.codgrupousuario = 1)
