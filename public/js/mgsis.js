function formataCodigo(numero)
{
    if (numero > 99999999)
        return numero;

    numero = new String("00000000" + numero);
    numero = numero.substring(numero.length-8, numero.length);
    return numero;
}

function formataCnpjCpf(numero)
{
    //CNPJ
    if (numero > 99999999999)
    {
        numero = new String("00000000000000" + numero);
        numero = numero.substring(numero.length-14, numero.length);
        // 01 234 567 8901 23
        // 04.576.775/0001-60
        numero = numero.substring(0, 2) 
                 + "."
                 + numero.substring(2, 5)
                 + "."
                 + numero.substring(5, 8)
                 + "/"
                 + numero.substring(8, 12)
                 + "-"
                 + numero.substring(12, 14)
                 ;
    }
    //CPF
    else
    {
        numero = "000000000000" + numero;
        numero = numero.substring(numero.length-11, numero.length);
        // 012 345 678 90
        // 123 456 789 01
        // 803.452.710.68
        numero = numero.substring(0, 3) 
                 + "."
                 + numero.substring(3, 6)
                 + "."
                 + numero.substring(6, 9)
                 + "-"
                 + numero.substring(9, 11)
                 ;
    }

    return numero;
}

function excluirClick (tag)
{
    var url = $(tag).attr('href');
    var pergunta = $(tag).data('pergunta');
    var funcaoAfterDelete = $(tag).data('after-delete');
    var funcaoOnError = $(tag).data('on-error');
    
    pergunta = (typeof pergunta === 'undefined') ? 'Tem certeza que deseja excluir o registro?' : pergunta;
    
    bootbox.confirm('<strong>' + pergunta + '</strong>', function(result) {
        
        if (result) 
        {
            $.ajax({
                type: 'POST',
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                dataType: 'json',
                success: function(retorno) {
                    
                    if (retorno.resultado)
                    {
                        var mensagem = '<strong class="text-success">' + retorno.mensagem + '</strong>';
                        var funcaoExecutar = funcaoAfterDelete;
                    }
                    else
                    {
                        var mensagem = '<strong class="text-danger">' + retorno.mensagem + '</strong>';
                        mensagem += '<hr><pre>';
                        mensagem += JSON.stringify(retorno, undefined, 2);
                        mensagem += '</pre>';
                        var funcaoExecutar = funcaoOnError;
                    }
                    
                    bootbox.alert(mensagem, function (){
                        if (typeof funcaoExecutar !== 'undefined')
                            eval(funcaoExecutar);
                    });
                    
                    console.log(retorno);
                    
                },
                error: function (XHR, textStatus) {
                    bootbox.alert('<strong class="text-danger">Erro ao excluir o registro!</strong> <hr><span class="text-muted">Verifique o LOG do Console...</span>');
                    console.log(XHR);
                    console.log(textStatus);
                }
            });
        }
        
    });
    
    return true;
}

function recarregaDiv(div, url)
{
    if(url === undefined) {
        url = $(location).attr('href');
    };

    if (url.indexOf("?") == -1)
        url += '?';
    else
        url += '&';
    
    url += '_div=' + div + ' #' + div + ' > *';

    $('#' + div).load(url, function (){
        inicializa('#' + div + ' *');
    });
}

function inicializa(elemento)
{
    $(elemento).find('a[data-excluir]').click(function(event) {
        event.preventDefault();
        return excluirClick($(this));
    });
}

$(document).ready(function() {
    
    inicializa('*');
    
    $('#deleteId').on("submit", function(e) {
        var currentForm = this;
        e.preventDefault();
        bootbox.confirm("Tem certeza que deseja excluir?", function(result) {
            if (result) {
                currentForm.submit();
            }
        });
    });    
    
    $('.pagination').addClass('hide');
  
    var loading_options = {
        finishedMsg: "<div class='end-msg'>Fim dos registros</div>",
        msgText: "<div class='center'>Carregando mais itens...</div>",
        img: baseUrl + '/public/img/ajax-loader.gif'
    };

    $('#items').infinitescroll({
        loading : loading_options,
        navSelector : "#registros .pagination",
        nextSelector : "#registros .pagination li.active + li a",
        itemSelector : "#items div.list-group-item"
    });
    
});  



