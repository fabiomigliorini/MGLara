select * from tblnotafiscal where codfilial = 301 and serie = 1 and modelo = 65 and emitida = true and numero between 111876 and 111977

/*
update tblnotafiscal 
set emissao = current_date
, saida = current_date 
, alteracao = (current_timestamp - interval '60 seconds')
where codfilial = 301 and serie = 1 and modelo = 65 and emitida = true and numero between 111876 and 111977

*/