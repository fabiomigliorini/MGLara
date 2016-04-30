@extends('layouts.default')
@section('content')
<?php
    use MGLara\Models\Usuario;
    $usuarios = [''=>''] + Usuario::lists('usuario', 'codusuario')->all();
?>
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li>
                <a href="{{ url("#") }}"><span class="glyphicon glyphicon-print"></span> Relatório</a>
            </li> 
        </ul>
    </div>
</nav>
<h1 class="header">Histórico de Preços</h1>
<hr>
<div class="search-bar">
{!! Form::model(Request::all(), ['route' => 'produto-historico-preco.index', 'method' => 'GET', 'class' => 'form-inline', 'id' => 'produto-historico-preco-search', 'role' => 'search', 'autocomplete' => 'off'])!!}
    <div class="form-group">
        {!! Form::text('codproduto-historico-preco', null, ['class' => 'form-control search-cod', 'placeholder' => '#']) !!}
    </div>
    <div class="form-group">
        {!! Form::text('produto', null, ['class' => 'form-control', 'placeholder' => 'Produto']) !!}
    </div>
    <div class="form-group">
        {!! Form::text('referencia', null, ['class' => 'form-control', 'placeholder' => 'Referencia']) !!}
    </div>
    <strong>Alteração</strong>
    <div class="form-group">
        {!! Form::text('de', null, ['class' => 'form-control between', 'id' => 'de', 'placeholder' => 'De']) !!}
        {!! Form::text('ate', null, ['class' => 'form-control between', 'id' => 'ate', 'placeholder' => 'Até']) !!}
    </div>
    <div class="form-group">
        {!! Form::text('codmarca', null, ['class' => 'form-control','id'=>'codmarca', 'style'=>'width:140px', 'placeholder' => 'Marca']) !!}
    </div>
    <div class="form-group">
        {!! Form::select('codusuario', $usuarios, ['class' => 'form-control'],['id'=>'codusuario', 'style'=>'width:140px']) !!}
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
                <div class="col-md-2"><small>{{ formataCodigo($row->codprodutohistoricopreco, 6) }}</small></div>
                <div class="col-md-10"><small><a href="{{ url("produto/$row->codproduto") }}">{{ $row->Produto->produto }}</a></small></div>
            </div>                            
            <div class="col-md-3">
                <div class="col-md-3">
                    <small class="span3 text-center">
                        @if(isset($row->codprodutoembalagem))
                            {{ $row->ProdutoEmbalagem->UnidadeMedida->sigla }}
                            " C/" {{ formataNumero($row->ProdutoEmbalagem->quantidade, 0) }}
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
  <?php echo $model->appends(Request::all())->render();?>
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
$(document).ready(function() {
    $('ul.pagination').removeClass('hide');
    $('#produto-historico-preco-search').change(function() {
        this.submit();
    });
    $('#codmarca').select2({
        minimumInputLength:1,
        allowClear:true,
        closeOnSelect:true,
        placeholder:'Marca',
        formatResult:function(item) {
            var markup = "<div class='row-fluid'>";
            markup    += item.marca;
            markup    += "</div>";
            return markup;
        },
        formatSelection:function(item) { 
            return item.marca; 
        },
        ajax:{
            url:baseUrl+"/marca/ajax",
            dataType:'json',
            quietMillis:500,
            data:function(term,page) { 
                return {q: term}; 
            },
            results:function(data,page) {
                var more = (page * 20) < data.total;
                return {results: data.items};
            }
        },
        initSelection:function (element, callback) {
            $.ajax({
                type: "GET",
                url: baseUrl+"/marca/ajax",
                data: "id="+$('#codmarca').val(),
                dataType: "json",
                success: function(result) { callback(result); }
            });
        },
        width:'resolve'
    });
    $('#codusuario').select2({
        placeholder: 'Usuário',
        allowClear:true,
        closeOnSelect:true
    })<?php echo (app('request')->input('codusuario') ? ".select2('val'," .app('request')->input('codusuario').");" : ';'); ?>    
    $('#de, #ate').datetimepicker({
        useCurrent: false,
        showClear: true,
        locale: 'pt-br',
        format: 'DD/MM/YY'
    });
    $(document).on('dp.change', '#de, #ate, #codmarca', function() {
        $('#produto-historico-preco-search').submit();
    });    
});
</script>
@endsection
@stop