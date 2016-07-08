<?php
//use Blade;

use Collective\Html\FormFacade;


Form::macro('select2marca', function($name, $value = null, $options = [])
{
    
    if (empty($options['id']))
        $options['id'] = $name;
    
    $script = <<< END
  
        <script type="text/javascript">
            $(document).ready(function() {
                $('#{$options['id']}').select2({
                    placeholder:'Marca',
                    minimumInputLength: 1,
                    allowClear: true,
                    closeOnSelect: true,

                    formatResult: function(item) {
                        var markup = "<div class='row-fluid'>";
                        markup    += item.marca;
                        markup    += "</div>";
                        return markup;
                    },
                    formatSelection: function(item) { 
                        return item.marca; 
                    },
                    ajax:{
                        url: baseUrl + "/marca/listagem-json",
                        dataType: 'json',
                        quietMillis: 500,
                        data: function(term,page) { 
                        return {q: term}; 
                    },
                    results: function(data,page) {
                        var more = (page * 20) < data.total;
                        return {results: data.items};
                    }},
                    initSelection: function (element, callback) {
                        $.ajax({
                          type: "GET",
                          url: baseUrl + "/marca/listagem-json",
                          data: "id="+$('#{$options['id']}').val(),
                          dataType: "json",
                          success: function(result) { callback(result); }
                          });
                    },
                    width: 'resolve'
                }); 
            });            
        </script>
END;

    
    $campo = Form::text($name, null, $options);
    
    return $campo . $script;
});

