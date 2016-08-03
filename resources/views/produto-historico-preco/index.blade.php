@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li>
                <a href=""><span class="glyphicon glyphicon-print"></span> Relatório</a>
            </li> 
        </ul>
    </div>
</nav>
<h1 class="header">Histórico de Preços</h1>
<hr>
<div class="search-bar">
{!! Form::model(Request::session()->get('produto-historico-preco.index'), [
'route' => 'produto-historico-preco.index', 
'method' => 'GET', 
'class' => 'form-inline', 
'id' => 'produto-historico-preco-search', 
'role' => 'search', 
'autocomplete' => 'off'
])!!}

    <div class="form-group">
        {!! Form::text('id', null, ['class' => 'form-control search-cod', 'placeholder' => '#']) !!}
    </div>
    
    <div class="form-group">
        {!! Form::text('produto', null, ['class' => 'form-control', 'placeholder' => 'Produto']) !!}
    </div>
    
    <div class="form-group">
        {!! Form::text('referencia', null, ['class' => 'form-control', 'placeholder' => 'Referencia']) !!}
    </div>

    <strong>Alteração</strong>
    <div class="form-group">
        {!! Form::date('alteracao_de', null, ['class' => 'form-control', 'id' => 'alteracao_de', 'placeholder' => 'De']) !!}
        {!! Form::date('alteracao_ate', null, ['class' => 'form-control', 'id' => 'alteracao_ate', 'placeholder' => 'Até']) !!}
    </div>
    
    <div class="form-group">
        {!! Form::select2Marca('codmarca', null, ['class' => 'form-control','id'=>'codmarca', 'style'=>'width:160px']) !!}
    </div>

    <div class="form-group">
        {!! Form::select2Usuario('codusuario', null, ['class'=> 'form-control', 'id' => 'codusuario', 'style'=>'width:160px', 'placeholder' => 'Usuário']) !!}
    </div>

    <button type="submit" class="btn btn-default">Buscar</button>
{!! Form::close() !!}
</div>

<br>
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

});
</script>
@endsection
@stop