select 
	numeracao.codfilial 
	, numeracao.modelo 
	, numeracao.serie 
	, numeracao.numero
	, nf.codnotafiscal
from
(
	select min_max.codfilial, min_max.modelo, min_max.serie, generate_series as numero
	from (
		select max.*, 
			(
				select min(nf2.numero) 
				from tblnotafiscal nf2 
				where nf2.codfilial = max.codfilial 
				and nf2.modelo = max.modelo
				and nf2.serie = max.serie
				and nf2.emitida = true
				and nf2.numero > 0
				and nf2.emissao >= '2012-01-01 00:00:00'
			) as primeiro
		from  (
			SELECT 101 as codfilial, 55 as modelo, 1 as serie, s2.last_value as ultimo FROM tblnotafiscal_numero_101_1_55_seq s2 union 
			select 102 as codfilial, 55 as modelo, 1 as serie, s2.last_value as ultimo from tblnotafiscal_numero_102_1_55_seq s2 union 
			select 103 as codfilial, 55 as modelo, 1 as serie, s2.last_value as ultimo from tblnotafiscal_numero_103_1_55_seq s2 union 
			select 104 as codfilial, 55 as modelo, 1 as serie, s2.last_value as ultimo from tblnotafiscal_numero_104_1_55_seq s2 union 
			select 201 as codfilial, 55 as modelo, 1 as serie, s2.last_value as ultimo from tblnotafiscal_numero_201_1_55_seq s2 union 
			select 301 as codfilial, 55 as modelo, 1 as serie, s2.last_value as ultimo from tblnotafiscal_numero_301_1_55_seq s2 union 
			select 101 as codfilial, 65 as modelo, 1 as serie, s2.last_value as ultimo from tblnotafiscal_numero_101_1_65_seq s2 union 
			select 102 as codfilial, 65 as modelo, 1 as serie, s2.last_value as ultimo from tblnotafiscal_numero_102_1_65_seq s2 union 
			select 103 as codfilial, 65 as modelo, 1 as serie, s2.last_value as ultimo from tblnotafiscal_numero_103_1_65_seq s2 union 
			select 104 as codfilial, 65 as modelo, 1 as serie, s2.last_value as ultimo from tblnotafiscal_numero_104_1_65_seq s2 union
			select 201 as codfilial, 65 as modelo, 1 as serie, s2.last_value as ultimo from tblnotafiscal_numero_201_1_65_seq s2 union 
			select 301 as codfilial, 65 as modelo, 1 as serie, s2.last_value as ultimo from tblnotafiscal_numero_301_1_65_seq s2 
			) max
	) min_max
	, generate_series (min_max.primeiro, min_max.ultimo)
) numeracao
left join tblnotafiscal nf on (nf.codfilial = numeracao.codfilial and nf.modelo = numeracao.modelo and nf.serie = numeracao.serie and nf.numero = numeracao.numero)
where nf.codnotafiscal is null
order by 
	numeracao.codfilial 
	, numeracao.modelo 
	, numeracao.serie 
	, numeracao.numero


select 
	nf.codfilial 
	, nf.modelo 
	, nf.serie 
	, nf.numero
	, count(*) as qtd
from tblnotafiscal nf
where nf.emitida = true
and nf.numero <> 0
and nf.emissao >= '2010-01-01 00:00:00'
group by 
	nf.codfilial 
	, nf.modelo 
	, nf.serie 
	, nf.numero
having count(*) > 1

select * from tblnotafiscal where codnotafiscal in (3919, 328770)
