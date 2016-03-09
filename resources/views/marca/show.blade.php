@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
  <div class="container-fluid"> 
    <ul class="nav navbar-nav">
      <li>
        <a href="{{ url('marca') }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a>
      </li>
      <li>
          <a href="#" id="btnBuscaCodProduto"><span class="glyphicon glyphicon-refresh"></span> Recalcular Movimento de Estoque</a>
      </li>
    </ul>
  </div>
</nav>
<h1 class="header">
    {{ $model->marca }}
</h1>
<br>

<?php
foreach($model->ProdutoS as $prod)
{
    foreach ($prod->EstoqueSaldoS as $es)
    {
        $arr_saldos[$prod->codproduto][$es->codestoquelocal][$es->fiscal] = [
            'codestoquesaldo' => $es->codestoquesaldo,
            'saldoquantidade' => $es->saldoquantidade,
            'saldovalor' => $es->saldovalor,
            'customedio' => $es->customedio,
        ];
        
        if (!isset($arr_totais[$es->codestoquelocal][$es->fiscal]))
            $arr_totais[$es->codestoquelocal][$es->fiscal] = [
                'saldoquantidade' => 0,
                'saldovalor' => 0
            ];

        $arr_totais[$es->codestoquelocal][$es->fiscal]['saldoquantidade'] += $es->saldoquantidade;
        $arr_totais[$es->codestoquelocal][$es->fiscal]['saldovalor'] += $es->saldovalor;
    }
}

?>

<table class="table table-striped table-condensed table-hover table-bordered small">
    <thead>
        <th colspan="2" class="col-sm-4">
            Produtos
        </th>
        @foreach ($els as $el)
        <th colspan='3' class='text-center col-sm-1' style='border-left-width: 2px'>
            {{ $el->estoquelocal }}
        </th>
        @endforeach
    </thead>
    
    <tbody>
        @foreach($model->ProdutoS as $row)
        <?php
        if (!isset($arr_saldos[$row->codproduto]))
            continue;
        ?>
        <tr>
            <th rowspan="2">
                <small class='text-muted'>
                    {{ formataCodigo($row->codproduto, 6) }}                    
                </small>
                <a href="{{ url("produto/$row->codproduto") }}">{{$row->produto}}</a>
                <div class='pull-right'>
                    {{ formataNumero($row->preco) }}
                </div>
                <br>
                @if (isset($row->codmarca))
                    {{ $row->Marca->marca }}
                @endif
            </th>
            <th>
                Físico
            </th>
            @foreach ($els as $el)
            <td class='text-right' style='border-left-width: 2px'>
                @if (isset($arr_saldos[$row->codproduto][$el->codestoquelocal][0]))
                    <?php $codestoquesaldo = $arr_saldos[$row->codproduto][$el->codestoquelocal][0]['codestoquesaldo']; ?>
                    <a href="{{ url("estoque-saldo/$codestoquesaldo") }}">
                        {{ formataNumero($arr_saldos[$row->codproduto][$el->codestoquelocal][0]['saldoquantidade'], 0) }}
                    </a>
                @endif
            </td>
            <td class='text-right'>
                @if (isset($arr_saldos[$row->codproduto][$el->codestoquelocal][0]))
                    <?php $codestoquesaldo = $arr_saldos[$row->codproduto][$el->codestoquelocal][0]['codestoquesaldo']; ?>
                    <a href="{{ url("estoque-saldo/$codestoquesaldo") }}">
                        {{ formataNumero($arr_saldos[$row->codproduto][$el->codestoquelocal][0]['customedio'], 2) }}
                    </a>
                @endif
            </td>
            <td class='text-right'>
                @if (isset($arr_saldos[$row->codproduto][$el->codestoquelocal][0]))
                    <?php $codestoquesaldo = $arr_saldos[$row->codproduto][$el->codestoquelocal][0]['codestoquesaldo']; ?>
                    <a href="{{ url("estoque-saldo/$codestoquesaldo") }}">
                        {{ formataNumero($arr_saldos[$row->codproduto][$el->codestoquelocal][0]['saldovalor'], 2) }}
                    </a>
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
                @if (isset($arr_saldos[$row->codproduto][$el->codestoquelocal][1]))
                    <?php $codestoquesaldo = $arr_saldos[$row->codproduto][$el->codestoquelocal][1]['codestoquesaldo']; ?>
                    <a href="{{ url("estoque-saldo/$codestoquesaldo") }}">
                        {{ formataNumero($arr_saldos[$row->codproduto][$el->codestoquelocal][1]['saldoquantidade'], 0) }}
                    </a>
                @endif
            </td>
            <td class='text-right'>
                @if (isset($arr_saldos[$row->codproduto][$el->codestoquelocal][1]))
                    <?php $codestoquesaldo = $arr_saldos[$row->codproduto][$el->codestoquelocal][1]['codestoquesaldo']; ?>
                    <a href="{{ url("estoque-saldo/$codestoquesaldo") }}">
                        {{ formataNumero($arr_saldos[$row->codproduto][$el->codestoquelocal][1]['customedio'], 2) }}
                    </a>
                @endif
            </td>
            <td class='text-right'>
                @if (isset($arr_saldos[$row->codproduto][$el->codestoquelocal][1]))
                    <?php $codestoquesaldo = $arr_saldos[$row->codproduto][$el->codestoquelocal][1]['codestoquesaldo']; ?>
                    <a href="{{ url("estoque-saldo/$codestoquesaldo") }}">
                        {{ formataNumero($arr_saldos[$row->codproduto][$el->codestoquelocal][1]['saldovalor'], 2) }}
                    </a>
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
            <th>
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
            <th>
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


