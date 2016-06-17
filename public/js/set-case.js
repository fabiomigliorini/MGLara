(function($) {

    $.fn.RemoveAcentos = function(settings) {
        this.each(function() {
            $(this).blur(function() {
                var currVal = $(this).val();

                //remove aspas
                currVal = currVal.replace(/"/g, " ");
                currVal = currVal.replace(/'/g, " ");

                //limpa espaço em branco no início e final
                currVal = currVal.trim();

                //remove espaço duplicado
                currVal = currVal.replace(/\s{2,}/g, ' ');

                //remove hífen duplicado
                currVal = currVal.replace(/(\-)\1+/gi, "-");

                //remove acentos
                currVal = currVal.replace(/[á|ã|â|à|ª]/g, "a");
                currVal = currVal.replace(/[Á|Ã|Â|À]/g, "A");
                currVal = currVal.replace(/[é|ẽ|ê|è]/g, "e");
                currVal = currVal.replace(/[É|Ẽ|Ê|È]/g, "E");
                currVal = currVal.replace(/[í|ĩ|î|ì]/g, "i");
                currVal = currVal.replace(/[Í|Ĩ|Î|Ì]/g, "I");
                currVal = currVal.replace(/[ó|õ|ô|ò|º]/g, "o");
                currVal = currVal.replace(/[Ó|Õ|Ô|Ò]/g, "O");
                currVal = currVal.replace(/[ú|ũ|û|ù]/g, "u");
                currVal = currVal.replace(/[Ú|Ũ|Û|Ù]/g, "U");
                currVal = currVal.replace(/[ĉ|ç]/g, "c");
                currVal = currVal.replace(/[Ĉ|Ç]/g, "C");
                currVal = currVal.replace(/[ń|ñ|ǹ]/gi, "n");
                currVal = currVal.replace(/[Ń|Ñ|Ǹ]/gi, "N");

                $(this).val(currVal);

            });
        });
    }

    //Create plugin named Setcase
    $.fn.Setcase = function(settings) {

        // Defaults
        var config = {
        caseValue: 'pascal',
        };

        //Merge settings
        if(settings) $.extend(config, settings);

        this.each(function() {

            //blur event		
            $(this).blur(function() {

                //$(this).RemoveAcentos(); <-- AQUI QUE NÃO ESTÁ CARREGANDO
                var currVal = $(this).val();

                /* REMOVENDO TODOS OS ACENTOS */
                    currVal = currVal.replace(/"/g, " ");
                    currVal = currVal.replace(/'/g, " ");
                    currVal = currVal.trim();
                    currVal = currVal.replace(/\s{2,}/g, ' ');
                    currVal = currVal.replace(/(\-)\1+/gi, "-");
                    currVal = currVal.replace(/[á|ã|â|à|ª]/g, "a");
                    currVal = currVal.replace(/[Á|Ã|Â|À]/g, "A");
                    currVal = currVal.replace(/[é|ẽ|ê|è]/g, "e");
                    currVal = currVal.replace(/[É|Ẽ|Ê|È]/g, "E");
                    currVal = currVal.replace(/[í|ĩ|î|ì]/g, "i");
                    currVal = currVal.replace(/[Í|Ĩ|Î|Ì]/g, "I");
                    currVal = currVal.replace(/[ó|õ|ô|ò|º]/g, "o");
                    currVal = currVal.replace(/[Ó|Õ|Ô|Ò]/g, "O");
                    currVal = currVal.replace(/[ú|ũ|û|ù]/g, "u");
                    currVal = currVal.replace(/[Ú|Ũ|Û|Ù]/g, "U");
                    currVal = currVal.replace(/[ĉ|ç]/g, "c");
                    currVal = currVal.replace(/[Ĉ|Ç]/g, "C");
                    currVal = currVal.replace(/[ń|ñ|ǹ]/gi, "n");
                    currVal = currVal.replace(/[Ń|Ñ|Ǹ]/gi, "N");
                /* --------------- */

                //monta o case conforme o parametro recebido
                if(config.caseValue == "upper")
                {
                    currVal = currVal.toUpperCase();
                }
                else if(config.caseValue == "lower")
                {
                    currVal = currVal.toLowerCase();
                }
                else if(config.caseValue == "title")
                {
                    currVal = currVal.charAt(0).toUpperCase() + currVal.slice(1).toLowerCase();
                }
                else if(config.caseValue == "pascal")
                {

                    //deixa tudo como "Pascal Case"
                    var i, lowers, uppers;
                    currVal = currVal.replace(/([^\W_]+[^\s-]*) */g, function(txtVal) {
                            return txtVal.charAt(0).toUpperCase() + txtVal.substr(1).toLowerCase();
                    });

                    //palavras que devem ficar em minúsculo
                    lowers = ['De', 'Da', 'Do', 'Dos', 'E', 'Em'];
                    for (i = 0; i < lowers.length; i++)
                        currVal = currVal.replace(new RegExp('\\s' + lowers[i] + '\\s', 'g'), 
                            function(txt) 
                            {
                                return txt.toLowerCase();
                            });

                    //palavras que devem ficar em maiúsculo
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