@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="{{ url("estoque-movimento/create/$model->codestoquemes") }}"><span class="glyphicon glyphicon-plus"></span> Novo</a></li>             
            <li><a href="#" id="btnRecalculaMovimentoEstoque"><span class="glyphicon glyphicon-refresh"></span> Recalcular Movimento de Estoque</a></li>
            <li><a href="#" id="btnRecalculaCustoMedio"><span class="glyphicon glyphicon-usd"></span> Recalcular Custo Medio</a></li>
        </ul>
    </div>
</nav>

<h1 class="header">
    <a href='{{ url("grupo-produto/{$model->EstoqueSaldo->Produto->SubGrupoProduto->codgrupoproduto}") }}'>
        {{$model->EstoqueSaldo->Produto->SubGrupoProduto->GrupoProduto->grupoproduto}}
    </a> 
    ›
    <a href='{{ url("sub-grupo-produto/{$model->EstoqueSaldo->Produto->codsubgrupoproduto}") }}'>
        {{$model->EstoqueSaldo->Produto->SubGrupoProduto->subgrupoproduto}}
    </a>
    ›
    <a href='{{ url("produto/{$model->EstoqueSaldo->codproduto}") }}'>
        {{ $model->EstoqueSaldo->Produto->produto }}     
    </a>
</h1>

<div class="row row-fluid" id="estoque-mes-filtro">
    <div class="col-sm-1">
        {{ $model->EstoqueSaldo->EstoqueLocal->estoquelocal }}
    </div> 
    <div class="col-sm-1">
        {{ ($model->EstoqueSaldo->fiscal)?"Fiscal":"Fisico" }}
    </div> 
    <div class="col-sm-1">
        @if (isset($model->EstoqueSaldo->Produto->codmarca))
            <a href="{{ url("marca/{$model->EstoqueSaldo->Produto->Marca->codmarca}") }}">
                {{ $model->EstoqueSaldo->Produto->Marca->marca }}
            </a>
        @endif
    </div> 
    <div class="col-sm-1">
        <a href="{{ url("produto/{$model->EstoqueSaldo->codproduto}") }}">
            {{ formataCodigo($model->EstoqueSaldo->codproduto, 6) }}
        </a>
    </div> 
    <div class="col-sm-1">
        {{ formataNumero($model->EstoqueSaldo->Produto->preco, 2) }}
    </div>
    <div class="col-sm-2">
    {!! Form::model(Request::all(), ['url' => "estoque-mes/$model->codestoquemes", 'method' => 'GET', 'class' => 'form-inline', 'id' => 'estoque-mes-search', 'role' => 'search', 'autocomplete' => 'off'])!!}        
        <select class="form-control" name="negativos" id="negativos" style="width: 100px;">
            <option value=""></option>
            <option value="0">Todos</option>
            <option value="1">Negativos</option>
        </select>  
    {!! Form::close() !!}    
    </div>
</div>
<hr>
<!--
<div class="">
{!! Form::model(Request::all(), [
  'route' => 'estoque-mes.show', 
  'method' => 'GET', 
  'class' => 'form-inline',
  'id' => 'estoque-mes-filtro',
  'role' => 'search'
])!!}
    <div class="btn-group" data-toggle="buttons">
      <label class="btn btn-default">
          <a href="" class="radio-filter"> Todos</a>
      </label>
      <label class="btn btn-default">
          <a href="{{ url("estoque-mes/40676?var=10") }}" class="text-danger radio-filter"> Somente negativos</a>
      </label>
    </div>
{!! Form::close() !!}  
</div>
<br>
-->

<?php

$proximos = $model->buscaProximos(8);

$anteriores = $model->buscaAnteriores(16 - sizeof($proximos));

if (sizeof($anteriores) < 8)
    $proximos = $model->buscaProximos(16 - sizeof($anteriores));

?>

