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


/* GRUPO DE PRODUTO */
Form::macro('select2GrupoProduto', function($name, $value = null, $options = [])
{
    
    if (empty($options['id']))
        $options['id'] = $name;
    
    if (empty($options['placeholder']))
        $options['placeholder'] = 'Grupo...';
    
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
                var limpaGrupoProduto = function () {
                    $('#codsubgrupoproduto').select2('val', null);
                }

                $('#codgrupoproduto').on("select2-removed", function(e) { 
                    limpaGrupoProduto
                }).change(limpaGrupoProduto);  

                $('#{$options['id']}').select2({
                    placeholder: '{$options['placeholder']}',
                    minimumInputLength: 0,
                    allowClear: {$options['allowClear']},
                    closeOnSelect: {$options['closeOnSelect']},
                    formatResult:function(item) {
                        var markup = "<div class='row-fluid'>";
                        markup    += item.grupoproduto;
                        markup    += "</div>";
                        return markup;
                    },
                    formatSelection:function(item) { 
                        return item.grupoproduto; 
                    },
                    ajax:{
                        url:baseUrl+"/grupo-produto/listagem-json",
                        dataType:'json',
                        quietMillis:500,
                        data:function(term, codfamiliaproduto, page) { 
                            return {
                                q: term,
                                codfamiliaproduto: $('#codfamiliaproduto').val()
                            }; 
                        },
                        results:function(data,page) {
                            var more = (page * 20) < data.total;
                            return {results: data.items};
                        }
                    },
                    initSelection:function (element, callback) {
                        $.ajax({
                            type: "GET",
                            url: baseUrl+"/grupo-produto/listagem-json",
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


/* SUBGRUPO DE PRODUTO */
Form::macro('select2SubGrupoProduto', function($name, $value = null, $options = [])
{
    
    if (empty($options['id']))
        $options['id'] = $name;
    
    if (empty($options['placeholder']))
        $options['placeholder'] = 'Sub Grupo...';
    
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
                    minimumInputLength: 0,
                    allowClear: {$options['allowClear']},
                    closeOnSelect: {$options['closeOnSelect']},
                    formatResult:function(item) {
                        var markup = "<div class='row-fluid'>";
                        markup    += item.subgrupoproduto;
                        markup    += "</div>";
                        return markup;
                    },
                    formatSelection:function(item) { 
                        return item.subgrupoproduto; 
                    },
                    ajax:{
                        url:baseUrl+"/sub-grupo-produto/listagem-json",
                        dataType:'json',
                        quietMillis:500,
                        data:function(term, codgrupoproduto, page) { 
                            return {
                                q: term,
                                codgrupoproduto: $('#codgrupoproduto').val()
                            }; 
                        },
                        results:function(data,page) {
                            var more = (page * 20) < data.total;
                            return {results: data.items};
                        }
                    },
                    initSelection:function (element, callback) {
                        $.ajax({
                            type: "GET",
                            url: baseUrl+"/sub-grupo-produto/listagem-json",
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


/* NCM */
Form::macro('select2Ncm', function($name, $value = null, $options = [])
{
    if (empty($options['id']))
        $options['id'] = $name;
    
    if (empty($options['placeholder']))
        $options['placeholder'] = 'Sub Grupo...';
    
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
                    formatResult:function(item) {
                        var markup = "";
                        markup    += "<b>" + item.ncm + "</b>&nbsp;";
                        markup    += "<span>" + item.descricao + "</span>";
                        return markup;
                    },
                    formatSelection:function(item) { 
                        return item.ncm + "&nbsp;" + item.descricao; 
                    },
                    ajax:{
                        url:baseUrl+"/ncm/listagem-json",
                        dataType:'json',
                        quietMillis:500,
                        data:function(term, page) { 
                            return {q: term}; 
                        },
                        results:function(data, page) {
                            var more = (page * 20) < data.total;
                            return {results: data.data};
                        }
                    },
                    initSelection:function (element, callback) {
                        $.ajax({
                            type: "GET",
                            url: baseUrl+"/ncm/listagem-json",
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

/* TRIBUTAÇÃO */
Form::macro('select2Tributacao', function($name, $selected = null, $options = [])
{
    $tributacoes = [''=>''] + MGLara\Models\Tributacao::orderBy('tributacao')->lists('tributacao', 'codtributacao')->all();
    return Form::select2($name, $tributacoes, $selected, $options);
});

/* TIPO PRODUTO */
Form::macro('select2TipoProduto', function($name, $selected = null, $options = [])
{
    $tipos = [''=>''] + MGLara\Models\TipoProduto::orderBy('tipoproduto')->lists('tipoproduto', 'codtipoproduto')->all();
    return Form::select2($name, $tipos, $selected, $options);
});

/* FILIAL */
Form::macro('select2Filial', function($name, $selected = null, $options = [])
{
    if (empty($options['placeholder'])) $options['placeholder'] = 'Filial';
    $regs = [''=>''] + MGLara\Models\Filial::orderBy('codfilial')->lists('filial', 'codfilial')->all();
    return Form::select2($name, $regs, $selected, $options);
});

/* NATUREZA OPERACAO */
Form::macro('select2NaturezaOperacao', function($name, $selected = null, $options = [])
{
    if (empty($options['placeholder'])) $options['placeholder'] = 'Natureza de Operação';
    $regs = [''=>''] + MGLara\Models\NaturezaOperacao::orderBy('naturezaoperacao')->lists('naturezaoperacao', 'codnaturezaoperacao')->all();
    return Form::select2($name, $regs, $selected, $options);
});

/* CEST */
Form::macro('select2Cest', function($name, $value = null, $options = [])
{
    if (empty($options['id']))
        $options['id'] = $name;
    
    if (empty($options['placeholder']))
        $options['placeholder'] = 'CEST...';
    
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
                        var markup = "";
                        markup    += "<b>" + item.ncm + "</b>/";
                        markup    += "<b>" + item.cest + "</b>&nbsp;";
                        markup    += "<span>" + item.descricao + "</span>";
                        return markup;
                    },
                    formatSelection: function(item) { 
                            return item.ncm + "/" + item.cest + "&nbsp;" + item.descricao; 
                    },
                    ajax:{
                        url:baseUrl+"/cest/listagem-json",
                        dataType:'json',
                        quietMillis:500,
                        data:function(codncm, page) { 
                            return {codncm: $('#codncm').val()}; 
                        },
                        results:function(data, page) {
                            var more = (page * 20) < data.total;
                            return {results: data};
                        }
                    },
                    initSelection:function (element, callback) {
                        $.ajax({
                            type: "GET",
                            url: baseUrl+"/cest/listagem-json",
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
