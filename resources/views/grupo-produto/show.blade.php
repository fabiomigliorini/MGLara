@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="<?php echo url('grupo-produto');?>"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>             
            <li><a href="#" id="btnBuscaCodProduto"><span class="glyphicon glyphicon-refresh"></span> Recalcular Estoque</a></li>             
        </ul>
    </div>
</nav>
<h1 class="header">{{ $model->grupoproduto }}</h1>
<br>

<?php

foreach($ess as $es)
{
    $arr_saldos[$es->codsubgrupoproduto][$es->codestoquelocal][$es->fiscal] = [
        'saldoquantidade' => $es->saldoquantidade,
        'saldovalor' => $es->saldovalor,
    ];
    
    if (!isset($arr_totais[$es->codestoquelocal][$es->fiscal]))
        $arr_totais[$es->codestoquelocal][$es->fiscal] = [
            'saldoquantidade' => 0,
            'saldovalor' => 0
        ];
    
    $arr_totais[$es->codestoquelocal][$es->fiscal]['saldoquantidade'] += $es->saldoquantidade;
    $arr_totais[$es->codestoquelocal][$es->fiscal]['saldovalor'] += $es->saldovalor;
}
//dd($arr_saldos);
?>

<table class="table table-striped table-condensed table-hover table-bordered small">
    <thead>
        <th colspan="2" class="col-sm-4">
            Grupo Produto
        </th>
        @foreach ($els as $el)
        <th colspan='2' class='text-center col-sm-1' style='border-left-width: 2px'>
            {{ $el->estoquelocal }}
        </th>
        @endforeach
    </thead>
    
    <tbody>
        @foreach($model->SubGrupoProdutoS as $row)
        <tr>
            <th rowspan="2">
                <a href="{{ url("sub-grupo-produto/$row->codsubgrupoproduto") }}">{{$row->subgrupoproduto}}</a>
            </th>
            <th>
                Físico
            </th>
            @foreach ($els as $el)
            <td class='text-right' style='border-left-width: 2px'>
                @if (isset($arr_saldos[$row->codsubgrupoproduto][$el->codestoquelocal][0]))
                    {{ formataNumero($arr_saldos[$row->codsubgrupoproduto][$el->codestoquelocal][0]['saldoquantidade'], 0) }}
                @endif
            </td>
            <td class='text-right'>
                @if (isset($arr_saldos[$row->codsubgrupoproduto][$el->codestoquelocal][0]))
                    {{ formataNumero($arr_saldos[$row->codsubgrupoproduto][$el->codestoquelocal][0]['saldovalor'], 2) }}
                @endif
            </td>
            @endforeach
        </tr>
        <tr>
            <th>
                Fiscal
            </th>
            @foreach ($els as $el)
            <td class='text-right' style='border-left-width: 2px'>
                @if (isset($arr_saldos[$row->codsubgrupoproduto][$el->codestoquelocal][1]))
                    {{ formataNumero($arr_saldos[$row->codsubgrupoproduto][$el->codestoquelocal][1]['saldoquantidade'], 0) }}
                @endif
            </td>
            <td class='text-right'>
                @if (isset($arr_saldos[$row->codsubgrupoproduto][$el->codestoquelocal][1]))
                    {{ formataNumero($arr_saldos[$row->codsubgrupoproduto][$el->codestoquelocal][1]['saldovalor'], 2) }}
                @endif
            </td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
    
    <tfoot>
        <tr>
            <th rowspan="2">
                Totais
            </th>
            <th>
                Físico
            </th>
            @foreach ($els as $el)
            <th class='text-right' style='border-left-width: 2px'>
                @if (isset($arr_totais[$el->codestoquelocal][0]))
                    {{ formataNumero($arr_totais[$el->codestoquelocal][0]['saldoquantidade'], 0) }}
                @endif
            </th>
            <th class='text-right'>
                @if (isset($arr_totais[$el->codestoquelocal][0]))
                    {{ formataNumero($arr_totais[$el->codestoquelocal][0]['saldovalor'], 2) }}
                @endif
            </th>
            @endforeach
        </tr>
        <tr>
            <th>
                Fiscal
            </th>
            @foreach ($els as $el)
            <th class='text-right' style='border-left-width: 2px'>
                @if (isset($arr_totais[$el->codestoquelocal][1]))
                    {{ formataNumero($arr_totais[$el->codestoquelocal][1]['saldoquantidade'], 0) }}
                @endif
            </th>
            <th class='text-right'>
                @if (isset($arr_totais[$el->codestoquelocal][1]))
                    {{ formataNumero($arr_totais[$el->codestoquelocal][1]['saldovalor'], 2) }}
                @endif
            </th>
            @endforeach
        </tr>
    </tfoot>
