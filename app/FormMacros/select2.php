<?php
//use Blade;

use Collective\Html\FormFacade;

Form::macro('select2', function($name, $list = [], $selected = null, $options = [])
{
    
    if (empty($options['id']))
        $options['id'] = $name;
    
    if (empty($options['placeholder']))
        $options['placeholder'] = 'Selecione...';
    
    if (empty($options['allowClear']))
        $options['allowClear'] = true;
    $options['allowClear'] = ($options['allowClear'])?'true':'false';
    
    if (empty($options['closeOnSelect']))
        $options['closeOnSelect'] = true;
    $options['closeOnSelect'] = ($options['closeOnSelect'])?'true':'false';
    
    $script = <<< END
  
        <script type="text/javascript">
            $(document).ready(function() {
                $('#{$options['id']}').select2({
                    placeholder: '{$options['placeholder']}',
                    allowClear: {$options['allowClear']},
                    closeOnSelect: {$options['closeOnSelect']}
                });
            });            
        </script>
END;
    
    unset($options['placeholder']);
                    
    $campo = Form::select($name, $list, $selected, $options);
            
    return $campo . $script;
});

Form::macro('select2Marca', function($name, $value = null, $options = [])
{
    
    if (empty($options['id']))
        $options['id'] = $name;
    
    if (empty($options['placeholder']))
        $options['placeholder'] = 'Marca...';
    
    if (empty($options['allowClear']))
        $options['allowClear'] = true;
    $options['allowClear'] = ($options['allowClear'])?'true':'false';
    
    if (empty($options['closeOnSelect']))
        $options['closeOnSelect'] = true;
    $options['closeOnSelect'] = ($options['closeOnSelect'])?'true':'false';
    
    if (empty($options['somenteAtivos']))
        $options['somenteAtivos'] = true;
    $options['somenteAtivos'] = ($options['somenteAtivos'])?'true':'false';
    
    $script = <<< END
  
        <script type="text/javascript">
            $(document).ready(function() {
                $('#{$options['id']}').select2({
                    placeholder: '{$options['placeholder']}',
                    minimumInputLength: 1,
                    allowClear: {$options['allowClear']},
                    closeOnSelect: {$options['closeOnSelect']},

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

    $campo = Form::text($name, $value, $options);
    
    return $campo . $script;
});

/* UNIDADES DE MEDIDA */
Form::macro('select2UnidadeMedida', function($name, $selected = null, $options = [])
{
    if (empty($options['campo']))
        $options['campo'] = 'sigla';
    $medidas = [''=>''] + MGLara\Models\UnidadeMedida::orderBy('unidademedida')->lists($options['campo'], 'codunidademedida')->all();
    return Form::select2($name, $medidas, $selected, $options);
});

/* SEÇÃO DE PRODUTO */
Form::macro('select2SecaoProduto', function($name, $selected = null, $options = [])
{
    $secoes = [''=>''] + MGLara\Models\SecaoProduto::orderBy('secaoproduto')->lists('secaoproduto', 'codsecaoproduto')->all();
    $campo = Form::select2($name, $secoes, $selected, $options);
    $script = <<< END
        <script type="text/javascript">
            $(document).ready(function() {
                var limpaSecaoProduto = function(){
                    $('#codfamiliaproduto').select2('val', null);
                    $('#codgrupoproduto').select2('val', null);
                    $('#codsubgrupoproduto').select2('val', null);        
                }
                $("#codsecaoproduto").on("select2-removed", function(e) {
                    limpaSecaoProduto;
                }).change(limpaSecaoProduto);
            });            
        </script>
END;

    return $campo . $script;    
});


/* FAMÍLIA DE PRODUTO */
Form::macro('select2FamiliaProduto', function($name, $value = null, $options = [])
{
    
    if (empty($options['id']))
        $options['id'] = $name;
    
    if (empty($options['placeholder']))
        $options['placeholder'] = 'Família...';
    
    if (empty($options['allowClear']))
        $options['allowClear'] = true;
    $options['allowClear'] = ($options['allowClear'])?'true':'false';
    
    if (empty($options['closeOnSelect']))
        $options['closeOnSelect'] = true;
    $options['closeOnSelect'] = ($options['closeOnSelect'])?'true':'false';
    
    if (empty($options['somenteAtivos']))
        $options['somenteAtivos'] = true;
    $options['somenteAtivos'] = ($options['somenteAtivos'])?'true':'false';
    
    $script = <<< END
  
        <script type="text/javascript">
            $(document).ready(function() {

                var limpaFamiliaProduto = function(){
                    $('#codgrupoproduto').select2('val', null);
                    $('#codsubgrupoproduto').select2('val', null);        
                }

                $("#codfamiliaproduto").on("select2-removed", function(e) {
                    limpaFamiliaProduto
                }).change(limpaFamiliaProduto);


                $('#{$options['id']}').select2({
                    placeholder: '{$options['placeholder']}',
                    minimumInputLength: 0,
                    allowClear: {$options['allowClear']},
                    closeOnSelect: {$options['closeOnSelect']},
                    formatResult:function(item) {
                        var markup = "<div class='row-fluid'>";
                        markup    += item.familiaproduto;
                        markup    += "</div>";
                        return markup;
                    },
                    formatSelection:function(item) { 
                        return item.familiaproduto; 
                    },
                    ajax:{
                        url:baseUrl+"/familia-produto/listagem-json",
                        dataType:'json',
                        quietMillis:500,
                        data:function(term, codsecaoproduto, page) { 
                            return {
                                q: term,
                                codsecaoproduto: $('#codsecaoproduto').val()
                            }; 
                            console.log($('#codsecaoproduto').val());
                        },
                        results:function(data,page) {
                            var more = (page * 20) < data.total;
                            return {results: data.items};
                        }
                    },
                    initSelection:function (element, callback) {
                        $.ajax({
                            type: "GET",
                            url: baseUrl+"/familia-produto/listagem-json",
                            data: "id="+$('#{$options['id']}').val(),
                            dataType: "json",
                            success: function(result) { callback(result); }
                        });
                    },
                    width:'resolve'
                });
            });
        </script>
END;

    $campo = Form::text($name, $value, $options);
    
    return $campo . $script;
});
