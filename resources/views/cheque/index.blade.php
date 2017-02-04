@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
    {!! titulo(null, 'Cheque', null) !!}
    <li class='active'>
        <small>
            <a title="Novo" href="{{ url("cheque/create") }}"><i class="glyphicon glyphicon-plus"></i></a>
            &nbsp;
            <a class="" data-toggle="collapse" href="#div-filtro" aria-expanded="false" aria-controls="div-filtro"><span class='glyphicon glyphicon-search'></span></a>
        </small>
    </li>
</ol>
<div class="clearfix"></div>
<div class='collapse' id='div-filtro'>
    <div class='well well-sm' style="padding:9px 0">
    {!! Form::model(
        Request::session()->get('cheque.index'),
        [
            'route' => 'cheque.index',
            'method' => 'GET',
            'class' => 'form-horizontal',
            'id' => 'cheque-search',
            'role' => 'search',
            'autocomplete' => 'off']
        )
    !!}
      <div class="clearfix">
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('codcheque', '#', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-5">{!! Form::number('codcheque', null, ['class' => 'form-control text-right', 'placeholder' => '#', 'step'=>1, 'min'=>1]) !!}</div>
            </div>
            <div class="form-group">
                {!! Form::label('codbanco', 'Banco', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-9">{!! Form::select2Banco('codbanco', null, ['class'=> 'form-control', 'id' => 'codbanco']) !!}</div>
            </div>
            <div class="form-group">
                {!! Form::label('agencia', 'Agência', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-9">{!! Form::number('agencia', null, ['class' => 'form-control', 'step' => 1, 'placeholder' => '']) !!}</div>
            </div>
            <div class="form-group">
                {!! Form::label('numero', 'Número', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-9">{!! Form::number('numero', null, ['class' => 'form-control', 'step' => 1, 'placeholder' => '']) !!}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('codpessoa', 'Pessoa', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-9">{!! Form::select2Pessoa('codpessoa', null, ['class' => 'form-control', 'placeholder' => 'Pessoa']) !!}</div>
            </div>
            <div class="form-group">
                {!! Form::label('emitente', 'Emitente', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-9">{!! Form::text('emitente', null, ['class' => 'form-control', 'step' => 1, 'placeholder' => '']) !!}</div>
            </div>
            <div class="form-group">
                {!! Form::label('valor_de', 'Valor de', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-9">{!! Form::number('valor_de', null, ['class' => 'form-control', 'step' => 1, 'placeholder' => '']) !!}</div>
            </div>
            <div class="form-group">
                {!! Form::label('valor_ate', 'Valor Até', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-9">{!! Form::number('valor_ate', null, ['class' => 'form-control', 'step' => 1, 'placeholder' => '']) !!}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                {!! Form::label('indstatus', 'Status', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-9">{!! Form::select2('indstatus', $indstatus_descricao, null, ['class'=> 'form-control', 'id' => 'indstatus']) !!}</div>
            </div>
            <div class="form-group">
                {!! Form::label('vencimento_de', 'De', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-9">{!! Form::date('vencimento_de', null, ['class'=> 'form-control', 'id' => 'vencimento_de']) !!}</div>
            </div>
            <div class="form-group">
                {!! Form::label('vencimento_ate', 'Até', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-9">{!! Form::date('vencimento_ate', null, ['class'=> 'form-control', 'id' => 'vencimento_ate']) !!}</div>
            </div>
            <div class="form-group">
              <div class="col-md-4 col-md-offset-3"><button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i> Buscar</button></div>
            </div>
        </div>

      </div>
    </div>
    {!! Form::close() !!}
</div>

<div id="registros">
  <div class="list-group list-group-striped list-group-hover" id="items">
    @foreach($model as $i => $row)
        <div class="list-group-item">
            <div class="row item">
                <div class="col-md-1 small text-muted">
                    <a href="{{ url('cheque', $row->codcheque) }}">{{ formataCodigo($row->codcheque) }}</a>
                </div>
                <div class="col-md-1 text-muted">
                      {{ $row->Banco->banco  }} <br>
                      {{ $row->agencia  }}
                </div>
                <div class="col-md-1 text-right text-muted">
                    {{ $row->contacorrente  }} <br>
                      {{ formataNumero($row->numero, 0)  }}
                </div>
                <div class="col-md-5">
                      <b>
                  @if (!empty($row->codpessoa) )
                    <a href='{{ url('pessoa', $row->codpessoa) }}'>
                    {!!  $row->Pessoa->pessoa   !!}
                    </a><br>
                  @endif
                  </b>
                  <span class='text-muted'>
                  @foreach ($row->ChequeEmitenteS as $emit)
                    {{ $emit->emitente }} </br>
                  @endforeach
                  </span>
                </div>
                <div class="col-md-1 text-right">
                    <strong>
                    {{ formataNumero($row->valor)  }}
                    </strong>
                </div>
                <div class="col-md-1 text-muted text-center">
                    {{ formataData($row->emissao)  }}
                </div>
                <div class="col-md-1 text-center">
                    <strong>
                    {{ formataData($row->vencimento)  }}
                    </strong>
                </div>
                <div class="col-md-1">
                    <span class='label {{ $indstatus_class[$row->indstatus] }}'>
                     {{ $indstatus_descricao[$row->indstatus] }}
                     </span>
                </div>
            </div>
        </div>
    @endforeach
    @if (count($model) === 0)
        <h3>Nenhum registro encontrado!</h3>
    @endif
  </div>
  <?php echo $model->appends(Request::session()->get('cheque.index'))->render();?>
</div>

@section('inscript')
<script type="text/javascript">
function atualizaFiltro()
{
    scroll();
    var frmValues = $('#cheque-motivo-devolucao-search').serialize();
    $.ajax({
        type: 'GET',
        url: baseUrl + '/cheque-motivo-devolucao',
        data: frmValues,
        dataType: 'html'
    })
    .done(function (data) {
        $('#items').html(jQuery(data).find('#items').html());
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
    $("#cheque-motivo-devolucao-search").on("change", function (event) {
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