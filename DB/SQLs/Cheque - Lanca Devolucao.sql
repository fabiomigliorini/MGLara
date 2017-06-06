select * from tblchequerepassecheque where codcheque = 8388

select * from tblchequedevolucao where codchequerepassecheque = 15593

insert into tblchequedevolucao (codchequerepassecheque, codchequemotivodevolucao, data, observacoes) values (15593, 7, '2017-03-06', 'Repassado para Fernanda Migliorini cobrar')

update tblcheque set indstatus = 3, observacao = 'Repassado para Fernanda Migliorini cobrar' where codcheque = 8388