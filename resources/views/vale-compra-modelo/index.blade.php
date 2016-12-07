@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
    {!! titulo(null, 'Tipos de produto', null) !!}
    <li class='active'>
        <small>
            <a title="Novo Tipo" href="{{ url("tipo-produto/create") }}"><i class="glyphicon glyphicon-plus"></i></a>
            &nbsp;
            <a class="" data-toggle="collapse" href="#div-filtro" aria-expanded="false" aria-controls="div-filtro"><span class='glyphicon glyphicon-search'></span></a>
        </small>
    </li>   
</ol>
<div class="clearfix"></div>
<div class='collapse' id='div-filtro'>
    <div class='well well-sm' style="padding:9px 0">
    {!! Form::model(
        Request::session()->get('tipo-produto.index'), 
        [
            'route' => 'tipo-produto.index', 
            'method' => 'GET', 
            'class' => 'form-horizontal', 
            'id' => 'tipo-produto-search', 
            'role' => 'search', 
            'autocomplete' => 'off']
        )
    !!}
        <div class="col-md-2">
            <div class="form-group">
                {!! Form::label('codtipoproduto', '#', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-md-8">{!! Form::text('codtipoproduto', null, ['class' => 'form-control', 'placeholder' => '#']) !!}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('tipoproduto', 'Tipo', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-md-8">{!! Form::text('tipoproduto', null, ['class' => 'form-control', 'placeholder' => 'Tipo']) !!}</div>
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
                {{ formataCodigo($row->codtipoproduto) }}
            </div>                            
            <div class="col-md-4">
                <a href="{{ url("tipo-produto/$row->codtipoproduto") }}">{{ $row->tipoproduto }}</a>
            </div>                            
            <div class="col-md-4">
            
            </div>                            
        </div>
      </div>    
    @endforeach
    @if (count($model) === 0)
        <h3>Nenhum registro encontrado!</h3>
    @endif    
  </div>
  <?php echo $model->appends(Request::session()->get('tipo-produto.index'))->render();?>
</div>
@section('inscript')
<script type="text/javascript">
function atualizaFiltro()
{
    scroll();
    var frmValues = $('#tipo-produto-search').serialize();
    $.ajax({
        type: 'GET',
        url: baseUrl + '/tipo-produto',
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
    $("#tipo-produto-search").on("change", function (event) {
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