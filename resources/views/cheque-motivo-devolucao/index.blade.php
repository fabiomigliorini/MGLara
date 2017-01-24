@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
    {!! titulo(null, 'Motivos de Devolução', null) !!}
    <li class='active'>
        <small>
            <a title="Novo" href="{{ url("cheque-motivo-devolucao/create") }}"><i class="glyphicon glyphicon-plus"></i></a>
            &nbsp;
            <a class="" data-toggle="collapse" href="#div-filtro" aria-expanded="false" aria-controls="div-filtro"><span class='glyphicon glyphicon-search'></span></a>
        </small>
    </li>
</ol>
<div class="clearfix"></div>
<div class='collapse' id='div-filtro'>
    <div class='well well-sm' style="padding:9px 0">
    {!! Form::model(
        Request::session()->get('cheque-motivo-devolucao.index'),
        [
            'route' => 'cheque-motivo-devolucao.index',
            'method' => 'GET',
            'class' => 'form-horizontal',
            'id' => 'cheque-motivo-devolucao-search',
            'role' => 'search',
            'autocomplete' => 'off']
        )
    !!}
      <div class="clearfix">
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('codchequemotivodevolucao', 'Código', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-md-2">{!! Form::text('codchequemotivodevolucao', null, ['class' => 'form-control', 'placeholder' => '#Código']) !!}</div>
            </div>
            <div class="form-group">
                {!! Form::label('numero', 'Número', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-md-2">{!! Form::text('numero', null, ['class' => 'form-control', 'placeholder' => 'Número']) !!}</div>
            </div>
            <div class="form-group">
                {!! Form::label('chequemotivodevolucao', 'Descrição', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-md-2">{!! Form::text('chequemotivodevolucao', null, ['class' => 'form-control', 'placeholder' => 'Descrição']) !!}</div>
            </div>
            <div class="form-group">
              <div class="col-md-12"><button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i> Buscar</button></div>
            </div>
        </div>
      </div>
    </div>
    {!! Form::close() !!}
</div>

<div id="registros">
  <div class="list-group list-group-striped list-group-hover" id="items">
    @foreach($model as $i => $row)
      <a href="{{ url('cheque-motivo-devolucao', $row->codchequemotivodevolucao) }}">
        <div class="list-group-item">
          <!--
            <div class="row item">
                <div class="col-md-1 small text-muted">
                  {{ formataCodigo($row->codchequemotivodevolucao) }}
                </div>
                  <div class="col-md-1 text-right">
                    {{ formataNumero($row->numero, 0)  }}
                  </div>
                <div class="col-md-3 small text-muted">
                  {!! $row->chequemotivodevolucao  !!}
                </div>
            </div>
          -->
          teste
        </div>
        </a>
    @endforeach
    @if (count($model) === 0)
        <h3>Nenhum registro encontrado!</h3>
    @endif
  </div>
  <?php echo $model->appends(Request::session()->get('cheque-motivo-devolucao.index'))->render();?>
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