</table>

@if (count($model) === 0)
    <h3>Nenhum registro encontrado!</h3>
@endif    

<div id="modalRecalculaEstoque" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    Recálculo Estoque:
                    {{ $model->grupoproduto }}
                </h4>
            </div>
            <div class="modal-body">
                <div class="progress">
                    <div class="progress-bar progress-bar-striped active" role="progressbar" id="pbRecalculaEstoque" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%">
                    </div>
                </div>
                <div class='row-fluid text-center' id='labelPbRecalculaEstoque'></div>
                <br>
                <pre class='row-fluid hidden' id='logPbRecalculaEstoque' style='height: 400px'></pre>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" disabled id="btnRecalculaEstoque">Iniciar</button>
                <button type="button" class="btn btn-default" id="btnFechaModalRecalculaEstoque" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

@section('inscript')
<script type="text/javascript">

var codprodutos;
var i_codprodutos = 0;

function recalculaEstoque() {
    
    var codproduto = codprodutos[i_codprodutos];
    
    var url = '{{ url('produto/{id}/recalcula-estoque' )}}';
    url = url.replace('{id}', codproduto);
        
    $.getJSON(url)
        .done(function(data) 
        {
            console.log(data);
            var mensagem = 'OK';
            
            if (!data.resultado)
                mensagem = 'Erro - ' + data.mensagem;
            
            $('#logPbRecalculaEstoque').prepend(codproduto + ': ' + mensagem + '<br>');
            
            i_codprodutos++;
            atualizaPbRecalculaEstoque();
            
            if (i_codprodutos <= (codprodutos.length -1))
                recalculaEstoque();
            else
            {
                $('#btnRecalculaEstoque').removeAttr('disabled');
                $('#btnFechaModalRecalculaEstoque').removeAttr('disabled');
            }
        })
        .fail(function( jqxhr, textStatus, error ) 
        {
            bootbox.alert(error);
        });	
}

function atualizaPbRecalculaEstoque () {
    var perc = (i_codprodutos / codprodutos.length) * 100;
    $('#pbRecalculaEstoque').addClass('active');
    $('#pbRecalculaEstoque').css('width', perc + '%');
    $('#labelPbRecalculaEstoque').text(i_codprodutos + ' de ' + codprodutos.length + ' produtos!');
    if (i_codprodutos >= (codprodutos.length-1))
    {
        $('#pbRecalculaEstoque').removeClass('active');
        $('#labelPbRecalculaEstoque').text(codprodutos.length + ' produtos Processados!');
    }
}

function buscaCodProduto() {
    $.getJSON("<?php echo url("grupo-produto/{$model->codgrupoproduto}/busca-codproduto"); ?>")
        .done(function(data) 
        {
            codprodutos = data;
            atualizaPbRecalculaEstoque();
            $('#modalRecalculaEstoque').modal('show');
            $('#btnRecalculaEstoque').removeAttr('disabled');
        })
        .fail(function( jqxhr, textStatus, error ) 
        {
            bootbox.alert(error);
        });	
    
}

$(document).ready(function() {
    $('#btnBuscaCodProduto').click(function (e) {
        buscaCodProduto();
    });
    
    $('#btnRecalculaEstoque').click(function (e) {
        
        i_codprodutos = 0;
        $('#logPbRecalculaEstoque').html('');
        $('#btnRecalculaEstoque').attr('disabled', 'disabled');
        $('#btnFechaModalRecalculaEstoque').attr('disabled', 'disabled');
        $('#logPbRecalculaEstoque').removeClass('hidden');
        recalculaEstoque();
    });
});
</script>@endsection
@stop
