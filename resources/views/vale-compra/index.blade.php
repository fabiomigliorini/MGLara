@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
    {!! titulo(null, 'Vale Compras', null) !!}
    <li class='active'>
        <small>
            <a title="Novo" href="{{ url("vale-compra/create") }}"><i class="glyphicon glyphicon-plus"></i></a>
            &nbsp;
            <a class="" data-toggle="collapse" href="#div-filtro" aria-expanded="false" aria-controls="div-filtro"><span class='glyphicon glyphicon-search'></span></a>
        </small>
    </li>
</ol>
<div class="clearfix"></div>
<div class='collapse' id='div-filtro'>
    <div class='well well-sm' style="padding:9px 0">
    {!! Form::model(
        $parametros,
        [
            'route' => 'vale-compra.index',
            'method' => 'GET',
            'class' => 'form-horizontal',
            'id' => 'vale-compra-search',
            'role' => 'search',
            'autocomplete' => 'off']
        )
    !!}
      <div class="clearfix">
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('codvalecompra', '#', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-5">{!! Form::number('codvalecompra', null, ['class' => 'form-control text-right', 'placeholder' => '#', 'step'=>1, 'min'=>1]) !!}</div>
            </div>
            <div class="form-group">
                {!! Form::label('codpessoafavorecido', 'Favorecido', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-9">{!! Form::select2Pessoa('codpessoafavorecido', null, ['class' => 'form-control', 'placeholder' => 'Favorecido']) !!}</div>
            </div>
            <div class="form-group">
                {!! Form::label('codvalecompramodelo', 'Modelo', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-9">{!! Form::select2ValeCompraModelo('codvalecompramodelo', null, ['class' => 'form-control']) !!}</div>
            </div>
            <div class="form-group">
                {!! Form::label('ativo', 'Ativo', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-9">{!! Form::select2Ativo('ativo', null, ['class'=> 'form-control', 'id' => 'ativo']) !!}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('codpessoa', 'Pessoa', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-9">{!! Form::select2Pessoa('codpessoa', null, ['class' => 'form-control', 'placeholder' => 'Pessoa']) !!}</div>
            </div>
            <div class="form-group">
                {!! Form::label('aluno', 'Aluno', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-9">{!! Form::text('aluno', null, ['class' => 'form-control', 'step' => 1, 'placeholder' => 'aluno']) !!}</div>
            </div>
            <div class="form-group">
                {!! Form::label('turma', 'Turma', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-9">{!! Form::text('turma', null, ['class' => 'form-control', 'step' => 1, 'placeholder' => 'Turma']) !!}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                {!! Form::label('codusuariocriacao', 'Usuário', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-9">{!! Form::select2Usuario('codusuariocriacao', null, ['class'=> 'form-control', 'id' => 'codusuariocriacao']) !!}</div>
            </div>
            <div class="form-group">
                {!! Form::label('criacao_de', 'De', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-9">{!! Form::datetimeLocal('criacao_de', null, ['class'=> 'form-control', 'id' => 'criacao_de']) !!}</div>
            </div>
            <div class="form-group">
                {!! Form::label('criacao_ate', 'Até', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-9">{!! Form::datetimeLocal('criacao_ate', null, ['class'=> 'form-control', 'id' => 'criacao_ate']) !!}</div>
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
      <a href="{{ url('vale-compra', $row->codvalecompra) }}">
        <div class="list-group-item">
          <div class="row item">
            <div class="col-md-1">
              <small class="text-muted">
                {{ formataCodigo($row->codvalecompra) }}
              </small>
            </div>
            <div class="col-md-3">
              {{ $row->aluno }}
              <div class="pull-right"> {{ $row->turma }}</div>
              <div class="clearfix">
              {!! inativo($row->inativo) !!}
              </div>
            </div>
            <div class="col-md-1 text-right">
              {{ formataNumero($row->total) }}
            </div>
            <div class="col-md-2">
              <a href="{{ url('pessoa', $row->codpessoa) }}">{{ $row->Pessoa->fantasia }}</a>
            </div>
            <div class="col-md-3">
                <a href="{{ url('pessoa', $row->codpessoafavorecido) }}">{{ $row->PessoaFavorecido->fantasia }}</a>
                <div class="pull-right">
                  <a href="{{ url('vale-compra-modelo', $row->codvalecompramodelo ) }}">{{ $row->ValeCompraModelo->modelo }}</a>
                </div>
            </div>
            <div class="col-md-2 small text-muted">
              {{ formataData($row->criacao, 'L') }}
              <div class="pull-right">
                  {{ $row->UsuarioCriacao->usuario }}
              </div>
            </div>
          </div>
          <div class="row item">
            <div class="col-md-12">
              <small class="text-muted">
                {!! nl2br($row->observacoes) !!}
              </small>
            </div>
          </div>
        </div>
      </a>
    @endforeach
    @if (count($model) === 0)
        <h3>Nenhum registro encontrado!</h3>
    @endif
  </div>
  <?php echo $model->appends(Request::session()->get('vale-compra.index'))->render();?>
</div>
@section('inscript')
<script type="text/javascript">
function atualizaFiltro()
{
    scroll();
    var frmValues = $('#vale-compra-search').serialize();
    $.ajax({
        type: 'GET',
        url: baseUrl + '/vale-compra',
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
    $("#vale-compra-search").on("change", function (event) {
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