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
        Request::session()->get('vale-compra.index'), 
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
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('codvalecompramodelo', 'Modelo', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-md-7">{!! Form::select2ValeCompraModelo('codvalecompramodelo', null, ['class' => 'form-control']) !!}</div>
            </div>    
            <div class="form-group">
                {!! Form::label('codpessoafavorecido', 'Favorecido', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-md-10">{!! Form::select2Pessoa('codpessoafavorecido', null, ['class' => 'form-control', 'placeholder' => 'Favorecido']) !!}</div>
            </div>
        </div>    
        <div class="col-md-3">
            <div class="form-group">
                {!! Form::label('turma', 'Turma', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-9">{!! Form::text('turma', null, ['class' => 'form-control', 'step' => 1, 'placeholder' => 'Turma']) !!}</div>
            </div>    
            <div class="form-group">
                {!! Form::label('aluno', 'Aluno', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-9">{!! Form::text('aluno', null, ['class' => 'form-control', 'step' => 1, 'placeholder' => 'aluno']) !!}</div>
            </div>    
        </div>
        <div class="col-md-3">
            <div class="form-group">
                {!! Form::label('ativo', 'Ativo', ['class' => 'col-sm-3 control-label']) !!}
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
<div id="registros">
  <div class="list-group list-group-striped list-group-hover" id="items">
    @foreach($model as $i => $row)
      <div class="list-group-item">
        <div class="row item">
            <div class="col-md-1 small text-muted">
                {{ formataCodigo($row->codvalecompra) }}
            </div>                            
            <div class="col-md-4">
              @if (!empty($row->inativo))
                <s><a href="{{ url('vale-compra', $row->codvalecompra) }}">{{ $row->modelo }}</a></s>
                <span class='text-danger'>
                    inativo desde {{ formataData($row->inativo) }}
                </span>
              @else
                <a href="{{ url('vale-compra', $row->codvalecompra) }}">{{ $row->modelo }}</a>
              @endif
              <div class="pull-right">
                {{ formataNumero($row->total) }}
              </div>                            
            </div>                            
            <div class="col-md-2">
                <a href="{{ url('pessoa', $row->codpessoafavorecido) }}">{{ $row->PessoaFavorecido->fantasia }}</a>
            </div>                            
            <div class="col-md-2">
                {{ $row->turma }} / {{ $row->turma }}
            </div>                            
            <div class="col-md-3 small text-muted">
              {!! nl2br($row->observacoes) !!}
            </div>                            
        </div>
      </div>    
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