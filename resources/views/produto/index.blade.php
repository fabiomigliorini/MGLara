@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li>
                <a href="<?php echo url('produto/create');?>"><span class="glyphicon glyphicon-plus"></span> Novo</a>
            </li> 
        </ul>
    </div>
</nav>
<h1 class="header">Produtos</h1>
<hr>
<div class="search-bar">
{!! Form::model(Request::all(), ['route' => 'produto.index', 'method' => 'GET', 'class' => 'form-inline', 'id' => 'produto-search', 'role' => 'search'])!!}
    <div class="form-group">
        {!! Form::text('codproduto', null, ['class' => 'form-control search-cod', 'placeholder' => '#']) !!}
    </div>

    <div class="form-group">
        {!! Form::text('barras', null, ['class' => 'form-control', 'placeholder' => 'Barras']) !!}
    </div>

    <div class="form-group">
        {!! Form::text('produto', null, ['class' => 'form-control', 'placeholder' => 'Descrição']) !!}
    </div>
    <button type="submit" class="btn btn-default">Buscar</button>
{!! Form::close() !!}
</div>

<br>
<div id="registros">
  <div class="list-group" id="items">
    @foreach($model as $row)
      <div class="list-group-item">
        <div class="row item">
            <div class="col-md-1">
                <a href="{{ url("produto/$row->codproduto") }}">
                    <strong>{{ formataCodigo($row->codproduto) }}</strong>
                </a>
                @if($row->codncm)
                <div class="text-muted">
                    <a href="{{ url("ncm/$row->codncm") }}">
                        {{ formataNcm($row->Ncm->ncm) }}
                    </a>
                </div>    
                @endif
                @if($row->codtributacao)
                <div class="text-muted">
                    {{ $row->Tributacao->tributacao }}
                </div>
                @endif
            </div>                            
            <div class="col-md-4">
                <a href="{{ url("produto/$row->codproduto") }}">
                    <strong>{{ $row->produto }}</strong>
                </a>
                @if(!empty($row->inativo))
                <div>
                    <span class="label label-danger produtos-grid-inativo">Inativado em {{ formataData($row->inativo, 'L')}} </span>
                </div>
                @endif
                @if(!empty($row->codsubgrupoproduto))
                <div>
                    <strong>{{ $row->SubGrupoProduto->GrupoProduto->grupoproduto }} › {{ $row->SubGrupoProduto->subgrupoproduto }}</strong>
                </div>    
                @endif
                <a href="{{ url("marca/$row->codmarca") }}">
                    {{ $row->Marca->marca }}
                </a>
                <span class="text-muted">{{ $row->referencia }}</span>
            </div>
            <div class="col-md-7">
                combinações
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
    ul.pagination {
        margin: 0;
    }
</style>
<script type="text/javascript">
  $(document).ready(function() {
    $('ul.pagination').removeClass('hide');
  });
</script>
@endsection
@stop