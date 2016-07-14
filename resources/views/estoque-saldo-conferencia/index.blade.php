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
<h1 class="header">{!! 
    titulo(
        NULL ,
        'Conferência Saldo de Estoque',
        NULL
    ) 
!!}
 </h1>
<hr>
<!--
<div class="search-bar">
{!! Form::model(
    Request::session()->get('estoque-saldo-conferencia.index'), 
    [
        'route' => 'estoque-saldo-conferencia.index', 
        'method' => 'GET', 
        'class' => 'form-inline', 
        'id' => 'estoque-saldo-conferencia-search', 
        'role' => 'search', 
        'autocomplete' => 'off'
    ]
)!!}
    <div class="form-group">
        {!! Form::text('codsecaoproduto', null, ['class' => 'form-control search-cod', 'placeholder' => '#']) !!}
    </div>
    <div class="form-group">
        {!! Form::text('secaoproduto', null, ['class' => 'form-control', 'placeholder' => 'Seção']) !!}
    </div>
    <div class="form-group">
        {!! Form::select(
            'inativo', [
                '9' => 'Todos', 
                '1' => 'Ativos', 
                '2' => 'Inativos'
            ], 
            Request::session()->get('estoque-saldo-conferencia.index.inativo'),
            ['class' => 'form-control']
        ) !!}
    </div>      
    <button type="submit" class="btn btn-default">Buscar</button>
{!! Form::close() !!}
</div>
-->
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
                    {{ $row->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->Produto->produto}}
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
        <h3>Nenhum registro encontrado!</h3>
    @endif    
  </div>
  <?php echo $model->appends(Request::all())->render();?>
</div>
@section('inscript')
<script type="text/javascript">
$(document).ready(function() {

    /*
    $("#estoque-saldo-conferencia-search").on("change", function (event) {
        var $this = $(this);
        var frmValues = $this.serialize();
        $.ajax({
            type: 'GET',
            url: baseUrl + '/estoque-saldo-conferencia',
            data: frmValues
        })
        .done(function (data) {
            $('#items').html(jQuery(data).find('#items').html()); 
        })
        .fail(function () {
            console.log('Erro no filtro');
        });
        event.preventDefault(); 
    });       
    */
});
</script>
@endsection
@stop