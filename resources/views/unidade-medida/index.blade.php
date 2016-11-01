@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
    {!! titulo(null, 'Unidades de Medida', null) !!}
    <li class='active'>
        <small>
            <a title="Nova Unidade de Medida" href="{{ url("unidade-medida/create") }}"><i class="glyphicon glyphicon-plus"></i></a>
            &nbsp;
            <a class="" data-toggle="collapse" href="#div-filtro" aria-expanded="false" aria-controls="div-filtro"><span class='glyphicon glyphicon-search'></span></a>
        </small>
    </li>   
</ol>
<div class="clearfix"></div>
<div class='collapse' id='div-filtro'>
    <div class='well well-sm' style="padding:9px 0">
    {!! Form::model(
        Request::session()->get('unidade-medida.index'), 
        [
            'route' => 'unidade-medida.index', 
            'method' => 'GET', 
            'class' => 'form-horizontal', 
            'id' => 'unidade-medida-search', 
            'role' => 'search', 
            'autocomplete' => 'off']
        )
    !!}
        <div class="col-md-2">
            <div class="form-group">
                {!! Form::label('codunidademedida', '#', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-md-8">{!! Form::text('codunidademedida', null, ['class' => 'form-control', 'placeholder' => '#']) !!}</div>
            </div>    
        </div>    

        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('unidademedida', 'Unidade de medida', ['class' => 'col-sm-4 control-label']) !!}
                <div class="col-md-8">{!! Form::text('unidademedida', null, ['class' => 'form-control', 'placeholder' => 'Unidade de medida']) !!}</div>
            </div>
        </div>
        <div class="col-md-2">      
            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i> Buscar</button>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
    {!! Form::close() !!}
</div>
<div id="registros">
  <div class="list-group list-group-striped list-group-hover" id="items">
    @foreach($model as $row)
      <div class="list-group-item">
        <div class="row item">
            <div class="col-md-1 small text-muted">
                {{ formataCodigo($row->codunidademedida) }}
            </div>                            
            <div class="col-md-3">
                <a href="{{ url("unidade-medida/$row->codunidademedida") }}">{{ $row->unidademedida }}</a>
            </div>                            
            <div class="col-md-2">
                <a href="{{ url("unidade-medida/$row->codunidademedida") }}">{{ $row->sigla }}</a>
            </div>                            
        </div>
      </div>    
    @endforeach
    @if (count($model) === 0)
        <h3>Nenhum registro encontrado!</h3>
    @endif    
  </div>
  <?php echo $model->appends(Request::session()->get('unidade-medida.index'))->render();?>
</div>
@section('inscript')
<script type="text/javascript">
function atualizaFiltro()
{
    scroll();
    var frmValues = $('#unidade-medida-search').serialize();
    $.ajax({
        type: 'GET',
        url: baseUrl + '/unidade-medida',
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
    $("#unidade-medida-search").on("change", function (event) {
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