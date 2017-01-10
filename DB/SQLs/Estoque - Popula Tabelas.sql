/*
create table tblestoquemovimento_2014 as select * from tblestoquemovimento

DROP TABLE tblestoquemovimento;

drop sequence codestoquemovimento_seq;

create sequence codestoquemovimento_seq;


CREATE TABLE tblestoquemovimento
(
  codestoquemovimento bigint NOT NULL DEFAULT nextval('codestoquemovimento_seq'),
  codfilial bigint NOT NULL,
  codproduto bigint NOT NULL,
  fiscal boolean NOT NULL,
  codestoquemovimentotipo bigint NOT NULL,
  lancamento timestamp without time zone NOT NULL,
  entradaquantidade numeric(14,3),
  entradavalor numeric(14,2),
  saidaquantidade numeric(14,3),
  saidavalor numeric(14,2),
  codnegocioprodutobarra bigint,
  codnotafiscalprodutobarra bigint,
  codcupomfiscalprodutobarra bigint,
  PRIMARY KEY (codestoquemovimento ),
  FOREIGN KEY (codcupomfiscalprodutobarra)
      REFERENCES tblcupomfiscalprodutobarra (codcupomfiscalprodutobarra),
  FOREIGN KEY (codestoquemovimentotipo)
      REFERENCES tblestoquemovimentotipo (codestoquemovimentotipo),
  FOREIGN KEY (codnegocioprodutobarra)
      REFERENCES tblnegocioprodutobarra (codnegocioprodutobarra),
  FOREIGN KEY (codnotafiscalprodutobarra)
      REFERENCES tblnotafiscalprodutobarra (codnotafiscalprodutobarra),
  FOREIGN KEY (codfilial)
      REFERENCES tblfilial (codfilial) ,
  FOREIGN KEY (codproduto)
      REFERENCES tblproduto (codproduto)
      
)



-- SALDO INICIAL
insert into tblestoquemovimento (codfilial, codproduto, fiscal, codestoquemovimentotipo, lancamento, entradaquantidade, entradavalor)
  select codfilial, codproduto, fiscal, 100, '2013-12-31', saldoquantidade, saldovalor from tblestoquesaldo

-- NOTAS DE ENTRADAS
insert into tblestoquemovimento (codfilial, codproduto, fiscal, codestoquemovimentotipo, lancamento, entradaquantidade, entradavalor, codnotafiscalprodutobarra)
select 
       codfilial
     , tblprodutobarra.codproduto
     , true
     , 200
     , saida
     --, tblnotafiscalprodutobarra.quantidade
     --, tblprodutoembalagem.quantidade
     , tblnotafiscalprodutobarra.quantidade * coalesce(tblprodutoembalagem.quantidade, 1)
     , tblnotafiscalprodutobarra.valortotal
     , tblnotafiscalprodutobarra.codnotafiscalprodutobarra
  from tblnotafiscal 
 inner join tblnotafiscalprodutobarra on (tblnotafiscalprodutobarra.codnotafiscal = tblnotafiscal.codnotafiscal)
 inner join tblprodutobarra on (tblprodutobarra.codprodutobarra = tblnotafiscalprodutobarra.codprodutobarra)
  left join tblprodutoembalagem on (tblprodutoembalagem.codprodutoembalagem = tblprodutobarra.codprodutoembalagem)
 where tblnotafiscal.saida between '2014-01-01 00:00:00.0' and '2014-12-31 23:59:59.9'
   and tblnotafiscal.nfecancelamento is null
   and tblnotafiscal.codoperacao = 1

-- NOTAS DE SAIDAS
insert into tblestoquemovimento (codfilial, codproduto, fiscal, codestoquemovimentotipo, lancamento, saidaquantidade, saidavalor, codnotafiscalprodutobarra)
select 
       tblnotafiscal.codfilial
     , tblprodutobarra.codproduto
     , true
     , 200
     , saida
     , tblnotafiscalprodutobarra.quantidade * coalesce(tblprodutoembalagem.quantidade, 1)
     , tblnotafiscalprodutobarra.quantidade * coalesce(tblprodutoembalagem.quantidade, 1) * coalesce(iqprecofilial.media, iqprecoproduto.media, tblnotafiscalprodutobarra.valorunitario * 0.6, 0) as custo
     , tblnotafiscalprodutobarra.codnotafiscalprodutobarra
  from tblnotafiscal 
 inner join tblnotafiscalprodutobarra on (tblnotafiscalprodutobarra.codnotafiscal = tblnotafiscal.codnotafiscal)
 inner join tblprodutobarra on (tblprodutobarra.codprodutobarra = tblnotafiscalprodutobarra.codprodutobarra)
  left join tblprodutoembalagem on (tblprodutoembalagem.codprodutoembalagem = tblprodutobarra.codprodutoembalagem)
  left join (
		  select tblestoquemovimento.codproduto
		       , tblestoquemovimento.codfilial
		       , sum(tblestoquemovimento.entradavalor) / sum(tblestoquemovimento.entradaquantidade) as media 
		  from tblestoquemovimento 
		  group by tblestoquemovimento.codproduto
		         , tblestoquemovimento.codfilial
	    ) iqprecofilial on (iqprecofilial.codproduto = tblprodutobarra.codproduto and iqprecofilial.codfilial = tblnotafiscal.codfilial)
  left join (
		  select tblestoquemovimento.codproduto
		       , sum(tblestoquemovimento.entradavalor) / sum(tblestoquemovimento.entradaquantidade) as media 
		  from tblestoquemovimento 
		  group by tblestoquemovimento.codproduto
	    ) iqprecoproduto on (iqprecoproduto.codproduto = tblprodutobarra.codproduto)
 where tblnotafiscal.saida between '2014-01-01 00:00:00.0' and '2014-12-31 23:59:59.9'
   and tblnotafiscal.nfecancelamento is null
   and tblnotafiscal.codoperacao = 2

--CUPOM FISCAL
insert into tblestoquemovimento (codfilial, codproduto, fiscal, codestoquemovimentotipo, lancamento, saidaquantidade, saidavalor, codcupomfiscalprodutobarra)
select 
       tblecf.codfilial
     , tblprodutobarra.codproduto
     , true
     , 300
     , datamovimento
     , tblcupomfiscalprodutobarra.quantidade * coalesce(tblprodutoembalagem.quantidade, 1)
     , tblcupomfiscalprodutobarra.quantidade * coalesce(tblprodutoembalagem.quantidade, 1) * coalesce(iqprecofilial.media, iqprecoproduto.media, tblcupomfiscalprodutobarra.valorunitario * 0.6, 0) as custo
     , tblcupomfiscalprodutobarra.codcupomfiscalprodutobarra
  from tblcupomfiscal 
 inner join tblecf on (tblecf.codecf = tblcupomfiscal.codecf)
 inner join tblcupomfiscalprodutobarra on (tblcupomfiscalprodutobarra.codcupomfiscal = tblcupomfiscal.codcupomfiscal)
 inner join tblprodutobarra on (tblprodutobarra.codprodutobarra = tblcupomfiscalprodutobarra.codprodutobarra)
  left join tblprodutoembalagem on (tblprodutoembalagem.codprodutoembalagem = tblprodutobarra.codprodutoembalagem)
  left join (
		  select tblestoquemovimento.codproduto
		       , tblestoquemovimento.codfilial
		       , sum(tblestoquemovimento.entradavalor) / sum(tblestoquemovimento.entradaquantidade) as media 
		  from tblestoquemovimento 
		  group by tblestoquemovimento.codproduto
		         , tblestoquemovimento.codfilial
	    ) iqprecofilial on (iqprecofilial.codproduto = tblprodutobarra.codproduto and iqprecofilial.codfilial = tblecf.codfilial)
  left join (
		  select tblestoquemovimento.codproduto
		       , sum(tblestoquemovimento.entradavalor) / sum(tblestoquemovimento.entradaquantidade) as media 
		  from tblestoquemovimento 
		  group by tblestoquemovimento.codproduto
	    ) iqprecoproduto on (iqprecoproduto.codproduto = tblprodutobarra.codproduto)
 where tblcupomfiscal.datamovimento between '2014-01-01 00:00:00.0' and '2014-12-31 23:59:59.9'
   and tblcupomfiscal.cancelado = false
*/

