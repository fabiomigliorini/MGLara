
CREATE TABLE mgsis.tblprodutovariacao ( 
	codprodutovariacao   bigserial  NOT NULL,
	codproduto           bigint  NOT NULL,
	variacao             varchar(100)  ,
	referencia           varchar(50)  ,
	codmarca             bigint  ,
	alteracao            timestamp  ,
	codusuarioalteracao  bigint  ,
	criacao              timestamp  ,
	codusuariocriacao    bigint  ,
	CONSTRAINT pk_tblprodutovariacao PRIMARY KEY ( codprodutovariacao ),
	CONSTRAINT idx_tblprodutovariacao UNIQUE ( codproduto, variacao, codmarca ) 
 );

CREATE INDEX idx_tblprodutovariacao_0 ON mgsis.tblprodutovariacao ( codmarca );

CREATE INDEX idx_tblprodutovariacao_1 ON mgsis.tblprodutovariacao ( codusuarioalteracao );

CREATE INDEX idx_tblprodutovariacao_2 ON mgsis.tblprodutovariacao ( codusuariocriacao );

CREATE INDEX idx_tblprodutovariacao_3 ON mgsis.tblprodutovariacao ( codproduto );

ALTER TABLE mgsis.tblprodutovariacao ADD CONSTRAINT fk_tblprodutovariacao FOREIGN KEY ( codmarca ) REFERENCES mgsis.tblmarca( codmarca );

ALTER TABLE mgsis.tblprodutovariacao ADD CONSTRAINT fk_tblprodutovariacao_0 FOREIGN KEY ( codusuarioalteracao ) REFERENCES mgsis.tblusuario( codusuario );

ALTER TABLE mgsis.tblprodutovariacao ADD CONSTRAINT fk_tblprodutovariacao_1 FOREIGN KEY ( codusuariocriacao ) REFERENCES mgsis.tblusuario( codusuario );

ALTER TABLE mgsis.tblprodutovariacao ADD CONSTRAINT fk_tblprodutovariacao_2 FOREIGN KEY ( codproduto ) REFERENCES mgsis.tblproduto( codproduto );


insert into tblprodutovariacao (codproduto, codmarca, variacao, referencia, codusuariocriacao, criacao, codusuarioalteracao, alteracao)
select codproduto, codmarca, variacao, max(referencia), max(codusuariocriacao), max(criacao), max(codusuarioalteracao), max(alteracao) from tblprodutobarra
group by codproduto, codmarca, variacao

ALTER TABLE mgsis.tblprodutobarra ADD codprodutovariacao bigint;
CREATE INDEX idx_tblprodutobarra ON mgsis.tblprodutobarra ( codprodutovariacao );
ALTER TABLE mgsis.tblprodutobarra ADD CONSTRAINT fk_tblprodutobarra FOREIGN KEY ( codprodutovariacao ) REFERENCES mgsis.tblprodutovariacao( codprodutovariacao );

update tblprodutobarra
set codprodutovariacao = pv.codprodutovariacao
from tblprodutovariacao pv
where pv.codproduto = tblprodutobarra.codproduto
and coalesce(pv.codmarca, 0) = coalesce(tblprodutobarra.codmarca, 0)
and coalesce(pv.variacao, '') = coalesce(tblprodutobarra.variacao, '')

alter table tblprodutobarra alter column codprodutovariacao set not null

alter table tblestoquelocalproduto add	codprodutovariacao   bigint;

update tblestoquelocalproduto 
set codprodutovariacao = (select min(pv.codprodutovariacao) from tblprodutovariacao pv where pv.codproduto = tblestoquelocalproduto.codproduto)

insert into tblprodutovariacao (codproduto)
select elp.codproduto from tblestoquelocalproduto elp where elp.codprodutovariacao is null

update tblestoquelocalproduto 
set codprodutovariacao = (select min(pv.codprodutovariacao) from tblprodutovariacao pv where pv.codproduto = tblestoquelocalproduto.codproduto)
where codprodutovariacao is null

alter table tblestoquelocalproduto alter column codprodutovariacao set not null

ALTER TABLE tblestoquelocalproduto RENAME TO tblestoquelocalprodutovariacao;

select * 
from tblestoquelocalprodutovariacao elpv
left join tblprodutovariacao pv on (pv.codprodutovariacao = elpv.codprodutovariacao)
where elpv.codproduto <> pv.codproduto

alter table tblestoquelocalprodutovariacao drop column codproduto;

alter table tblestoquelocalprodutovariacao rename column codestoquelocalproduto to codestoquelocalprodutovariacao;

alter table tblestoquesaldo rename column codestoquelocalproduto to codestoquelocalprodutovariacao;

ALTER TABLE mgsis.tblestoquesaldo ADD ultimaconferencia timestamp;

-- VINDO ZERADO APAGAR CODPRODUTO da tblestoquelocalprodutovariacao

--update tblproduto
/*
select count(*) from tblprodutobarra where codprodutovariacao is null

select pb.codproduto, pv.codproduto, pb.codmarca, pv.codmarca, pb.variacao, pv.variacao, pb.referencia, pv.referencia
from tblprodutobarra pb
inner join tblprodutovariacao pv on (pv.codprodutovariacao = pb.codprodutovariacao)
where pb.variacao is not null
limit 10000

                        select pe.codproduto
                        from tblprodutoembalagem pe
                        inner join tblproduto p on (p.codproduto = pe.codproduto)
                        where coalesce(pe.preco, pe.quantidade * p.preco) >= 158.00
                        or p.preco >= 158.00
*/


select * from tbljobs where payload ilike '%99310%' limit 50 

delete from tbljobs where payload ilike '%CalculaCustoMedio%99310%' 

select alteracao from tblnegocioprodutobarra where codnegocioprodutobarra = 1313078


update tblprodutobarra
set variacao = null
where tblprodutobarra.variacao is not null
and tblprodutobarra.variacao = (select pv.variacao from tblprodutovariacao pv where pv.codprodutovariacao = tblprodutobarra.codprodutovariacao)



update tblprodutobarra
set referencia = null
where tblprodutobarra.referencia is not null
and tblprodutobarra.referencia = (select pv.referencia from tblprodutovariacao pv where pv.codprodutovariacao = tblprodutobarra.codprodutovariacao)


