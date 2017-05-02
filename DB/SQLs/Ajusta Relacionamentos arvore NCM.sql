/*
select substring(ncm from 1 for 7), ncm
from tblncm
where char_length(ncm) = 8

select * from tblncm where codncmpai is null
*/

update tblncm set codncmpai = null;

update tblncm 
set codncmpai = (select n2.codncm from tblncm n2 where n2.ncm = substring(tblncm.ncm from 1 for 7) and char_length(n2.ncm) = 7)
where codncmpai is null
and char_length(ncm) > 7;

update tblncm 
set codncmpai = (select n2.codncm from tblncm n2 where n2.ncm = substring(tblncm.ncm from 1 for 6) and char_length(n2.ncm) = 6)
where codncmpai is null
and char_length(ncm) > 6;

update tblncm 
set codncmpai = (select n2.codncm from tblncm n2 where n2.ncm = substring(tblncm.ncm from 1 for 5) and char_length(n2.ncm) = 5)
where codncmpai is null
and char_length(ncm) > 5;

update tblncm 
set codncmpai = (select n2.codncm from tblncm n2 where n2.ncm = substring(tblncm.ncm from 1 for 4) and char_length(n2.ncm) = 4)
where codncmpai is null
and char_length(ncm) > 4;

update tblncm 
set codncmpai = (select n2.codncm from tblncm n2 where n2.ncm = substring(tblncm.ncm from 1 for 3) and char_length(n2.ncm) = 3)
where codncmpai is null
and char_length(ncm) > 3;

update tblncm 
set codncmpai = (select n2.codncm from tblncm n2 where n2.ncm = substring(tblncm.ncm from 1 for 2) and char_length(n2.ncm) = 2)
where codncmpai is null
and char_length(ncm) > 2;

update tblncm 
set codncmpai = (select n2.codncm from tblncm n2 where n2.ncm = substring(tblncm.ncm from 1 for 1) and char_length(n2.ncm) = 1)
where codncmpai is null
and char_length(ncm) > 1;
