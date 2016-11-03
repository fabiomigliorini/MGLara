@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
    {!! titulo(null, 'Grupos de Usuários', null) !!}
    <li class='active'>
        <small>
            <a title="Novo Grupo" href="{{ url('grupo-usuario/create') }}"><i class="glyphicon glyphicon-plus"></i></a>
            &nbsp;
            <a class="" data-toggle="collapse" href="#div-filtro" aria-expanded="false" aria-controls="div-filtro"><span class='glyphicon glyphicon-search'></span></a>
        </small>
    </li>   
</ol>
<div class="clearfix"></div>
<div class='collapse' id='div-filtro'>
    <div class='well well-sm' style="padding:9px 0">
  {!! Form::model(Request::session()->get('grupo-usuario.index'), [
    'route' => 'grupo-usuario.index', 
    'method' => 'GET', 
    'class' => 'form-horizontal',
    'id' => 'grupo-usuario-search',
    'role' => 'search',
    'autocomplete'=> 'off'
  ])!!}
        <div class="col-md-2">
            <div class="form-group">
                {!! Form::label('codgrupousuario', '#', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-md-10">{!! Form::text('codgrupousuario', null, ['class' => 'form-control', 'placeholder' => '#']) !!}</div>
            </div>
        </div>
        
        <div class="col-md-5">
            <div class="form-group">
                {!! Form::label('grupousuario', 'Grupo', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-md-10">{!! Form::text('grupousuario', null, ['class' => 'form-control', 'placeholder' => 'Grupo de usuário']) !!}</div>
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
          <div class="col-md-1">
              <a class="small text-muted" href="<?php echo url("grupo-usuario/$row->codgrupousuario");?>">{{formataCodigo($row->codgrupousuario)}}</a>
          </div>                            
          <div class="col-md-11">
            <a href="<?php echo url("grupo-usuario/$row->codgrupousuario");?>">{{$row->grupousuario}}</a>
          </div>                  
        </div>
      </div>    
    @endforeach
    @if (count($model) === 0)
        <h3>Nenhum registro encontrado!</h3>
    @endif    
  </div>
  <?php echo $model->appends(Request::session()->get('grupo-usuario.index'))->render();?>
</div>
@section('inscript')
<script type="text/javascript">
function atualizaFiltro()
{
    scroll();
    var frmValues = $("#grupo-usuario-search").serialize();
    $.ajax({
        type: 'GET',
        url: baseUrl + '/grupo-usuario',
        data: frmValues
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
    $("#grupo-usuario-search").on("change", function (event) {
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
