
select * from (
	-- MESMO NCM/SUBGRUPO/MARCA/LOCAL
	select 11 as prioridade, es.codestoquesaldo, p.codproduto, p.produto, p.preco, es.codestoquelocal, es.saldoquantidade
	from tblestoquesaldo es 
	inner join tblproduto p on (p.codproduto = es.codproduto)
	inner join 
		(
		select es_orig.codestoquelocal, es_orig.fiscal, es_orig.codproduto, p_orig.codncm, p_orig.preco, p_orig.codsubgrupoproduto, p_orig.codmarca
		from tblestoquesaldo es_orig
		inner join tblproduto p_orig on (p_orig.codproduto = es_orig.codproduto)
		where es_orig.codestoquesaldo = 1684
		) orig on 
		(
		p.codncm = orig.codncm
		and p.preco between (orig.preco * .80) and  (orig.preco * 1.20) 
		AND p.codsubgrupoproduto = orig.codsubgrupoproduto
		and p.codmarca = orig.codmarca
		AND ES.codestoquelocal = orig.codestoquelocal 
		)
	where es.saldoquantidade > 0
	union 
	-- MESMO NCM/SUBGRUPO/MARCA NA SINOPEL
	select 21 as prioridade, es.codestoquesaldo, p.codproduto, p.produto, p.preco, es.codestoquelocal, es.saldoquantidade
	from tblestoquesaldo es 
	inner join tblproduto p on (p.codproduto = es.codproduto)
	inner join 
		(
		select es_orig.codestoquelocal, es_orig.fiscal, es_orig.codproduto, p_orig.codncm, p_orig.preco, p_orig.codsubgrupoproduto, p_orig.codmarca
		from tblestoquesaldo es_orig
		inner join tblproduto p_orig on (p_orig.codproduto = es_orig.codproduto)
		where es_orig.codestoquesaldo = 1684
		) orig on 
		(
		p.codncm = orig.codncm
		and p.preco between (orig.preco * .80) and  (orig.preco * 1.20) 
		AND p.codsubgrupoproduto = orig.codsubgrupoproduto
		and p.codmarca = orig.codmarca
		AND ES.codestoquelocal = 301001
		)
	where es.saldoquantidade > 0
	union 
	-- MESMO NCM NA SINOPEL
	select 22 as prioridade, es.codestoquesaldo, p.codproduto, p.produto, p.preco, es.codestoquelocal, es.saldoquantidade
	from tblestoquesaldo es 
	inner join tblproduto p on (p.codproduto = es.codproduto)
	inner join 
		(
		select es_orig.codestoquelocal, es_orig.fiscal, es_orig.codproduto, p_orig.codncm, p_orig.preco, p_orig.codsubgrupoproduto, p_orig.codmarca
		from tblestoquesaldo es_orig
		inner join tblproduto p_orig on (p_orig.codproduto = es_orig.codproduto)
		where es_orig.codestoquesaldo = 1684
		) orig on 
		(
		p.codncm = orig.codncm
		and p.preco between (orig.preco * .80) and  (orig.preco * 1.20) 
		--AND p.codsubgrupoproduto = orig.codsubgrupoproduto
		--and p.codmarca = orig.codmarca
		AND ES.codestoquelocal = 301001
		)
	where es.saldoquantidade > 0
	union 
	-- MESMO NCM/SUBGRUPO/MARCA NA FDF
	select 31 as prioridade, es.codestoquesaldo, p.codproduto, p.produto, p.preco, es.codestoquelocal, es.saldoquantidade
	from tblestoquesaldo es 
	inner join tblproduto p on (p.codproduto = es.codproduto)
	inner join 
		(
		select es_orig.codestoquelocal, es_orig.fiscal, es_orig.codproduto, p_orig.codncm, p_orig.preco, p_orig.codsubgrupoproduto, p_orig.codmarca
		from tblestoquesaldo es_orig
		inner join tblproduto p_orig on (p_orig.codproduto = es_orig.codproduto)
		where es_orig.codestoquesaldo = 1684
		) orig on 
		(
		p.codncm = orig.codncm
		and p.preco between (orig.preco * .80) and  (orig.preco * 1.20) 
		AND p.codsubgrupoproduto = orig.codsubgrupoproduto
		and p.codmarca = orig.codmarca
		AND ES.codestoquelocal = 201001
		)
	where es.saldoquantidade > 0
	union 
	-- MESMO NCM NA FDF
	select 32 as prioridade, es.codestoquesaldo, p.codproduto, p.produto, p.preco, es.codestoquelocal, es.saldoquantidade
	from tblestoquesaldo es 
	inner join tblproduto p on (p.codproduto = es.codproduto)
	inner join 
		(
		select es_orig.codestoquelocal, es_orig.fiscal, es_orig.codproduto, p_orig.codncm, p_orig.preco, p_orig.codsubgrupoproduto, p_orig.codmarca
		from tblestoquesaldo es_orig
		inner join tblproduto p_orig on (p_orig.codproduto = es_orig.codproduto)
		where es_orig.codestoquesaldo = 1684
		) orig on 
		(
		p.codncm = orig.codncm
		and p.preco between (orig.preco * .80) and  (orig.preco * 1.20) 
		--AND p.codsubgrupoproduto = orig.codsubgrupoproduto
		--and p.codmarca = orig.codmarca
		AND ES.codestoquelocal = 201001
		)
	where es.saldoquantidade > 0
	union
	-- MESMO NCM/SUBGRUPO/MARCA NAS MIGLIORINIS
	select 41 as prioridade, es.codestoquesaldo, p.codproduto, p.produto, p.preco, es.codestoquelocal, es.saldoquantidade
	from tblestoquesaldo es 
	inner join tblproduto p on (p.codproduto = es.codproduto)
	inner join 
		(
		select es_orig.codestoquelocal, es_orig.fiscal, es_orig.codproduto, p_orig.codncm, p_orig.preco, p_orig.codsubgrupoproduto, p_orig.codmarca
		from tblestoquesaldo es_orig
		inner join tblproduto p_orig on (p_orig.codproduto = es_orig.codproduto)
		where es_orig.codestoquesaldo = 1684
		) orig on 
		(
		p.codncm = orig.codncm
		and p.preco between (orig.preco * .80) and  (orig.preco * 1.20) 
		AND p.codsubgrupoproduto = orig.codsubgrupoproduto
		and p.codmarca = orig.codmarca
		AND ES.codestoquelocal not in (301001, 201001)
		)
	where es.saldoquantidade > 0
	union
	-- MESMO NCM/SUBGRUPO 
	select 51 as prioridade, es.codestoquesaldo, p.codproduto, p.produto, p.preco, es.codestoquelocal, es.saldoquantidade
	from tblestoquesaldo es 
	inner join tblproduto p on (p.codproduto = es.codproduto)
	inner join 
		(
		select es_orig.codestoquelocal, es_orig.fiscal, es_orig.codproduto, p_orig.codncm, p_orig.preco, p_orig.codsubgrupoproduto, p_orig.codmarca
		from tblestoquesaldo es_orig
		inner join tblproduto p_orig on (p_orig.codproduto = es_orig.codproduto)
		where es_orig.codestoquesaldo = 1684
		) orig on 
		(
		p.codncm = orig.codncm
		and p.preco between (orig.preco * .80) and  (orig.preco * 1.20) 
		AND p.codsubgrupoproduto = orig.codsubgrupoproduto
		--and p.codmarca = orig.codmarca
		--AND ES.codestoquelocal = 301001
		)
	where es.saldoquantidade > 0
	) iq2
order by iq2.prioridade, iq2.codestoquelocal desc