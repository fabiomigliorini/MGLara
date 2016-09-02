@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li>
                <a href="" id="relatorio"><span class="glyphicon glyphicon-print"></span> Relatório</a>
            </li> 
        </ul>
    </div>
</nav>
<h1 class="header">{!! titulo(NULL, 'Histórico de Preços', NULL) !!}
    <a class="btn btn-primary pull-right" role="button" data-toggle="collapse" href="#div-filtro" aria-expanded="false" aria-controls="div-filtro">
        <span class='glyphicon glyphicon-search'></span>
    </a>
 </h1>
<div class="clearfix"></div>
<div class='collapse' id='div-filtro'>
    <div class='well well-sm' style="padding:9px 0">
    {!! Form::model(Request::session()->get('produto-historico-preco.index'), [
        'route' => 'produto-historico-preco.index', 
        'method' => 'GET', 
        'class' => 'form-horizontal', 
        'id' => 'produto-historico-preco-search', 
        'role' => 'search', 
        'autocomplete' => 'off'
    ])!!}
        <div class="col-md-5">
            <div class="form-group">
                {!! Form::label('id', '#', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-4">{!! Form::text('id', null, ['class' => 'form-control', 'placeholder' => '#']) !!}</div>
            </div>

            <div class="form-group">
                {!! Form::label('produto', 'Produto', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-9">{!! Form::text('produto', null, ['class' => 'form-control', 'placeholder' => 'Produto']) !!}</div>
            </div>

            <div class="form-group">
                {!! Form::label('referencia', 'Referência', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-6">{!! Form::text('referencia', null, ['class' => 'form-control', 'placeholder' => 'Referência']) !!}</div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="form-group">
                {!! Form::label('codmarca', 'Marca', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-md-4">{!! Form::select2Marca('codmarca', null, ['class' => 'form-control','id'=>'codmarca', 'placeholder'=>'Marca']) !!}</div>
            </div>
            <div class="form-group">
                {!! Form::label('codusuario', 'Usuário', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-md-4">{!! Form::select2Usuario('codusuario', null, ['class'=> 'form-control', 'id' => 'codusuario', 'placeholder' => 'Usuário']) !!}</div>
            </div>        
            <div class="form-group">
                {!! Form::label('alteracao_de', 'Alteração', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-md-10">
                    {!! Form::datetimeLocal('alteracao_de', null, ['class' => 'form-control pull-left', 'id' => 'alteracao_de', 'placeholder' => 'De', 'style'=>'width:210px; margin-right:10px']) !!}
                    {!! Form::datetimeLocal('alteracao_ate', null, ['class' => 'form-control pull-left', 'id' => 'alteracao_ate', 'placeholder' => 'Até', 'style'=>'width:210px;']) !!}
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i> Buscar</button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
        <div class="clearfix"></div>    
    </div>
</div>
<div id="registros">
  <div class="list-group produto-historico-preco" id="items">
    @foreach($model as $row)
      <div class="list-group-item">
        <div class="row item">
            <div class="col-md-4">
                <div class="col-md-2"><small>{{ formataCodigo($row->codproduto, 6) }}</small></div>
                <div class="col-md-10"><small><a href="{{ url("produto/$row->codproduto") }}">{{ $row->Produto->produto }}</a></small></div>
            </div>                            
            <div class="col-md-3">
                <div class="col-md-3">
                    <small class="span3 text-center">
                        @if(isset($row->codprodutoembalagem))
                            {{ $row->ProdutoEmbalagem->UnidadeMedida->sigla }} /{{ formataNumero($row->ProdutoEmbalagem->quantidade, 0) }}
                        @else
                            {{ $row->Produto->UnidadeMedida->sigla }}
                        @endif
                    </small>                    
                </div>
                <div class="col-md-6">
                    {{ $row->Produto->referencia }}
                </div>
                <div class="col-md-3">
                    <a href="{{ url("marca/{$row->Produto->Marca->codmarca}") }}">{{ $row->Produto->Marca->marca }}</a>
                </div>
            </div>                            
            <div class="col-md-2 text-right">
                <b class="col-md-4 text-success">
                    <?php  
                    if (isset($row->ProdutoEmbalagem)) {
                        echo $row->ProdutoEmbalagem->preco;
                    } else {
                        echo formataNumero($row->Produto->preco);
                    }?>
                </b>
                <small class="col-md-4 text-warning">
                    {{ formataNumero($row->preconovo) }}
                </small>
                <small class="col-md-4 muted text-danger" style="text-decoration: line-through">
                    {{ formataNumero($row->precoantigo) }}
                </small>
            </div>
            
            <div class="col-md-3">
                <small class="col-md-4 muted">
                    <a href="{{ url("usuario/$row->codusuariocriacao") }}">{{ $row->UsuarioCriacao->usuario }}</a>
                </small>
                <small class="col-md-8 muted">
                    {{ formataData($row->alteracao, 'L') }}
                </small>
            </div>                            
        </div>
      </div>    
    @endforeach
    @if (count($model) === 0)
        <h3>Nenhum registro encontrado!</h3>
    @endif    
  </div>
  <?php echo $model->appends(Request::session()->get('produto-historico-preco.index'))->render();?>
</div>
@section('inscript')
<style type="text/css">
.between {
    width: 82px !important;
}
#produto-historico-preco-search .form-group {
    position: relative;
}
</style>
<script type="text/javascript">
function atualizaFiltro()
{
    scroll();
    var frmValues = $('#produto-historico-preco-search').serialize();
    $.ajax({
        type: 'GET',
        url: baseUrl + '/produto-historico-preco',
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
    $('#produto-historico-preco-search').change(function() {
        $('#items').infinitescroll('destroy');
        atualizaFiltro();
    }).on('submit', function (event){
        event.preventDefault();
        $('#items').infinitescroll('destroy');
        atualizaFiltro();
    });

    var alteracao_de = $('#alteracao_de').val();
    if(alteracao_de.length > 0 ){
        $('#alteracao_ate').attr('min', alteracao_de);
    }
    $('#alteracao_de').on('change', function(e) {
        e.preventDefault();
        var valor = $(this).val();
        if(valor.length === 0 ) {
            $('#alteracao_ate').empty();
            $('#alteracao_ate').attr('min', '');
        } else {
            $('#alteracao_ate').attr('min', valor);
        }
        
    });
    
    var alteracao_ate = $('#alteracao_ate').val();
    if(alteracao_ate.length > 0){
        $('#alteracao_de').attr('max', alteracao_ate);
    }
    $('#alteracao_ate').on('change', function(e) {        
        e.preventDefault();
        var valor = $(this).val();
        if(valor.length === 0 ) {
            $('#alteracao_de').empty();
            $('#alteracao_de').attr('max', '');
        } else {
            $('#alteracao_de').attr('max', valor);
        }
    });
    
    $('#relatorio').on('click', function(e){
        e.preventDefault();
        location.replace('produto-historico-preco/relatorio/?' + $('#produto-historico-preco-search').serialize());
    });
});
</script>
@endsection
@stop