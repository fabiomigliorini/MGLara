/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var baseUrl = '/MGLara';


/* bootstrapSwitch */
//$.fn.bootstrapSwitch.defaults.size = 'large';
//$.fn.bootstrapSwitch.defaults.onColor = 'success';
//$("input[type=\"checkbox\"], input[type=\"radio\"]").not("[data-switch-no-init]").bootstrapSwitch();


(function ($) {
    $.fn.select2.defaults.set("theme", "classic");
    $.datepicker.setDefaults($.datepicker.regional["pt-BR"]);    
});



(function ($) {
    //Create plugin named Setcase
    $.fn.Setcase = function (settings) {

        // Defaults
        var config = {
            caseValue: 'pascal',
        };

        //Merge settings
        if (settings)
            $.extend(config, settings);

        this.each(function () {

            //blur event		
            $(this).blur(function () {

                var currVal = $(this).val();

                //remove aspas
                currVal = currVal.replace(/"/g, " ");
                currVal = currVal.replace(/'/g, " ");

                //limpa espaÃ§o em branco no inÃ­cio e final
                currVal = currVal.trim();

                //remove espaÃ§o duplicado
                currVal = currVal.replace(/\s{2,}/g, ' ');

                //remove hÃ­fen duplicado
                currVal = currVal.replace(/(\-)\1+/gi, "-");

                //remove acentos
                currVal = currVal.replace(/[Ã¡|Ã£|Ã¢|Ã ]/g, "a");
                currVal = currVal.replace(/[Ã|Ãƒ|Ã‚|Ã€]/g, "A");
                currVal = currVal.replace(/[Ã©|áº½|Ãª|Ã¨]/g, "e");
                currVal = currVal.replace(/[Ã‰|áº¼|ÃŠ|Ãˆ]/g, "E");
                currVal = currVal.replace(/[Ã­|Ä©|Ã®|Ã¬]/g, "i");
                currVal = currVal.replace(/[Ã|Ä¨|ÃŽ|ÃŒ]/g, "I");
                currVal = currVal.replace(/[Ã³|Ãµ|Ã´|Ã²]/g, "o");
                currVal = currVal.replace(/[Ã“|Ã•|Ã”|Ã’]/g, "O");
                currVal = currVal.replace(/[Ãº|Å©|Ã»|Ã¹]/g, "u");
                currVal = currVal.replace(/[Ãš|Å¨|Ã›|Ã™]/g, "U");
                currVal = currVal.replace(/[Ä‰|Ã§]/g, "c");
                currVal = currVal.replace(/[Äˆ|Ã‡]/g, "C");
                currVal = currVal.replace(/[Å„|Ã±|Ç¹]/gi, "n");
                currVal = currVal.replace(/[Åƒ|Ã‘|Ç¸]/gi, "N");

                //monta o case conforme o parametro recebido
                if (config.caseValue == "upper")
                {
                    currVal = currVal.toUpperCase();
                } else if (config.caseValue == "lower")
                {
                    currVal = currVal.toLowerCase();
                } else if (config.caseValue == "title")
                {
                    currVal = currVal.charAt(0).toUpperCase() + currVal.slice(1).toLowerCase();
                } else if (config.caseValue == "pascal")
                {

                    //deixa tudo como "Pascal Case"
                    var i, lowers, uppers;
                    currVal = currVal.replace(/([^\W_]+[^\s-]*) */g, function (txtVal) {
                        return txtVal.charAt(0).toUpperCase() + txtVal.substr(1).toLowerCase();
                    });

                    //palavras que devem ficar em minÃºsculo
                    lowers = ['De', 'Da', 'Do', 'Dos', 'E', 'Em'];
                    for (i = 0; i < lowers.length; i++)
                        currVal = currVal.replace(new RegExp('\\s' + lowers[i] + '\\s', 'g'),
                                function (txt)
                                {
                                    return txt.toLowerCase();
                                });

                    //palavras que devem ficar em maiÃºsculo
                    uppers = ['Cdce', 'Sa', 'S/a'];
                    for (i = 0; i < uppers.length; i++)
                        currVal = currVal.replace(new RegExp('\\b' + uppers[i] + '\\b', 'g'),
                                uppers[i].toUpperCase());


                }

                //altera o valor do campo
                $(this).val(currVal);
            });
        });
    };
})(jQuery);
/*
(function ($) {
    "use strict";

    $.extend($.fn.select2.defaults, {
        formatNoMatches: function () { return "Nenhum resultado encontrado"; },
        formatInputTooShort: function (input, min) { var n = min - input.length; return "Informe " + n + " caracter" + (n == 1? "" : "es"); },
        formatInputTooLong: function (input, max) { var n = input.length - max; return "Apague " + n + " caracter" + (n == 1? "" : "es"); },
        formatSelectionTooBig: function (limit) { return "SÃ³ Ã© possÃ­vel selecionar " + limit + " elemento" + (limit == 1 ? "" : "s"); },
        formatLoadMore: function (pageNumber) { return "Carregando mais resultados..."; },
        formatSearching: function () { return "Buscando..."; }
    });
})(jQuery);
*/
/**
 * Portuguese translation for bootstrap-datepicker
 * Original code: Cauan Cabral <cauan@radig.com.br>
 * Tiago Melo <tiago.blackcode@gmail.com>
 */
;
/*
(function ($) {
    $.fn.datepicker.dates['pt'] = {
        days: ["Domingo", "Segunda", "TerÃ§a", "Quarta", "Quinta", "Sexta", "SÃ¡bado", "Domingo"],
        daysShort: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "SÃ¡b", "Dom"],
        daysMin: ["Do", "Se", "Te", "Qu", "Qu", "Se", "Sa", "Do"],
        months: ["Janeiro", "Fevereiro", "MarÃ§o", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],
        monthsShort: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
        today: "Hoje",
        clear: "Limpar"
    };
}(jQuery));
*/