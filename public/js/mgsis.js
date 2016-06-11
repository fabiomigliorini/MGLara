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
    
$(document).ready(function() {
    
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

