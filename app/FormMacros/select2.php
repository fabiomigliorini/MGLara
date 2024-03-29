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

    if (empty($options['ativo']))
        $options['ativo'] = 1;

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
                        return {
                            q: term,
                            ativo: {$options['ativo']}
                        };
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

/* UNIDADES USUÁRIO */
Form::macro('select2Usuario', function($name, $selected = null, $options = [])
{
    $usuarios = [''=>''] + MGLara\Models\Usuario::orderBy('usuario')->lists('usuario', 'codusuario')->all();
    return Form::select2($name, $usuarios, $selected, $options);
});

/* GRUPO CLIENTE */
Form::macro('select2GrupoCliente', function($name, $selected = null, $options = [])
{
    $grupos = [''=>''] + MGLara\Models\GrupoCliente::orderBy('grupocliente')->lists('grupocliente', 'codgrupocliente')->all();
    return Form::select2($name, $grupos, $selected, $options);
});

/* ATIVO */
Form::macro('select2Ativo', function($name, $selected = null, $options = [])
{
    $opcoes = ['' => '', 1 => 'Ativos', 2 => 'Inativos'];
    $options['placeholder'] = 'Ativos';
    return Form::select2($name, $opcoes, $selected, $options);
});

/* PRODUTO VARIAÇÃO */
Form::macro('select2ProdutoVariacao', function($name, $value = null, $options = [])
{
    if (empty($options['id']))
        $options['id'] = $name;

    if (empty($options['placeholder']))
        $options['placeholder'] = 'Variação...';

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
                    minimumInputLength: 0,
                    allowClear: {$options['allowClear']},
                    closeOnSelect: {$options['closeOnSelect']},

                    formatResult: function(item) {
                        var markup = "<div class='row-fluid'>";
                        markup    += item.variacao;
                        markup    += "</div>";
                        return markup;
                    },
                    formatSelection: function(item) {
                        return item.variacao;
                    },
                    ajax:{
                        url: baseUrl + "/produto-variacao/listagem-json",
                        dataType: 'json',
                        quietMillis: 500,
                        data: function(term,page) {
                        return {
                            q: term,
                            codproduto: $('#{$options['codproduto']}').val()
                        };
                    },
                    results: function(data,page) {
                        var more = (page * 20) < data.total;
                        return {results: data};
                    }},
                    initSelection: function (element, callback) {
                        $.ajax({
                          type: "GET",
                          url: baseUrl + "/produto-variacao/listagem-json",
                          data: "id="+$('#{$options['id']}').val(),
                          dataType: "json",
                          success: function(result) { callback(result); }
                        });
                    },
                    width: 'resolve'
                });
                $('#{$options['codproduto']}').change(function () {
                    $('#{$options['id']}').select2('val', '');
                });
            });
        </script>
END;

    $campo = Form::text($name, $value, $options);

    return $campo . $script;
});



