@extends('layouts.default')
@section('content')
<?php
    use MGLara\Models\Usuario;
    use MGLara\Models\EstoqueLocal;
    $usuarios = [''=>''] + Usuario::orderBy('usuario', 'ASC')->lists('usuario', 'codusuario')->all();
    $codestoquelocal = [''=>''] + EstoqueLocal::orderBy('estoquelocal', 'ASC')->lists('estoquelocal', 'codestoquelocal')->all();
?>
<ol class="breadcrumb header">{!! titulo(NULL, 'Conferência Saldo de Estoque', NULL) !!}
    <li class='active'>
        <small>
            <a title="Nova" href="{{ url("estoque-saldo-conferencia/create") }}"><span class="glyphicon glyphicon-plus"></span></a>
            &nbsp;
            <a title="Filtro" data-toggle="collapse" href="#div-filtro" aria-expanded="false" aria-controls="div-filtro"><span class='glyphicon glyphicon-search'></span></a>
        </small>
    </li>
</ol>
<div class="clearfix"></div>
<div class='collapse' id='div-filtro'>
    <div class='well well-sm' style="padding:9px 0">
        {!! Form::model(Request::session()->get('estoque-saldo-conferencia.index'), 
            [
                'route' => 'estoque-saldo-conferencia.index', 
                'method' => 'GET', 
                'class' => 'form-horizontal', 
                'id' => 'estoque-saldo-conferencia-search', 
                'role' => 'search', 
                'autocomplete' => 'off'
            ]
        )!!}
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('codproduto', 'Produto', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-md-10">{!! Form::select2Produto('codproduto', null, ['class' => 'form-control','id'=>'codproduto', 'somenteAtivos'=>'9']) !!}</div>
            </div>
            <div class="form-group">
                {!! Form::label('codestoquelocal', 'Local', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-md-4">{!! Form::select2EstoqueLocal('codestoquelocal', null, ['class'=> 'form-control', 'id' => 'codestoquelocal', 'placeholder' => 'Estoque Local']) !!}</div>            
            </div>              
            <div class="form-group">
                {!! Form::label('fiscal', 'Físico/Fiscal', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-md-4">{!! Form::select('fiscal', [''=>'', 'false'=>'Fisico', 'true'=>'Fiscal'],  null, ['class' => 'form-control', 'id' => 'fiscal']) !!}</div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('codusuario', 'Usuário', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-md-4">{!! Form::select2Usuario('codusuario', null, ['class'=> 'form-control', 'id' => 'codusuario', 'placeholder' => 'Usuário']) !!}</div>
            </div>         
            <div class="form-group">
                {!! Form::label('data_de', 'Ajuste', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-md-10">
                    {!! Form::date('data_de', null, ['class' => 'form-control', 'id' => 'data_de', 'placeholder' => 'De', 'style'=>'width:160px; float:left; margin-right:10px']) !!}
                    {!! Form::date('data_ate', null, ['class' => 'form-control', 'id' => 'data_ate', 'placeholder' => 'Até', 'style'=>'width:160px; float:left']) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('criacao_de', 'Criação', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-md-10">
                    {!! Form::date('criacao_de', null, ['class' => 'form-control pull-left', 'id' => 'criacao_de', 'placeholder' => 'De', 'style'=>'width:160px; margin-right:10px']) !!}
                    {!! Form::date('criacao_ate', null, ['class' => 'form-control pull-left', 'id' => 'criacao_ate', 'placeholder' => 'Até', 'style'=>'width:160px;']) !!}
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
  <div class="list-group list-group-striped list-group-hover" id="items">
    @foreach($model as $row)
      <div class="list-group-item @if(!empty($row->inativo)) bg-danger @endif">
        <div class="row item">
            <div class="col-md-1 small text-muted">
                {{ formataCodigo($row->codestoquesaldoconferencia)}}
            </div>  
            <div class="col-md-4">
                <a class="" href="{{ url("produto/{$row->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->codproduto}") }}">
                    {{ $row->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->Produto->produto }}
                </a>
                »
                @if (!empty($row->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->variacao))
                    {{ $row->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->variacao }}
                @else
                    <i class='text-muted'>{ Sem Variação }</i>
                @endif
                @if (!empty($row->observacoes))
                <br>
                <small class="text-muted">
                    {{ $row->observacoes }}
                </small>
                @endif
            </div>  
            <div class="col-md-1">
                <a class="" href="{{ url("estoque-local/{$row->EstoqueSaldo->EstoqueLocalProdutoVariacao->codestoquelocal}") }}">
                    {{ $row->EstoqueSaldo->EstoqueLocalProdutoVariacao->EstoqueLocal->estoquelocal }}
                </a>          
            </div>  
            <div class="col-md-1">
                <a class="" href="{{ url("estoque-saldo/{$row->codestoquesaldo}") }}">
                    {{ ($row->EstoqueSaldo->fiscal)?'Fiscal':'Físico' }}
                </a>
            </div>
            <div class="col-md-1 text-right">
                {{ formataNumero($row->quantidadeinformada, 3) }}
            </div>
            <div class="col-md-1 text-right">
                {{ formataNumero($row->customedioinformado, 6) }}
            </div>
            <div class="col-md-1 small">
                {{ formataData($row->data, 'C') }}
            </div>
            <div class="col-md-2 small text-muted">
                {{ $row->UsuarioCriacao->usuario }} 
                <div class='pull-right'>
                    {{ formataData($row->criacao, 'L') }}
                </div>
            </div>  
        </div>
      </div>    
    @endforeach
    @if (count($model) === 0)
        <h3>Nenhuma conferência encontrada!</h3>
    @endif    
  </div>
  <?php echo $model->appends(Request::session()->get('estoque-saldo-conferencia.index'))->render();?>
</div>
@section('inscript')
<script type="text/javascript">
function atualizaFiltro()
{
    scroll();
    var frmValues = $('#estoque-saldo-conferencia-search').serialize();
    $.ajax({
        type: 'GET',
        url: baseUrl + '/estoque-saldo-conferencia',
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
    $("#estoque-saldo-conferencia-search").on("change", function (event) {
        $('#items').infinitescroll('destroy');
        atualizaFiltro();
    }).on('submit', function (event){
        event.preventDefault();
        $('#items').infinitescroll('destroy');
        atualizaFiltro();
    });
    
    $('#fiscal').select2({
        placeholder: 'Fiscal',
        allowClear:true,
        closeOnSelect:true
    });

    var data_de = $('#data_de').val();
    if(data_de.length > 0 ){
        $('#data_ate').attr('min', data_de);
    }
    $('#data_de').on('change', function(e) {
        e.preventDefault();
        var valor = $(this).val();
        if(valor.length === 0 ) {
            $('#data_ate').empty();
            $('#data_ate').attr('min', '');
        } else {
            $('#data_ate').attr('min', valor);
        }
    });
    
    var data_ate = $('#data_ate').val();
    if(data_ate.length > 0){
        $('#data_de').attr('max', data_ate);
    }
    $('#data_ate').on('change', function(e) {        
        e.preventDefault();
        var valor = $(this).val();
        if(valor.length === 0 ) {
            $('#data_de').empty();
            $('#data_de').attr('max', '');
        } else {
            $('#data_de').attr('max', valor);
        }
    });

    var criacao_de = $('#criacao_de').val();
    if(criacao_de.length > 0 ){
        $('#criacao_ate').attr('min', criacao_de);
    }
    $('#criacao_de').on('change', function(e) {
        e.preventDefault();
        var valor = $(this).val();
        if(valor.length === 0 ) {
            $('#criacao_ate').empty();
            $('#criacao_ate').attr('min', '');
        } else {
            $('#criacao_ate').attr('min', valor);
        }
        
    });
    
    var criacao_ate = $('#criacao_ate').val();
    if(criacao_ate.length > 0){
        $('#criacao_de').attr('max', criacao_ate);
    }
    $('#criacao_ate').on('change', function(e) {        
        e.preventDefault();
        var valor = $(this).val();
        if(valor.length === 0 ) {
            $('#criacao_de').empty();
            $('#criacao_de').attr('max', '');
        } else {
            $('#criacao_de').attr('max', valor);
        }
    });
    
    
});
</script>
@endsection
@stop