@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li>
                <a href="{{ url("estoque-saldo-conferencia/create") }}"><span class="glyphicon glyphicon-plus"></span> Nova</a>
            </li> 
        </ul>
    </div>
</nav>
<?php
    use MGLara\Models\Usuario;
    use MGLara\Models\EstoqueLocal;
    $usuarios = [''=>''] + Usuario::orderBy('usuario', 'ASC')->lists('usuario', 'codusuario')->all();
    $codestoquelocal = [''=>''] + EstoqueLocal::orderBy('estoquelocal', 'ASC')->lists('estoquelocal', 'codestoquelocal')->all();
?>
<h1 class="header">{!! titulo(NULL, 'Conferência Saldo de Estoque', NULL) !!}
    <a class="btn btn-primary pull-right" role="button" data-toggle="collapse" href="#div-filtro" aria-expanded="false" aria-controls="div-filtro">
        <span class='glyphicon glyphicon-search'></span>
    </a>
 </h1>
<hr>
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
                <div class="col-md-6">{!! Form::select('codestoquelocal', $codestoquelocal, null, ['class'=> 'form-control', 'id' => 'codestoquelocal']) !!}</div>            
            </div>              
            <div class="form-group">
                {!! Form::label('fiscal', 'Físico/Fiscal', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-md-6">{!! Form::select('fiscal', [''=>'', 'false'=>'Fisico', 'true'=>'Fiscal'],  null, ['class' => 'form-control', 'id' => 'fiscal']) !!}</div>
            </div>
            <div class="form-group">
                {!! Form::label('codusuario', 'Usuário', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-md-6">{!! Form::select('codusuario', $usuarios, null, ['class'=> 'form-control', 'id' => 'codusuario']) !!}</div>
            </div>         
        </div>

        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('data_de', 'Ajuste', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-md-10">
                    {!! Form::date('data_de', null, ['class' => 'form-control', 'id' => 'data_de', 'placeholder' => 'De', 'style'=>'width:45%; float:left; margin-right:10px']) !!}
                    {!! Form::date('data_ate', null, ['class' => 'form-control', 'id' => 'data_ate', 'placeholder' => 'Até', 'style'=>'width:45%; float:left']) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('criacao_de', 'Criação', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-md-10">
                    {!! Form::date('criacao_de', null, ['class' => 'form-control', 'id' => 'criacao_de', 'placeholder' => 'De', 'style'=>'width:45%; float:left; margin-right:10px']) !!}
                    {!! Form::date('criacao_ate', null, ['class' => 'form-control', 'id' => 'criacao_ate', 'placeholder' => 'Até', 'style'=>'width:45%; float:left']) !!}
                </div>
            </div>
            <div class="col-md-offset-2 col-md-10">
                <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i> Buscar</button>
            </div>

        </div>
        {!! Form::close() !!}
        <div class="clearfix"></div>
    </div>
</div>
        
<br>
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
    var frmValues = $('#estoque-saldo-conferencia-search').serialize() + '&page=1';
    $.ajax({
        type: 'GET',
        url: baseUrl + '/estoque-saldo-conferencia',
        data: frmValues
    })
    .done(function (data) {
        $('#items').html(jQuery(data).find('#items').html(function() {
            $('#items').infinitescroll({ state: { currPage: 1, isDone: false}});
        }));
    })
    .fail(function () {
        console.log('Erro no filtro');
    });
}

$(document).ready(function() {
    $("#estoque-saldo-conferencia-search").on("change", function (event) {
        atualizaFiltro();
    });

    $('#codusuario').select2({
        placeholder: 'Usuário',
        allowClear:true,
        closeOnSelect:true
    });

    $('#fiscal').select2({
        placeholder: 'Fiscal',
        allowClear:true,
        closeOnSelect:true
    });

    $('#codestoquelocal').select2({
        placeholder: 'Local Estoque',
        allowClear:true,
        closeOnSelect:true
    });



});
</script>
@endsection
@stop