@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
    {!! titulo(null, 'Totais de Caixa', null) !!}
    <li class='active'>
        <small>
            <a class="" data-toggle="collapse" href="#div-filtro" aria-expanded="false" aria-controls="div-filtro"><span class='glyphicon glyphicon-search'></span></a>
        </small>
    </li>
</ol>
<div class="clearfix"></div>
<div class='collapse in' id='div-filtro'>
    <div class='well well-sm' style="padding:9px 0">
    {!! Form::model(
        $parametros,
        [
            'route' => 'caixa.index',
            'method' => 'GET',
            'class' => 'form-horizontal',
            'id' => 'caixa-search',
            'role' => 'search',
            'autocomplete' => 'off']
        )
    !!}
      <div class="clearfix">
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('codusuario', 'Usuário', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-9">{!! Form::select2Usuario('codusuario', null, ['class' => 'form-control', 'required'=>true]) !!}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('datainicial', 'De:', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-9">{!! Form::datetimeLocal('datainicial', null, ['class' => 'form-control text-center', 'required'=>true, 'placeholder' => 'De']) !!}</div>
            </div>
            <div class="form-group">
                {!! Form::label('datafinal', 'Até:', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-9">{!! Form::datetimeLocal('datafinal', null, ['class' => 'form-control text-center', 'required'=>true, 'placeholder' => 'Até']) !!}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('ativo', 'Ativo', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-9">{!! Form::select2Ativo('ativo', null, ['class'=> 'form-control', 'id' => 'ativo']) !!}</div>
            </div>
            <div class="form-group">
              <div class="col-md-4 col-md-offset-3"><button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i> Buscar</button></div>
            </div>
        </div>

      </div>
    </div>
    {!! Form::close() !!}
</div>

<table class='table table-bordered table-hover table-striped table-condensed' id="items">
  <thead>
    <tr>
      <th>
        Status
      </th>
      <th>
        Item
      </th>
      <th class="text-right">
        Entrada
      </th>
      <th class="text-right">
        Saída
      </th>
      <th class="text-right">
        Prazo
      </th>
    </tr>
  </thead>
  <tbody>
    @foreach ($dados['negocios'] as $neg)
      <?php
          switch ($neg->codnegociostatus) {
              case 1: // Aerto
                  $class='info';
                  break;

              case 3: // Cancelado
                  $class='danger';
                  break;

              default:
                  $class='';
                  break;
          }
      ?>
      <tr class='{{ $class }}'>
        <td>
          Negócio {{ $neg->negociostatus }}
        </td>
        <td>
          {{ $neg->naturezaoperacao }}
          <span class="badge">{{ formataNumero($neg->quantidade, 0) }}</span>

        </td>
        <td class='text-right'>
          @if ($neg->codoperacao == 2)
            {{ formataNumero($neg->avista) }}
          @endif
        </td>
        <td class='text-right'>
          @if ($neg->codoperacao == 1)
            {{ formataNumero($neg->avista) }}
          @endif
        </td>
        <td class='text-right'>
          {{ formataNumero($neg->aprazo) }}
        </td>
      </tr>
    @endforeach

    @foreach ($dados['vales'] as $vale)
      <?php
          switch ($vale->status) {
              case 'Inativo':
                  $class='danger';
                  break;

              default:
                  $class='';
                  break;
          }
      ?>
      <tr class='{{ $class }}'>
        <td colspan='2'>
          Vale Compras {{ $vale->status }}
          <span class="badge">{{ formataNumero($vale->quantidade, 0) }}</span>

        </td>
        <td class='text-right'>
          {{ formataNumero($vale->avista) }}
        </td>
        <td class='text-right'>
        </td>
        <td class='text-right'>
          {{ formataNumero($vale->aprazo) }}
        </td>
      </tr>
    @endforeach

    @foreach ($dados['liquidacoes'] as $liq)
      <?php
          switch ($liq->status) {
              case 'Estornada':
                  $class='danger';
                  break;

              default:
                  $class='';
                  break;
          }
      ?>
      <tr class='{{ $class }}'>
        <td colspan='2'>
          Liquidação {{ $liq->status }}
          <span class="badge">{{ formataNumero($liq->quantidade, 0) }}</span>

        </td>
        <td class='text-right'>
          {{ formataNumero($liq->credito) }}
        </td>
        <td class='text-right'>
          {{ formataNumero($liq->debito) }}
        </td>
        <td class='text-right'>
        </td>
      </tr>
    @endforeach

    @foreach ($dados['lio'] as $lio)
      <tr class='{{ $class }}'>
        <td colspan='2'>
          Lio Terminal
          {{ $lio->terminal }}
          <span class="badge">{{ formataNumero($lio->quantidade, 0) }}</span>

        </td>
        <td class='text-right'>
          {{ formataNumero($lio->entrada) }}
        </td>
        <td class='text-right'>
          {{ formataNumero($lio->saida) }}
        </td>
        <td class='text-right'>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>

<div id="items-lio">

  @if (!empty($dados['liolistagem']))
    <h3>
      Transações Lio
      <span class="badge">{{ formataNumero(count($dados['liolistagem']), 0) }}</span>
    </h3>
  @endif

  <table class='table table-bordered table-hover table-striped table-condensed'>
    @if (!empty($dados['liolistagem']))
    <tr>
      <th>
        #
      </th>
      <th class="text-right">
        Valor
      </th>
      <th>
        Cliente
      </th>
      <th>
        Produto
      </th>
      <th>
        Negócio
      </th>
      <th>
        Pedido Lio
      </th>
    </tr>
    @endif
    @foreach ($dados['liolistagem'] as $lio)
    <?php
    $class=($lio->codigov40 == 28)?'danger':''
    ?>
    <tr class='{{$class}}'>
      <td>
        <small class="text-muted">
          {{ formataCodigo($lio->codliopedidopagamento) }}
        </small>
      </td>
      <td class="text-right">
        <b>
          {{ formataNumero($lio->valor) }}
        </b>
      </td>
      <td>
        <small class="">
          {{ $lio->lioproduto }}
          @if (!empty($lio->parcelas))
            |
            {{ formataNumero($lio->parcelas, 0) }} VEZES
          @endif
          | {{ $lio->bandeiracartao }}
        </small> <br />
        <small class="text-muted">
          Terminal {{ $lio->terminal }}
          | Codigo V40 ({{ $lio->codigov40 }})
          | NSU ({{ $lio->nsu }})
        </small>
      </td>
      <td>
        <small>
          {{ $lio->nome }} <br />
        </small>
        <small class='text-muted'>
          Cartão {{ $lio->cartao }}
          | Autorização ({{ $lio->autorizacao }})
        </small>
      </td>
      <td>
        @if (!empty($lio->codnegocio))
          <?php
          $classNegocio=($lio->codnegociostatus != 2)?'text-danger':'text-muted'
          ?>
          <small>
            <a href="{{env('MGSIS_URL')}}/index.php?r=negocio/view&id={{$lio->codnegocio}}" target='_blank'>
              {{ formataCodigo($lio->codnegocio) }}
            </a>
            <b class="{{$classNegocio}}">
              {{ $lio->negociostatus }}
            </b>
          </small>
          <br />
          <small class="text-muted">
            Valor R$ {{ formataNumero($lio->valortotal) }} |
            {{ formataData($lio->lancamento, 'l') }}
          </small>

        @else
          <small class="text-muted">
            Valor Avulso
          </small>
        @endif
      </td>
      <td>
        <small class="text-muted">
          {{ $lio->uuid }} <br />
          Valor Pago R$ {{ formataNumero($lio->valorpago) }} |
          Status {{ $lio->liopedidostatus }}
        </small>
      </td>
    </tr>
    @endforeach
  </table>
</div>

@section('inscript')
<script type="text/javascript">
function atualizaFiltro()
{
    scroll();
    var frmValues = $('#caixa-search').serialize();
    $.ajax({
        type: 'GET',
        url: baseUrl + '/caixa',
        data: frmValues,
        dataType: 'html'
    })
    .done(function (data) {
        $('#items').html(jQuery(data).find('#items').html());
        $('#items-lio').html(jQuery(data).find('#items-lio').html());
    })
    .fail(function () {
        console.log('Erro no filtro');
    });

    $('#items').infinitescroll('update', {
        state: {
            currPage: 1,
            isDestroyed: false,
            isDone: false
        },
        path: ['?page=', '&'+frmValues]
    });
}

function scroll()
{
    var loading_options = {
        finishedMsg: "<div class='end-msg'>Fim dos registros</div>",
        msgText: "<div class='center'>Carregando mais itens...</div>",
        img: baseUrl + '/public/img/ajax-loader.gif'
    };

    $('#items').infinitescroll({
        loading : loading_options,
        navSelector : "#registros .pagination",
        nextSelector : "#registros .pagination li.active + li a",
        itemSelector : "#items div.list-group-item",
    });
}

$(document).ready(function() {
    scroll();
    $("#caixa-search").on("change", function (event) {
        $('#items').infinitescroll('destroy');
        atualizaFiltro();
    }).on('submit', function (event){
        event.preventDefault();
        $('#items').infinitescroll('destroy');
        atualizaFiltro();
    });
});
</script>
@endsection
@stop
