<?php
    //...
?>
<div class='row'>
  <div class='col-md-6'>
    <div class="form-group">
        {!! Form::label('codpessoafavorecido', 'Favorecido:', ['class'=>'col-sm-2 control-label']) !!}
        <div class="col-sm-10">{!! Form::select2Pessoa('codpessoafavorecido', null, ['class'=> 'form-control', 'id'=>'codpessoafavorecido', 'required'=>'required']) !!}</div>
    </div>
    <div class="form-group">
        {!! Form::label('modelo', 'Modelo:', ['class'=>'col-sm-2 control-label']) !!}
        <div class="col-sm-4">{!! Form::text('modelo', null, ['class'=> 'form-control', 'id'=>'modelo', 'required'=>'required']) !!}</div>
    </div>
    <div class="form-group">
        {!! Form::label('ano', 'Ano:', ['class'=>'col-sm-2 control-label']) !!}
        <div class="col-sm-3">{!! Form::number('ano', date("Y"), ['class'=> 'form-control text-center', 'step'=>'1', 'id'=>'ano', 'required'=>'required']) !!}</div>
    </div>
    <div class="form-group">
        {!! Form::label('turma', 'Turma:', ['class'=>'col-sm-2 control-label']) !!}
        <div class="col-sm-4">{!! Form::text('turma', null, ['class'=> 'form-control', 'id'=>'turma', 'required'=>'required']) !!}</div>
    </div>
  </div>
  <div class='col-md-6'>
    <div class="form-group">
        {!! Form::label('totalprodutos', 'Produtos:', ['class'=>'col-sm-2 control-label']) !!}
        <div class="col-sm-4">{!! Form::number('totalprodutos', null, ['class'=> 'form-control text-right', 'disabled'=>'disabled', 'id'=>'totalprodutos']) !!}</div>
    </div>
    <div class="form-group">
        {!! Form::label('desconto', 'Desconto:', ['class'=>'col-sm-2 control-label']) !!}
        <div class="col-sm-4">{!! Form::number('desconto', null, ['class'=> 'form-control text-right', 'id'=>'desconto', 'step'=> 0.01, 'min'=>0.00]) !!}</div>
    </div>
    <div class="form-group">
        {!! Form::label('total', 'Total:', ['class'=>'col-sm-2 control-label']) !!}
        <div class="col-sm-4">{!! Form::number('total', null, ['class'=> 'form-control text-right', 'disabled'=>'disabled', 'id'=>'total']) !!}</div>
    </div>
    <div class="form-group">
        {!! Form::label('observacoes', 'Observacoes:', ['class'=>'col-sm-2 control-label']) !!}
        <div class="col-sm-4">{!! Form::textarea('observacoes', null, ['class'=> 'form-control', 'id'=>'observacoes', 'rows'=>3]) !!}</div>
    </div>
  </div>
</div>

<div class='row'>
  <div class='col-md-3'>
    {!! Form::text('barras', null, ['class'=> 'form-control', 'id'=>'barras']) !!}
  </div>
  <div class='col-md-4'>
    {!! Form::select2ProdutoBarra('codprodutobarra_pesquisa', 20000713, ['class'=> 'form-control', 'id'=>'codprodutobarra_pesquisa']) !!}
  </div>
  <div class='col-md-1'>
    <a class='btn btn-primary' id='btnAdicionarProdutoBarra'>Adicionar</a>
  </div>
</div>
<br>

<div id='divListagemProdutos'>
    <div class='row linhaModeloProdutoBarra'>
      <div class='col-md-2'>
        {!! Form::text('item_codprodutobarra[]', null, ['class'=> 'form-control item_codprodutobarra']) !!}
      </div>
      <div class='col-md-3 item_produto'>
      </div>
      <div class='col-md-2'>
        {!! Form::number('item_quantidade[]', 1, ['class'=> 'form-control text-right item_quantidade', 'min'=>0.000, 'step'=>0.001]) !!}
      </div>
      <div class='col-md-2'>
        {!! Form::number('item_preco[]', null, ['class'=> 'form-control text-right item_preco', 'min'=>0.00, 'step'=>0.01]) !!}
      </div>
      <div class='col-md-2'>
        {!! Form::number('item_total[]', null, ['class'=> 'form-control text-right item_total', 'min'=>0.00, 'step'=>0.01]) !!}
      </div>
    </div>
</div>  

<br>
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {!! Form::submit($submitTextButton, array('class' => 'btn btn-primary')) !!}
    </div>
</div>

@section('inscript')
<script type="text/javascript">
    
function calculaTotais () {
    var totalprodutos = 0;
    $('.linhaModeloProdutoBarra').each( function () {
        var quant = $(this).find(".item_quantidade").first().val();
        var preco = $(this).find(".item_preco").first().val();
        var total = Math.round(preco * quant * 100) / 100
        $(this).find(".item_total").first().val(total);
        totalprodutos += total;
    });
    $('#totalprodutos').val(Math.round(totalprodutos * 100)/100);
    $('#total').val(Math.round((totalprodutos - $('#desconto').val()) * 100)/100);
}

function adicionaProduto () {
    
    var id = $('#codprodutobarra_pesquisa').val();
    
    $.ajax({
        type: 'GET',
        url: '{{ url('produto-barra/listagem-json') }}?id=' + id,
        dataType: 'json',
        success: function(retorno) {
            var div = $('.linhaModeloProdutoBarra').first().clone(true, true);
            div.find(".item_codprodutobarra").first().val(retorno.codprodutobarra);
            div.find(".item_produto").first().html(retorno.produto);
            div.find(".item_preco").first().val(retorno.preco);
            div.removeClass('hidden');
            $('#divListagemProdutos').append(div);
            calculaTotais();
        },
        error: function (XHR, textStatus) {
            bootbox.alert('<span class="text-danger">Erro ao adicionar o produto!</span>');
            console.log(XHR);
            console.log(textStatus);
        }
    });    
}
    
$(document).ready(function() {
    $('#form-vale-compra-modelo').on("submit", function(e) {
        var currentForm = this;
        e.preventDefault();
        bootbox.confirm("Tem certeza que deseja salvar?", function(result) {
            if (result) {
                currentForm.submit();
            }
        });
    });
    
    $('#btnAdicionarProdutoBarra').click(function (e) {
        adicionaProduto();        
    });
    
    $('.item_quantidade, .item_preco, #desconto').change(function (e) {
        calculaTotais();
    });
    
    $('.item_total').change(function (e) {
        var total = $(this).val();
        var quant = $(this).closest('.linhaModeloProdutoBarra').find(".item_quantidade").first().val();
        $(this).closest('.linhaModeloProdutoBarra').find(".item_preco").first().val(Math.round((total / quant) * 100) / 100);
        calculaTotais();
    });
});
</script>
@endsection