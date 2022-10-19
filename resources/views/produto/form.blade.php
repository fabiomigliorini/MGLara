<div class='col-md-5'>
    <div class="form-group">
        {!! Form::label('codtipoproduto', 'Tipo', ['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-6">{!! Form::select2TipoProduto('codtipoproduto', null, ['required' => true,  'class'=> 'form-control', 'id' => 'codtipoproduto', 'style'=>'width:100%', 'placeholder'=>'Tipo']) !!}</div>
        <div class="col-sm-3">{!! Form::select2('abc', ['A'=>'A', 'B'=>'B', 'C'=>'C'], null, ['class' => 'form-control','id'=>'abc', 'style'=>'width:100%', 'required'=>true]) !!}</div>
    </div>

    <div class="form-group">
        {!! Form::label('codmarca', 'Marca', ['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-5">{!! Form::select2Marca('codmarca', null, ['class' => 'form-control','id'=>'codmarca', 'style'=>'width:100%', 'required'=>true]) !!}</div>
    </div>

    <div class="form-group">
        {!! Form::label('codsecaoproduto', 'Seção', ['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-6">{!! Form::select2SecaoProduto('codsecaoproduto', null, ['required' => true, 'class'=> 'form-control', 'id' => 'codsecaoproduto', 'style'=>'width:100%', 'placeholder' => 'Seção']) !!}</div>

    </div>

    <div class="form-group">
        {!! Form::label('codfamiliaproduto', 'Família', ['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-6">{!! Form::select2FamiliaProduto('codfamiliaproduto', null, ['required' => true, 'class' => 'form-control','id'=>'codfamiliaproduto', 'style'=>'width:100%', 'placeholder' => 'Família', 'codsecaoproduto'=>'codsecaoproduto']) !!}</div>
    </div>

    <div class="form-group">
        {!! Form::label('codgrupoproduto', 'Grupo Produto', ['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-6">{!! Form::select2GrupoProduto('codgrupoproduto', null, ['required' => true, 'class' => 'form-control','id'=>'codgrupoproduto', 'style'=>'width:100%', 'placeholder' => 'Grupo', 'codfamiliaproduto'=>'codfamiliaproduto']) !!}</div>
    </div>

    <div class="form-group">
        {!! Form::label('codsubgrupoproduto', 'Sub Grupo', ['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-6">{!! Form::select2SubGrupoProduto('codsubgrupoproduto', null, ['required' => true, 'class' => 'form-control','id'=>'codsubgrupoproduto', 'style'=>'width:100%', 'placeholder' => 'Sub Grupo', 'codgrupoproduto'=>'codgrupoproduto']) !!}</div>
    </div>

    <div class="form-group">
        {!! Form::label('codncm', 'NCM', ['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-9">{!! Form::select2Ncm('codncm', null, ['required' => true, 'class' => 'form-control','id'=>'codncm', 'style'=>'width:100%', 'placeholder' => 'NCM']) !!}</div>
    </div>

    <div class="form-group">
        {!! Form::label('codtributacao', 'Tributação', ['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-4">{!! Form::select2Tributacao('codtributacao', null, ['required' => true, 'placeholder'=>'Tributação',  'class'=> 'form-control', 'id' => 'codtributacao', 'style'=>'width:100%']) !!}</div>
    </div>

    <div class="form-group">
        {!! Form::label('codcest', 'CEST', ['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-9">{!! Form::select2Cest('codcest', null, ['class' => 'form-control','id'=>'codcest', 'style'=>'width:100%', 'placeholder' => 'CEST', 'codncm'=>'codncm']) !!}</div>
    </div>

</div>

<div class='col-md-7'>
    <div class="form-group">
        {!! Form::label('produto', 'Descrição', ['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-9" id="produto-descricao">{!! Form::text('produto', null, ['class'=> 'typeahead form-control', 'id'=>'produto', 'data-provide'=>'typeahead', 'required'=>'true', 'autocomplete'=>'off']) !!}</div>
    </div>

    <div class="form-group">
        {!! Form::label('referencia', 'Referência', ['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-5">{!! Form::text('referencia', null, ['class'=> 'form-control'], ['id'=>'referencia']) !!}</div>
    </div>

    <div class="form-group">
        {!! Form::label('importado', 'Importado', ['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-9" id="wrapper-importado">{!! Form::checkbox('importado', true, null, ['id'=>'importado', 'data-off-text' => 'Nacional', 'data-on-text' => 'Importado']) !!}</div>
    </div>

    <div class="form-group">
        {!! Form::label('codunidademedida', 'Preço', ['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-2">{!! Form::number('preco', null, ['required' => true, 'step' => 0.01, 'class'=> 'form-control text-right', 'id'=>'preco', 'autofocus']) !!}</div>
        <div class="col-sm-3">{!! Form::select2UnidadeMedida('codunidademedida', null, ['required' => true,  'class'=> 'form-control', 'campo' => 'unidademedida', 'id' => 'codunidademedida', 'style'=>'width:100%']) !!}</div>
    </div>

    <div class="form-group">
        {!! Form::label('descricaosite', 'Descrição', ['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-9">{!! Form::textarea('descricaosite', null, ['class'=> 'form-control', 'id'=>'descricaosite', 'rows'=>'6']) !!}</div>
    </div>

    <div class="form-group">
        {!! Form::label('observacoes', 'Observações', ['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-9">{!! Form::textarea('observacoes', null, ['class'=> 'form-control', 'id'=>'observacoes', 'rows'=>'3']) !!}</div>
    </div>

    <!-- <div class="form-group">
        {!! Form::label('abc', 'ABC', ['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-2">{!! Form::select2('abc', ['A', 'B', 'C'], null, ['class' => 'form-control','id'=>'abc', 'style'=>'width:100%', 'required'=>true]) !!}</div>
    </div> -->

    <!-- <div class="form-group">
        {!! Form::label('site', 'Disponível no Site', ['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-9" id="wrapper-site">     {!! Form::checkbox('site', true, null, ['id'=>'site', 'data-off-text' => 'Não', 'data-on-text' => 'Sim']) !!}</div>
    </div> -->

</div>
<hr>
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {!! Form::submit($submitTextButton, array('class' => 'btn btn-primary')) !!}
    </div>
</div>

@section('inscript')
<script type="text/javascript" src="{{ URL::asset('public/vendor/bootstrap/typeahead.js/typeahead.bundle.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('public/vendor/bootstrap/typeahead.js/bloodhound.js') }}"></script>

<style type="text/css">
.popover {
    max-width: 100%;
    width: 70% !important;
}
.produtos-similares {
    list-style: none;
    padding: 5px 0;
}
.popover-title {
    display: none;
}
.typeahead,
.tt-query,
.tt-hint {
  width: 396px;
  height: 36px;
  line-height: 36px;
  border: 1px solid #ccc;
  outline: none;
}

.typeahead {
  background-color: #fff;
}

.typeahead:focus {

}

.tt-query {
  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
     -moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
          box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
}

.tt-hint {
  color: #999
}

.tt-menu {
  width: 422px;
  margin: 4px 0;
  padding: 4px 0;
  background-color: #fff;
  border: 1px solid #ccc;
  border: 1px solid rgba(0, 0, 0, 0.2);
  -webkit-border-radius: 3px;
     -moz-border-radius: 3px;
          border-radius: 3px;
  -webkit-box-shadow: 0 5px 10px rgba(0,0,0,.2);
     -moz-box-shadow: 0 5px 10px rgba(0,0,0,.2);
          box-shadow: 0 5px 10px rgba(0,0,0,.2);
}

.tt-suggestion {
  padding: 2px 10px;
  line-height: 24px;
}

.tt-suggestion:hover {
  cursor: pointer;
  color: #fff;
  background-color: #0097cf;
}

.tt-suggestion.tt-cursor {
  color: #fff;
  background-color: #0097cf;

}

.tt-suggestion p {
  margin: 0;
}

#custom-templates .empty-message {
  padding: 5px 10px;
 text-align: center;
}

#multiple-datasets .league-name {
  margin: 5px;
  padding: 3px 0;
  border-bottom: 1px solid #ccc;
}

#scrollable-dropdown-menu .tt-menu {
  max-height: 150px;
  overflow-y: auto;
}

#rtl-support .tt-menu {
  text-align: right;
}
</style>
<script type="text/javascript">
$(document).ready(function() {
    $('#form-produto').on("submit", function(e) {
        var currentForm = this;
        e.preventDefault();
        bootbox.confirm("Tem certeza que deseja salvar?", function(result) {
            if (result) {
                currentForm.submit();
            }
        });
    });
    $('#importado').bootstrapSwitch();
    // $('#site').bootstrapSwitch();

    @if (!empty($model->codsubgrupoproduto))
        $('#codsecaoproduto').val({{$model->SubGrupoProduto->GrupoProduto->FamiliaProduto->SecaoProduto->codsecaoproduto}});
        $('#codfamiliaproduto').val({{$model->SubGrupoProduto->GrupoProduto->codfamiliaproduto}});
        $('#codgrupoproduto').val({{ $model->SubGrupoProduto->codgrupoproduto}});
    @endif

    var codproduto = <?php echo (isset($model->codproduto) ? $model->codproduto:'""')?>;

    function descricaoProdutoTypeahead(codsubgrupoproduto, codproduto) {
        var produtoTypeahead = new Bloodhound({
            remote: {
                url: baseUrl + "/produto/descricao?q=%QUERY%&codsubgrupoproduto="+ codsubgrupoproduto +"&codproduto="+codproduto,
                wildcard: '%QUERY%',
            },
            datumTokenizer: function(produtoTypeahead) {
                return Bloodhound.tokenizers.whitespace(produtoTypeahead.produto);
            },
            queryTokenizer: Bloodhound.tokenizers.whitespace
        });

        $("#produto").typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        }, {
            source: produtoTypeahead.ttAdapter(),
            name: 'produtoTypeahead',
            displayKey: function(produtoTypeahead) {
              return produtoTypeahead.produto;
            },
            templates: {
                empty: [
                    '<p style="margin: 0; padding: 2px 8px;">Nenhum produto encontrado!</p>'
                ],
                header: [
                    //'<div class="list-group search-results-dropdown">'
                ],
                suggestion: function (data) {
                    return '<div>' + data.produto + '</div>'
                }
            },
            limit:14
        });

        $("#produto").on('typeahead:selected', function(e, data) {
            if($('#codsubgrupoproduto').val() == '') {
                $.getJSON(baseUrl + '/produto/popula-secao-produto', {
                    id: data.codproduto
                  }).done(function( data ) {
                      $("#codsubgrupoproduto").select2('val', 'id='+data.subgrupoproduto);
                      $("#codgrupoproduto").select2('val', 'id='+data.grupoproduto);
                      $("#codfamiliaproduto").select2('val', 'id='+data.familiaproduto);
                      $("#codsecaoproduto").select2('val', data.secaoproduto);
                });
            }
        });
    };

    $('#codsubgrupoproduto').change(function() {
        $('#produto').typeahead('destroy');
        var codsubgrupoproduto = $(this).val();
        descricaoProdutoTypeahead(codsubgrupoproduto, codproduto);
        $("#produto").Setcase();
    });

    descricaoProdutoTypeahead($('#codsubgrupoproduto').val(), codproduto);

    $("#produto").Setcase();
});
</script>
@endsection