<ul class="nav nav-tabs">
    @foreach($anteriores as $em)
        <li role="presentation"><a href="<?php echo url("estoque-mes/$em->codestoquemes");?>">{{ formataData($em->mes, 'EC') }}</a></li>
    @endforeach
    <li role="presentation" class="active"><a href="#">{{ formataData($model->mes, 'EC') }}</a></li>
    @foreach($proximos as $em)
        <li role="presentation"><a href="<?php echo url("estoque-mes/$em->codestoquemes");?>">{{ formataData($em->mes, 'EC') }}</a></li>
    @endforeach
</ul>
<table class="table table-striped table-bordered table-condensed small">
    <thead>
        <tr>
            <th rowspan="2" class='col-sm-1'>Data</th>
            <th rowspan="2" class='col-sm-1'>Tipo</th>
            <th colspan="2">Entrada</th>
            <th colspan="2">Saída</th>
            <th colspan="2">Saldo</th>
            <th rowspan="2" class='col-sm-1'>Custo Médio</th>
            <th rowspan="2" class='col-sm-3'>Documento</th>
        </tr>
        <tr>
            <th class='col-sm-1'>Quantidade</th>
            <th class='col-sm-1'>Valor</th>
            <th class='col-sm-1'>Quantidade</th>
            <th class='col-sm-1'>Valor</th>
            <th class='col-sm-1'>Quantidade</th>
            <th class='col-sm-1'>Valor</th>            
        </tr>
    </thead>
    <tbody>
        <?php
        $saldoquantidade = $model->inicialquantidade;
        $saldovalor = $model->inicialvalor;
        $customedio = ($saldoquantidade != 0)?$saldovalor/$saldoquantidade:0;
        ?>
        <tr>
            <td></td>
            <th colspan="5">Saldo Inicial</th>
            <td class="text-right <?php echo ($saldoquantidade < 0)?"text-danger":""; ?>">{{ formataNumero($saldoquantidade, 3) }}</td>
            <td class="text-right <?php echo ($saldovalor < 0)?"text-danger":""; ?>">{{ formataNumero($saldovalor) }}</td>
            <td class="text-right">{{ formataNumero($customedio, 6) }}</td>
            <td></td>
        </tr>
        @foreach($model->EstoqueMovimentoS()->orderBy('data', 'asc')->orderBy('entradaquantidade', 'asc')->negativos(app('request')->input('negativos'))->get() as $row)
        <tr>
            <?php
                $saldoquantidade += $row->entradaquantidade - $row->saidaquantidade;
                $saldovalor += $row->entradavalor - $row->saidavalor;
                $customedio = (($row->entradaquantidade + $row->saidaquantidade) != 0)?($row->entradavalor + $row->saidavalor)/(($row->entradaquantidade + $row->saidaquantidade)):0;
            ?>
            <td>{{ formataData($row->data, 'L') }}</td>
            <td>{{ $row->EstoqueMovimentoTipo->descricao }}</td>
            <td class="text-right">{{ formataNumero($row->entradaquantidade, 3) }}</td>
            <td class="text-right">{{ formataNumero($row->entradavalor) }}</td>
            <td class="text-right">{{ formataNumero($row->saidaquantidade, 3) }}</td>
            <td class="text-right">{{ formataNumero($row->saidavalor) }}</td>
            <td class="text-right <?php echo ($saldoquantidade < 0)?"text-danger":""; ?>">{{ formataNumero($saldoquantidade, 3) }}</td>
            <td class="text-right <?php echo ($saldovalor < 0)?"text-danger":""; ?>"">{{ formataNumero($saldovalor) }}</td>
            <td class="text-right">{{ formataNumero($customedio, 6) }}</td>
            <td>
                @if (isset($row->codnotafiscalprodutobarra))
                    {{ formataNumero($row->NotaFiscalProdutoBarra->NotaFiscal->numero, 0) }} -
                    {{ $row->NotaFiscalProdutoBarra->NotaFiscal->Pessoa->fantasia }}
                @endif
                
                @if (isset($row->codestoquemovimentoorigem))
                    <a href="{{ url("estoque-mes/" . $row->EstoqueMovimentoOrigem->codestoquemes) }}">
                       {{ $row->EstoqueMovimentoOrigem->EstoqueMes->EstoqueSaldo->EstoqueLocal->estoquelocal }}
                    </a>
                @endif
                
                @foreach ($row->EstoqueMovimentoS as $em)
                    <a href="{{ url("estoque-mes/" . $em->codestoquemes) }}">
                       {{ $em->EstoqueMes->EstoqueSaldo->EstoqueLocal->estoquelocal }}
                    </a>
                @endforeach
                
                @if($row->manual)
                <a href="{{ url("estoque-movimento/$row->codestoquemovimento/edit") }}"class="pull-right btn btn-info btn-sm">
                    <i class="glyphicon glyphicon-pencil"></i>
                </a>
                @endif
                
                {{ $row->observacoes }}
            </td>
        </tr>
        @endforeach
        @if (count($model) === 0)
        <tr>
            <th colspan="10">Nenhum registro encontrado!</th>
        </tr>
        @endif
    <tfoot>
        <tr>
            <th></th>
            <th>Totais</th>
            <th class="text-right">{{ formataNumero($model->entradaquantidade, 3) }}</th>
            <th class="text-right">{{ formataNumero($model->entradavalor) }}</th>
            <th class="text-right">{{ formataNumero($model->saidaquantidade, 3) }}</th>
            <th class="text-right">{{ formataNumero($model->saidavalor) }}</th>
            <th class="text-right <?php echo ($model->saldoquantidade < 0)?"text-danger":""; ?>">{{ formataNumero($model->saldoquantidade, 3) }}</th>
            <th class="text-right <?php echo ($model->saldovalor < 0)?"text-danger":""; ?>"">{{ formataNumero($model->saldovalor) }}</th>
            <th class="text-right">{{ formataNumero($model->customedio, 6) }}</th>
            <th></th>
        </tr>
    </tfoot>
    </tbody>
