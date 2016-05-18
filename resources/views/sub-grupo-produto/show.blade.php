@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="#" id="btnBuscaCodProduto"><span class="glyphicon glyphicon-refresh"></span> Recalcular Movimento de Estoque</a></li>            
            <li><a href="<?php echo url("grupo-produto/$model->codgrupoproduto");?>"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>
            <li><a href="<?php echo url('sub-grupo-produto/create?codgrupoproduto='.$model->GrupoProduto->codgrupoproduto);?>"><span class="glyphicon glyphicon-plus"></span> Novo</a></li> 
            <li><a href="<?php echo url("sub-grupo-produto/$model->codsubgrupoproduto/edit");?>"><span class="glyphicon glyphicon-pencil"></span> Alterar</a></li> 
            <li>
                @if(empty($model->inativo))
                <a href="" id="inativar">
                    <span class="glyphicon glyphicon-ban-circle"></span> Inativar
                </a>
                @else
                <a href="" id="inativar">
                    <span class="glyphicon glyphicon-ok-sign"></span> Ativar
                </a>
                @endif
            </li>             
            
            <li>
                {!! Form::open(['method' => 'DELETE', 'id'=>'deleteId', 'route' => ['sub-grupo-produto.destroy', $model->codsubgrupoproduto]]) !!}
                <span class="glyphicon glyphicon-trash"></span>
                {!! Form::submit('Excluir') !!}
                {!! Form::close() !!}
            </li>              
        </ul>
    </div>
</nav>
<div class="row">
    <div class="col-md-6">
        <h1 class="header">
            <a href="{{ url("grupo-produto/$model->codgrupoproduto") }}">
                {{ $model->GrupoProduto->grupoproduto }} 
            </a>
            › {{ $model->subgrupoproduto }}
        </h1>
    </div>
    <div class="pull-right foto-item-unico">
        @if(empty($model->codimagem))
            <a class="btn btn-default carregar" href="{{ url("/imagem/edit?id=$model->codsubgrupoproduto&model=SubGrupoProduto") }}">
                <i class="glyphicon glyphicon-picture"></i>
                 Carregar imagem
            </a>
        @else
        <img class="img-responsive pull-right" src='<?php echo URL::asset('public/imagens/'.$model->Imagem->observacoes);?>'>
        <span class="caption simple-caption">
            <a href="" class="btn btn-default btn-sm"><i class="glyphicon glyphicon-pencil"></i> Alterar</a>
        </span>            
        @endif
    </div>
</div>
<hr>
<?php
foreach($model->ProdutoS as $prod)
{
    foreach ($prod->EstoqueLocalProdutoS as $es)
    {
        $arr_saldos[$prod->codproduto][$es->codestoquelocal][$es->EstoqueSaldoS->first()->fiscal] = [
            'codestoquesaldo'   => $es->EstoqueSaldoS->first()->codestoquesaldo,
            'saldoquantidade'   => $es->EstoqueSaldoS->first()->saldoquantidade,
            'saldovalor'        => $es->EstoqueSaldoS->first()->saldovalor,
            'customedio'        => $es->EstoqueSaldoS->first()->customedio,
        ];
        
        if (!isset($arr_totais[$es->codestoquelocal][$es->EstoqueSaldoS->first()->fiscal]))
            $arr_totais[$es->codestoquelocal][$es->EstoqueSaldoS->first()->fiscal] = [
                'saldoquantidade'   => 0,
                'saldovalor'        => 0
            ];

        $arr_totais[$es->codestoquelocal][$es->EstoqueSaldoS->first()->fiscal]['saldoquantidade'] += $es->EstoqueSaldoS->first()->saldoquantidade;
        $arr_totais[$es->codestoquelocal][$es->EstoqueSaldoS->first()->fiscal]['saldovalor'] += $es->EstoqueSaldoS->first()->saldovalor;
    }
}
?>
@if (count($model->ProdutoS) > 0)
<table class="table table-striped table-condensed table-hover table-bordered small">
    <thead>
        <th colspan="2" class="col-sm-4">
            Grupo Produto
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
@endif  

@if (count($model->ProdutoS) === 0)
    <h3>Nenhum produto cadastrado!</h3>
@endif    

<div id="modalrecalculaMovimentoEstoque" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    Recálculo Estoque:
                    <a href="{{ url("grupo-produto/$model->codgrupoproduto") }}">
                        {{ $model->GrupoProduto->grupoproduto }} 
                    </a>
                    > {{ $model->subgrupoproduto }}
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
    $.getJSON("<?php echo url("sub-grupo-produto/{$model->codsubgrupoproduto}/busca-codproduto"); ?>")
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
    
    $('#inativar').on("click", function(e) {
        e.preventDefault();
        var codsubgrupoproduto = {{ $model->codsubgrupoproduto }};
        var token = '{{ csrf_token() }}';
        var inativo = '{{ $model->inativo }}';
        if(inativo.length === 0) {
            acao = 'inativar';
        } else {
            acao = 'ativar';
        }
        bootbox.confirm("Tem certeza que deseja "+acao+"?", function(result) {
            if(result) {
                $.post(baseUrl + '/sub-grupo-produto/inativo', {
                    codsubgrupoproduto: codsubgrupoproduto,
                    acao: acao,
                    _token: token
                }).done(function (data) {
                    location.reload();
                }).fail(function (error) {
                  location.reload();          
                });
            }
        });
    });
    
});
</script>@endsection
@stop