<div id="modalrecalculaMovimentoEstoque" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    Recálculo Estoque marca:
                    <a href="{{ url("marca/$model->codmarca") }}">
                        {{ $model->marca }} 
                    </a>
                </h4>
            </div>
            <div class="modal-body">
                <div class="progress">
                    <div class="progress-bar progress-bar-striped active" role="progressbar" id="pbrecalculaMovimentoEstoque" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%">
                    </div>
                </div>
                <div class='row-fluid text-center' id='labelPbrecalculaMovimentoEstoque'></div>
                <br>
                <pre class='row-fluid hidden' id='logPbrecalculaMovimentoEstoque' style='height: 400px'></pre>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" disabled id="btnRecalculaMovimentoEstoque">Iniciar</button>
                <button type="button" class="btn btn-default" id="btnFechaModalrecalculaMovimentoEstoque" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>


@section('inscript')
<script type="text/javascript">

var codprodutos;
var i_codprodutos = 0;

function recalculaMovimentoEstoque() {
    
    var codproduto = codprodutos[i_codprodutos];
    
    var url = '{{ url('produto/{id}/recalcula-movimento-estoque' )}}';
    url = url.replace('{id}', codproduto);
        
    $.getJSON(url)
        .done(function(data) 
        {
            console.log(data);
            var mensagem = 'OK';
            
            if (!data.resultado)
                mensagem = 'Erro - ' + data.mensagem;
            
            $('#logPbrecalculaMovimentoEstoque').prepend(codproduto + ': ' + mensagem + '<br>');
            
            i_codprodutos++;
            atualizaPbrecalculaMovimentoEstoque();
            
            if (i_codprodutos <= (codprodutos.length -1))
                recalculaMovimentoEstoque();
            else
            {
                $('#btnRecalculaMovimentoEstoque').removeAttr('disabled');
                $('#btnFechaModalrecalculaMovimentoEstoque').removeAttr('disabled');
            }
        })
        .fail(function( jqxhr, textStatus, error ) 
        {
            bootbox.alert(error);
        });	
}

function atualizaPbrecalculaMovimentoEstoque () {
    var perc = (i_codprodutos / codprodutos.length) * 100;
    $('#pbrecalculaMovimentoEstoque').addClass('active');
    $('#pbrecalculaMovimentoEstoque').css('width', perc + '%');
    $('#labelPbrecalculaMovimentoEstoque').text(i_codprodutos + ' de ' + codprodutos.length + ' produtos!');
    if (i_codprodutos >= (codprodutos.length-1))
    {
        $('#pbrecalculaMovimentoEstoque').removeClass('active');
        $('#labelPbrecalculaMovimentoEstoque').text(codprodutos.length + ' produtos Processados!');
    }
}

function buscaCodProduto() {
    $.getJSON("<?php echo url("marca/{$model->codmarca}/busca-codproduto"); ?>")
        .done(function(data) 
        {
            codprodutos = data;
            atualizaPbrecalculaMovimentoEstoque();
            $('#modalrecalculaMovimentoEstoque').modal('show');
            $('#btnRecalculaMovimentoEstoque').removeAttr('disabled');
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
    
    $('#btnRecalculaMovimentoEstoque').click(function (e) {
        
        i_codprodutos = 0;
        $('#logPbrecalculaMovimentoEstoque').html('');
        $('#btnRecalculaMovimentoEstoque').attr('disabled', 'disabled');
        $('#btnFechaModalrecalculaMovimentoEstoque').attr('disabled', 'disabled');
        $('#logPbrecalculaMovimentoEstoque').removeClass('hidden');
        recalculaMovimentoEstoque();
    });
});
</script>
@endsection
@stop