</table>

<!-- Modal -->
<div class="modal fade" id="modalRecalculando" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        Aguarde, recalculando...
      </div>
    </div>
  </div>
</div>

@section('inscript')
<script type="text/javascript">
    
function recalculaMovimentoEstoque() {
    $.getJSON("<?php echo url("produto/{$model->EstoqueSaldo->codproduto}/recalcula-movimento-estoque"); ?>")
        .done(function(data) 
        {
            $('#modalRecalculando').modal('hide');
            var mensagem = 'Recálculo efetuado com sucesso!'
            if (!data.resultado)
                mensagem = data.mensagem;
            bootbox.alert(mensagem, function (result){
                location.reload();                    
            });
        })
        .fail(function( jqxhr, textStatus, error ) 
        {
            $('#modalRecalculando').modal('hide');
            bootbox.alert(error);
        });	
    
}

function recalculaCustoMedio() {
    $.getJSON("<?php echo url("produto/{$model->EstoqueSaldo->codproduto}/recalcula-custo-medio"); ?>")
        .done(function(data) 
        {
            $('#modalRecalculando').modal('hide');
            var mensagem = 'Recálculo efetuado com sucesso!'
            if (!data.resultado)
                mensagem = data.mensagem;
            bootbox.alert(mensagem, function (result){
                location.reload();                    
            });
        })
        .fail(function( jqxhr, textStatus, error ) 
        {
            $('#modalRecalculando').modal('hide');
            bootbox.alert(error);
        });	
    
}

$(document).ready(function() {
    $('#btnRecalculaMovimentoEstoque').click(function (e) {
        bootbox.confirm("Recalcular Movimento de Estoque para este produto?", function(result) {
            if (result)
            {
                $('#modalRecalculando').modal();
                recalculaMovimentoEstoque();
            }
        }); 
    });
    
    $('#btnRecalculaCustoMedio').click(function (e) {
        bootbox.confirm("Recalcular Movimento de Estoque para este produto?", function(result) {
            if (result)
            {
                $('#modalRecalculando').modal();
                recalculaCustoMedio();
            }
        }); 
    });
    $('#estoque-mes-search').change(function() {
        this.submit();
    });     
    $('#negativos').select2({
        allowClear:true,
        closeOnSelect:true
    })<?php echo (app('request')->input('negativos') ? ".select2('val'," .app('request')->input('negativos').");" : ';'); ?>    
});
</script>
@endsection
@stop