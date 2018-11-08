<?php

    use MGLara\Models\ProdutoEmbalagem;
    
    $embalagens[0] = $produto->UnidadeMedida->sigla;
    
    foreach ($produto->ProdutoEmbalagemS as $pe)
        $embalagens[$pe->codprodutoembalagem] = $pe->descricao;
    
    $variacoes = $produto->ProdutoVariacaoS()->orderBy('variacao', 'ASC NULLS FIRST')->lists('variacao', 'codprodutovariacao')->all();

    foreach($variacoes as $cod => $descr)
        if (empty($descr))
            $variacoes[$cod] = '{Sem Variação}';
    
    $variacoes = ['' => ''] + $variacoes;
    
?>
<div class="form-group">
    <label for="codprodutovariacao" class="col-sm-2 control-label">{!! Form::label('Variação:') !!}</label>
    <div class="col-sm-2">{!! Form::select('codprodutovariacao', $variacoes, null, ['class'=> 'form-control', 'required'=>true, 'id' => 'codprodutovariacao', 'style'=>'width:100%']) !!}</div>
</div>
<div class="form-group">
    <label for="codprodutoembalagem" class="col-sm-2 control-label">{!! Form::label('Unidade Medida:') !!}</label>
    <div class="col-sm-2">{!! Form::select('codprodutoembalagem', $embalagens, null, ['class'=> 'form-control', 'id' => 'codprodutoembalagem', 'style'=>'width:100%']) !!}</div>
</div>
<div class="form-group">
    <label for="barras" class="col-sm-2 control-label">{!! Form::label('Barras:') !!}</label>
    <div class="col-sm-2" id="barrasDiv">{!! Form::text('barras', null, ['class'=> 'form-control', 'id'=>'barras']) !!}
    </div>
</div>
<div class="form-group">
    <label for="variacao" class="col-sm-2 control-label">{!! Form::label('Detalhes:') !!}</label>
    <div class="col-sm-2">{!! Form::text('variacao', null, ['class'=> 'form-control', 'id'=>'variacao']) !!}
    </div>
</div>

<div class="form-group">
    <label for="referencia" class="col-sm-2 control-label">{!! Form::label('Referência:') !!}</label>
    <div class="col-sm-2">{!! Form::text('referencia', null, ['class'=> 'form-control', 'id'=>'referencia']) !!}
    </div>
</div>
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {!! Form::submit($submitTextButton, array('class' => 'btn btn-primary')) !!}
    </div>
</div>

@section('inscript')
<script type="text/javascript">
//http://www.gs1.org/barcodes/support/check_digit_calculator#how
function calculaDigitoGtin(codigo)
{
    //preenche com zeros a esquerda
    codigo = "000000000000000000" + codigo;

    //pega 18 digitos
    codigo = codigo.substring(codigo.length-18, codigo.length);
    soma = 0;

    //soma digito par *1 e impar *3
    for (i = 1; i<codigo.length; i++)
    {
        digito = codigo.charAt(i-1);
        if (i === 0 || !!(i && !(i%2)))
            multiplicador = 1;
        else
            multiplicador = 3;
        soma +=  digito * multiplicador;
    }

    //subtrai da maior dezena
    digito = (Math.ceil(soma/10)*10) - soma;	

    //retorna digitocalculado
    return digito;
}

//valida o codigo de barras 
function validaGtin(codigo)
{
    codigooriginal = codigo;
    codigo = codigo.replace(/[^0-9]/g, '');
    
    //se estiver em branco retorna verdadeiro
    if (codigo.length == 0) 
        return true;

    //se tiver letras no meio retorna false
    if (codigo.length != codigooriginal.length)
        return false;

    //se nao tiver comprimento adequado retorna false
    if ((codigo.length != 8) 
        && (codigo.length != 12) 
        && (codigo.length != 13) 
        && (codigo.length != 14) 
        && (codigo.length != 18))
        return false;

    //calcula digito e verifica se bate com o digitado
    digito = calculaDigitoGtin(codigo)
    if (digito == codigo.substring(codigo.length-1, codigo.length))
        return true;
    else
        return false;
}

function validaBarrasDigitado()
{
    //inicializa var
    var codigo = $('#barras').val();
    
    if (validaGtin(codigo))
        return true;
    /*
    if (codigo.substring(0, 7) == '{!! str_pad($produto->codproduto, 6, '0', STR_PAD_LEFT)  !!}-')
        return true;
    
    if (codigo.substring(0, 6) == '{!! str_pad($produto->codproduto, 6, '0', STR_PAD_LEFT)  !!}' && codigo.length == 6)
        return true;
    */
    return false;
}

//mostra aviso sobre digito codigo de barras incorreto
function mostraPopoverBarras()
{
    var aberto = !($('#barrasDiv').parent().find('.popover').length === 0);
    var abrir = !validaBarrasDigitado();

    //abre
    if (abrir && !aberto)
    {
        $("#barrasDiv").popover({title: 'Dígito Verificador Inválido!', content: 'Verifique o códito digitado, ele não parece estar em nenhum dos padrões de código de barras, como GTIN, EAN ou UPC!', trigger: 'manual', placement: 'right'});
        $("#barrasDiv").popover('show');
    }

    //fecha
    if (!abrir && aberto)
    {
        $("#barrasDiv").popover('destroy');  	
    }
	
}

function bootboxSalvar(form)
{
    bootbox.confirm("Tem certeza que deseja salvar?", function(result) {
        if (result) {
            form.submit();
        }
    });
}

$(document).ready(function() {
    $('#form-produto-barra').on("submit", function(e) {
        var currentForm = this;
        e.preventDefault();
        if (!validaBarrasDigitado())
        {
            bootbox.confirm("O código de barras cadastrado parece estar incorreto, deseja mesmo continuar?", function(result) {
                if (result) {
                    bootboxSalvar(currentForm);
                }
            });
        }
        else
            bootboxSalvar(currentForm);
    });
    
    $('#codprodutoembalagem').select2({
        placeholder: 'Embalagem',
        allowClear: true,
        closeOnSelect: true
    });    
    $('#codprodutovariacao').select2({
        placeholder: 'Variação',
        allowClear: true,
        closeOnSelect: true
    });

    $('#barras').keyup(function () {
        mostraPopoverBarras();
    });

    $("#variacao").Setcase();
    mostraPopoverBarras();    
    
});



</script>
@endsection