/* SEÇÃO DE PRODUTO */
Form::macro('select2SecaoProduto', function($name, $selected = null, $options = [])
{
    if (empty($options['placeholder']))
        $options['placeholder'] = 'Seção...';

    $secoes = [''=>''] + MGLara\Models\SecaoProduto::orderBy('secaoproduto')->lists('secaoproduto', 'codsecaoproduto')->all();
    $campo = Form::select2($name, $secoes, $selected, $options);
    return $campo;
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

    if (empty($options['ativo']))
        $options['ativo'] = 1;

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
                                ativo: {$options['ativo']},
                                codsecaoproduto: $('#codsecaoproduto').val()
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
    if(isset($options['codsecaoproduto'])) {
    $script .= <<< END
    <script type="text/javascript">
        $(document).ready(function() {
            $('#{$options['codsecaoproduto']}').on('change', function (e) {
                e.preventDefault();
                $('#{$options['id']}').select2('val', null).trigger('change');
            });
        });
    </script>
END;
    }
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

    if (empty($options['ativo']))
        $options['ativo'] = 1;

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
                                ativo: {$options['ativo']},
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
    if(isset($options['codfamiliaproduto'])) {
    $script .= <<< END
    <script type="text/javascript">
        $(document).ready(function() {
            $('#{$options['codfamiliaproduto']}').on('change', function (e) {
                e.preventDefault();
                $('#{$options['id']}').select2('val', null).trigger('change');
            });
        });
    </script>
END;
    }
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

    if (empty($options['ativo']))
        $options['ativo'] = 1;

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
                                ativo: {$options['ativo']},
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
    if(isset($options['codgrupoproduto'])) {
    $script .= <<< END
    <script type="text/javascript">
        $(document).ready(function() {
            $('#{$options['codgrupoproduto']}').on('change', function (e) {
                e.preventDefault();
                $('#{$options['id']}').select2('val', null).trigger('change');
            });
        });
    </script>
END;
    }

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

    if (empty($options['ativo']))
        $options['ativo'] = 1;

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
                            return {
                                q: term,
                                ativo: {$options['ativo']}
                            };
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

/* EMPRESA */
Form::macro('select2Empresa', function($name, $selected = null, $options = [])
{
    if (empty($options['placeholder'])) $options['placeholder'] = 'Empresa';
    $regs = [''=>''] + MGLara\Models\Empresa::orderBy('codempresa')->lists('empresa', 'codempresa')->all();
    return Form::select2($name, $regs, $selected, $options);
});

/* FILIAL */
Form::macro('select2Filial', function($name, $selected = null, $options = [])
{
    if (empty($options['placeholder'])) $options['placeholder'] = 'Filial';
    $regs = [''=>''] + MGLara\Models\Filial::orderBy('codfilial')->lists('filial', 'codfilial')->all();
    return Form::select2($name, $regs, $selected, $options);
});

/* DEPOSITO */
Form::macro('select2Deposito', function($name, $selected = null, $options = [])
{
    if (empty($options['placeholder'])) $options['placeholder'] = 'Filial';
    $regs = [''=>''] + MGLara\Models\EstoqueLocal::orderBy('codestoquelocal')->lists('estoquelocal', 'codestoquelocal')->all();
    return Form::select2($name, $regs, $selected, $options);
});


/* BANCO */
Form::macro('select2Banco', function($name, $selected = null, $options = [])
{
    if (empty($options['placeholder'])) $options['placeholder'] = 'Banco';
    $regs = [''=>''] + MGLara\Models\Banco::orderBy('codbanco')->lists('banco', 'codbanco')->all();
    return Form::select2($name, $regs, $selected, $options);
});

/* ECF */
Form::macro('select2Ecf', function($name, $selected = null, $options = [])
{
    if (empty($options['placeholder'])) $options['placeholder'] = 'ECF';
    $regs = [''=>''] + MGLara\Models\Ecf::orderBy('codecf')->lists('ecf', 'codecf')->all();
    return Form::select2($name, $regs, $selected, $options);
});

/* PORTADORES */
Form::macro('select2Portador', function($name, $selected = null, $options = [])
{
    if (empty($options['placeholder'])) $options['placeholder'] = 'Portador';
    $regs = [''=>''] + MGLara\Models\Portador::orderBy('codportador')->lists('portador', 'codportador')->all();
    return Form::select2($name, $regs, $selected, $options);
});

/* OPERAÇÃO */
Form::macro('select2Operacao', function($name, $selected = null, $options = [])
{
    if (empty($options['placeholder'])) $options['placeholder'] = 'Operação';
    $regs = [''=>''] + MGLara\Models\Operacao::orderBy('codoperacao')->lists('operacao', 'codoperacao')->all();
    return Form::select2($name, $regs, $selected, $options);
});

/* ESTOQUE LOCAL */
Form::macro('select2EstoqueLocal', function($name, $selected = null, $options = [])
{
    if (empty($options['placeholder'])) $options['placeholder'] = 'Local Estoque';
    $regs = [''=>''] + MGLara\Models\EstoqueLocal::orderBy('codestoquelocal')->lists('estoquelocal', 'codestoquelocal')->all();
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

    if (empty($options['codncm']))
        $options['codncm'] = '';

    if (empty($options['placeholder']))
        $options['placeholder'] = 'CEST...';

    if (empty($options['allowClear']))
        $options['allowClear'] = true;
    $options['allowClear'] = ($options['allowClear'])?'true':'false';

    if (empty($options['closeOnSelect']))
        $options['closeOnSelect'] = true;
    $options['closeOnSelect'] = ($options['closeOnSelect'])?'true':'false';

    if (empty($options['ativo']))
        $options['ativo'] = 1;

    $script = <<< END
        <script type="text/javascript">
            $(document).ready(function() {
                $('#{$options['id']}').select2({
                    placeholder: '{$options['placeholder']}',
                    minimumInputLength: 0,
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

                $('#{$options['codncm']}').change(function () {
                    $('#{$options['id']}').select2('val', '');
                });
            });
        </script>
END;

    $campo = Form::text($name, $value, $options);

    return $campo . $script;
});

/* PESSOA */
Form::macro('select2Pessoa', function($name, $value = null, $options = [])
{
    if (empty($options['id']))
        $options['id'] = $name;

    if (empty($options['placeholder']))
        $options['placeholder'] = 'Pessoa';

    if (empty($options['allowClear']))
        $options['allowClear'] = true;
    $options['allowClear'] = ($options['allowClear'])?'true':'false';

    if (empty($options['closeOnSelect']))
        $options['closeOnSelect'] = true;
    $options['closeOnSelect'] = ($options['closeOnSelect'])?'true':'false';

    if (empty($options['ativo']))
        $options['ativo'] = 1;

    $script = <<< END

        <script type="text/javascript">
            $(document).ready(function() {
                $('#{$options['id']}').select2({
                    placeholder: '{$options['placeholder']}',
                    minimumInputLength: 3,
                    allowClear: {$options['allowClear']},
                    closeOnSelect: {$options['closeOnSelect']},
                    'formatResult':function(item) {
                        var css_titulo = "";
                        var css_detalhes = "text-muted";
                        if (item.inativo) {
                            css_titulo = "text-danger";
                            css_detalhes = "text-danger";
                        }

                        var nome = item.fantasia;

                        //if (item.inclusaoSpc != 0)
                        //  nome += "&nbsp<span class=\"label label-warning\">" + item.inclusaoSpc + "</span>";

                        var markup = "";
                        markup    += "<strong class='" + css_titulo + "'>" + nome + "</strong>";
                        markup    += "<small class='pull-right " + css_detalhes + "'>#" + formataCodigo(item.id) + "</small>";
                        markup    += "<br>";
                        markup    += "<small class='" + css_detalhes + "'>" + item.pessoa + "</small>";
                        markup    += "<small class='pull-right " + css_detalhes + "'>" + formataCnpjCpf(item.cnpj) + "</small>";
                        return markup;
                    },
                    'formatSelection':function(item) {
                        return item.fantasia;
                    },
                    'ajax':{
                        'url':baseUrl+'/pessoa/listagem-json',
                        'dataType':'json',
                        'quietMillis':500,
                        'data':function(term, ativo, current_page) {
                            return {
                                q: term,
                                ativo: {$options['ativo']},
                                per_page: 10,
                                current_page: current_page
                            };
                        },
                        'results':function(data,page) {
                            //var more = (current_page * 20) < data.total;
                            return {
                                results: data.data,
                                //more: data.mais
                            };
                        }
                    },
                    'initSelection':function (element, callback) {
                        $.ajax({
                            type: "GET",
                            url: baseUrl+'/pessoa/listagem-json',
                            data: "id="+$('#{$options['id']}').val(),
                            dataType: "json",
                            success: function(result) {
                                callback(result);
                            }
                        });
                    },'width':'resolve'
                });
            });
        </script>
END;

    $campo = Form::text($name, $value, $options);

    return $campo . $script;
});

/* CIDADE*/
Form::macro('select2Cidade', function($name, $value = null, $options = [])
{
    if (empty($options['id']))
        $options['id'] = $name;

    if (empty($options['placeholder']))
        $options['placeholder'] = 'Cidade';

    if (empty($options['allowClear']))
        $options['allowClear'] = true;
    $options['allowClear'] = ($options['allowClear'])?'true':'false';

    if (empty($options['closeOnSelect']))
        $options['closeOnSelect'] = true;
    $options['closeOnSelect'] = ($options['closeOnSelect'])?'true':'false';

    if (empty($options['ativo']))
        $options['ativo'] = 1;

    $script = <<< END

        <script type="text/javascript">
            $(document).ready(function() {
                $('#{$options['id']}').select2({
                    placeholder: '{$options['placeholder']}',
                    minimumInputLength: 3,
                    allowClear: {$options['allowClear']},
                    closeOnSelect: {$options['closeOnSelect']},
                    formatResult: function(item) {
                        var markup = "";
                        markup    += item.cidade + "<span class='pull-right'>" + item.uf + "</span>";
                        return markup;
                    },
                    formatSelection: function(item) {
                        return item.cidade;
                    },
                    ajax:{
                        url: baseUrl+'/cidade/listagem-json',
                        dataType: 'json',
                        quietMillis: 500,
                        data: function(term, current_page) {
                            return {
                                q: term,
                                ativo: {$options['ativo']},
                                per_page: 10,
                                current_page: current_page
                            };
                        },
                        results:function(data,page) {
                            //var more = (current_page * 20) < data.total;
                            return {
                                results: data.data,
                                //more: data.mais
                            };
                        }
                    },
                    initSelection: function (element, callback) {
                        $.ajax({
                            type: "GET",
                            url: baseUrl+'/cidade/listagem-json',
                            data: "id="+$('#{$options['id']}').val(),
                            dataType: "json",
                            success: function(result) {
                                callback(result);
                            }
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

/* PRODUTO*/
Form::macro('select2Produto', function($name, $value = null, $options = [])
{
    if (empty($options['id']))
        $options['id'] = $name;

    if (empty($options['placeholder']))
        $options['placeholder'] = 'Produto';

    if (empty($options['allowClear']))
        $options['allowClear'] = true;
    $options['allowClear'] = ($options['allowClear'])?'true':'false';

    if (empty($options['closeOnSelect']))
        $options['closeOnSelect'] = true;
    $options['closeOnSelect'] = ($options['closeOnSelect'])?'true':'false';

    if (empty($options['ativo']))
        $options['ativo'] = 1;

    $script = <<< END

        <script type="text/javascript">
            $(document).ready(function() {
                $('#{$options['id']}').select2({
                    placeholder: '{$options['placeholder']}',
                    minimumInputLength: 3,
                    allowClear: {$options['allowClear']},
                    closeOnSelect: {$options['closeOnSelect']},
                    'formatResult':function(item) {
                        var css_titulo = "";
                        var css_detalhes = "text-muted";
                        if (item.inativo) {
                            css_titulo = "text-danger";
                            css_detalhes = "text-danger";
                        }

                        var markup = "";
                        markup    += "<span class="+ css_titulo +"><small class=\"text-muted\">"+ item.codigo +"</small> "+item.produto + "<span class='pull-right'>R$ " + item.preco + "</span>";
                        markup    += "<br>";
                        markup    += "<small class='" + css_detalhes + "'>";
                        markup    += item.secaoproduto;
                        markup    += " » " + item.familiaproduto;
                        markup    += " » " + item.grupoproduto;
                        markup    += " » " + item.subgrupoproduto;
                        markup    += " » " + item.marca;
                        if (item.referencia) {
                            markup    += " » " + item.referencia;
                        }
                        markup    += "</small>";
                        return markup;
                    },
                    'formatSelection':function(item) {
                        return item.produto;
                    },
                    'ajax':{
                        'url':baseUrl+'/produto/listagem-json',
                        'dataType':'json',
                        'quietMillis':500,
                        'data':function(term, ativo, current_page) {
                            return {
                                q: term,
                                ativo: {$options['ativo']},
                                per_page: 10,
                                current_page: current_page
                            };
                        },
                        'results':function(data,page) {
                            //var more = (current_page * 20) < data.total;
                            return {
                                results: data,
                                //more: data.mais
                            };
                        }
                    },
                    'initSelection':function (element, callback) {
                        $.ajax({
                            type: "GET",
                            url: baseUrl+'/produto/listagem-json',
                            data: "id="+$('#{$options['id']}').val(),
                            dataType: "json",
                            success: function(result) {
                                callback(result);
                            }
                        });
                    },'width':'resolve'
                });
            });
        </script>
END;

    $campo = Form::text($name, $value, $options);

    return $campo . $script;
});

/* PRODUTO Barra */
Form::macro('select2ProdutoBarra', function($name, $value = null, $options = [])
{
    if (empty($options['id']))
        $options['id'] = $name;

    if (empty($options['placeholder']))
        $options['placeholder'] = 'Produto';

    if (empty($options['allowClear']))
        $options['allowClear'] = true;
    $options['allowClear'] = ($options['allowClear'])?'true':'false';

    if (empty($options['closeOnSelect']))
        $options['closeOnSelect'] = true;
    $options['closeOnSelect'] = ($options['closeOnSelect'])?'true':'false';

    if (empty($options['ativo']))
        $options['ativo'] = 1;

    $script = <<< END

        <script type="text/javascript">
            $(document).ready(function() {
                $('#{$options['id']}').select2({
                    placeholder: '{$options['placeholder']}',
                    minimumInputLength: 3,
                    allowClear: {$options['allowClear']},
                    closeOnSelect: {$options['closeOnSelect']},
                    'formatResult':function(item) {
                        var css_titulo = "";
                        var css_detalhes = "";
                        if (item.inativo) {
                            css_titulo = "text-danger";
                            css_detalhes = "text-danger";
                        }

                        var markup = "";
                        markup    += "<div class='row "+ css_titulo +"'>";

                        markup    += "<strong class='col-md-9'>";
                        markup    += item.produto + "";
                        markup    += "</strong>";

                        markup    += "<div class='col-md-3'>";
                        markup    += "<small class='pull-left'>" + item.unidademedida + "</small>";
                        markup    += "<span class='pull-right'> " + item.preco + "</span>";
                        markup    += "</div>";

                        markup    += "</div>";

                        markup    += "<small class='" + css_detalhes + "'>";
                        markup    += "<strong>" + item.barras + "</strong>";
                        markup    += " » " + item.codproduto;
                        markup    += " » " + item.secaoproduto;
                        markup    += " » " + item.familiaproduto;
                        markup    += " » " + item.grupoproduto;
                        markup    += " » " + item.subgrupoproduto;
                        markup    += " » " + item.marca;
                        if (item.referencia) {
                            markup    += " » " + item.referencia;
                        }
                        markup    += "</small>";
                        return markup;
                    },
                    'formatSelection':function(item) {
                        return item.produto;
                    },
                    'ajax':{
                        'url':baseUrl+'/produto-barra/listagem-json',
                        'dataType':'json',
                        'quietMillis':500,
                        'data':function(term, ativo, current_page) {
                            return {
                                q: term,
                                ativo: {$options['ativo']},
                                per_page: 10,
                                current_page: current_page
                            };
                        },
                        'results':function(data,page) {
                            //var more = (current_page * 20) < data.total;
                            return {
                                results: data,
                                //more: data.mais
                            };
                        }
                    },
                    'initSelection':function (element, callback) {
                        $.ajax({
                            type: "GET",
                            url: baseUrl+'/produto-barra/listagem-json',
                            data: "id="+$('#{$options['id']}').val(),
                            dataType: "json",
                            success: function(result) {
                                callback(result);
                            }
                        });
                    },'width':'resolve'
                });
            });
        </script>
END;

    $campo = Form::text($name, $value, $options);

    return $campo . $script;
});

/* ESTOQUE MOVIMENTO TIPO */
Form::macro('select2EstoqueMovimentoTipo', function($name, $selected = null, $options = [])
{
    if (!isset($options['manual'])) {
        $options['manual'] = false;
    }

    if ($options['manual']) {
        $op = MGLara\Models\EstoqueMovimentoTipo::where('manual', '=', true)->orderBy('descricao')->lists('descricao', 'codestoquemovimentotipo')->all();
    } else {
        $op = MGLara\Models\EstoqueMovimentoTipo::orderBy('descricao')->lists('descricao', 'codestoquemovimentotipo')->all();
    }

    $op = [''=>''] + $op;

    return Form::select2($name, $op, $selected, $options);
});

/* Vale Compra Modelo */
Form::macro('select2ValeCompraModelo', function($name, $selected = null, $options = [])
{
    $options['ativo'] = (isset($options['ativo']))?$options['ativo']:1;
    $options['placeholder'] = (isset($options['placeholder']))?$options['placeholder']:'Modelo de Vale Compras';

    $qry = MGLara\Models\ValeCompraModelo::orderBy('modelo');
    switch ($options['ativo']) {
        case 1:
            $qry->whereNull('inativo');
            break;
        case 2:
            $qry->whereNotNull('inativo');
            break;
    }
    $valores = [''=>''] + $qry->lists('modelo', 'codvalecompramodelo')->all();
    return Form::select2($name, $valores, $selected, $options);
});


/* Forma de Pagamento */
Form::macro('select2FormaPagamento', function($name, $selected = null, $options = [])
{
    $options['ativo'] = (isset($options['ativo']))?$options['ativo']:1;
    $options['placeholder'] = (isset($options['placeholder']))?$options['placeholder']:'Forma de Pagamento';

    $qry = MGLara\Models\FormaPagamento::orderBy('formapagamento');
    /*
    switch ($options['ativo']) {
        case 1:
            $qry->whereNull('inativo');
            break;
        case 2:
            $qry->whereNotNull('inativo');
            break;
    }
     *
     */
    $valores = [''=>''] + $qry->lists('formapagamento', 'codformapagamento')->all();
    return Form::select2($name, $valores, $selected, $options);
});

/* ATIVO */
Form::macro('select2MarcaControlada', function($name, $selected = null, $options = [])
{
    $opcoes = ['' => '', 1 => 'Controladas', 2 => 'Não Controladas'];
    $options['placeholder'] = 'Marcas Controladas';
    return Form::select2($name, $opcoes, $selected, $options);
});
