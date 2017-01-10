delete from tblauditoriatransmissao 
where tblauditoriatransmissao.codauditoria <= 
	(select max(tblauditoria.codauditoria) 
	   from tblauditoria 
	  where data <= '2014-02-03 23:59:59.9');

delete from tblauditoria
where data <= '2014-02-03 23:59:59.9';

/*
select * from tblauditoriatransmissao limit 5

begin transaction;

delete from tblauditoriatransmissao where codauditoria <= 10247000;
delete from tblauditoria where codauditoria <= 10247000;

commit;

*/