select 
	  codfilial
	, grupoproduto
	, subgrupoproduto
	, tblproduto.codproduto
        , produto
        , tblproduto.preco
	, entradaquantidade
	, entradavalor
 	, saidaquantidade
from (
	select
		  tblestoquemovimento.codfilial
		, tblestoquemovimento.codproduto
		, sum(coalesce(tblestoquemovimento.entradaquantidade, 0)) as entradaquantidade
		, sum(coalesce(tblestoquemovimento.entradavalor, 0)) as entradavalor
		, sum(coalesce(tblestoquemovimento.saidaquantidade, 0)) as saidaquantidade
	  from tblestoquemovimento 
	group by tblestoquemovimento.codfilial, tblestoquemovimento.codproduto
	) iqsaldo
  left join tblproduto on (tblproduto.codproduto = iqsaldo.codproduto)
  left join tblsubgrupoproduto on (tblsubgrupoproduto.codsubgrupoproduto = tblproduto.codsubgrupoproduto)
  left join tblgrupoproduto on (tblgrupoproduto.codgrupoproduto = tblsubgrupoproduto.codgrupoproduto)
order by produto, codfilial, tblproduto.preco



select
	  tblestoquemovimento.codfilial
	, tblestoquemovimento.codproduto
	, sum(coalesce(tblestoquemovimento.entradaquantidade, 0)) as entradaquantidade
	, sum(coalesce(tblestoquemovimento.entradavalor, 0)) as entradavalor
  from tblestoquemovimento 
group by tblestoquemovimento.codfilial
, tblestoquemovimento.codproduto


