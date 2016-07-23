@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
  <div class="container-fluid"> 
    <ul class="nav navbar-nav">
    </ul>
  </div>
</nav>
<h1 class="header">Saldos de Estoque</h1>
<hr>
<br>

<div id='div-estoque'>
    <div class="panel-group">

        <div class="panel panel-default panel-condensed">

            <!-- Titulo -->
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-5">
                        <b>Item</b>
                    </div>
                    <div class="col-md-1 text-right">
                        Min <span class='glyphicon glyphicon-arrow-down'></span> 
                        Max <span class='glyphicon glyphicon-arrow-up'></span> 
                    </div>
                    <div class="col-md-3 text-center">
                        <b>Físico</b>
                    </div>
                    <div class="col-md-3 text-center">
                        <b>Fiscal</b>
                    </div>
                </div>
            </div>

        </div>

        @foreach($itens as $coditem => $item)
            <div class="panel panel-default panel-condensed">

                <!-- Total Local -->
                <div class="{{ ($coditem == 'total')?'panel-footer':'panel-body' }}">
                        <div class="row">
                            <div class='col-md-1 text-muted'>
                                <small>
                                    @if (!empty($item['coditem']))
                                        {{ formataCodigo($item['coditem']) }}
                                        @if (!empty($link))
                                            <a href='{{ $link }}{{ $coditem }}' class="pull-right">
                                                <span class='glyphicon glyphicon-zoom-in'></span>
                                            </a>
                                        @endif
                                    @endif
                                </small>
                            </div>
                            <a data-toggle="collapse" href="#collapseItem{{ $coditem }}">
                                <div class='col-md-3'>
                                    <b>
                                        {{ ($coditem == 'total')?'Total':$item['item'] }}
                                    </b>
                                </div>
                                <div class='col-md-2 text-right'>
                                    {!! formataEstoqueMinimoMaximo($item['estoquelocal']['total']['estoqueminimo'], $item['estoquelocal']['total']['estoquemaximo'], $item['estoquelocal']['total']['fisico']['saldoquantidade']) !!}
                                </div>
                                <div class='col-md-2 text-right'>
                                        {{ formataNumero($item['estoquelocal']['total']['fisico']['saldoquantidade'], 0) }}
                                </div>
                                <div class='col-md-1 text-right text-muted'>
                                    <small>
                                        {{ formataNumero($item['estoquelocal']['total']['fisico']['saldovalor'], 2) }}
                                    </small>
                                </div>
                                <div class='col-md-2 text-right'>
                                    {{ formataNumero($item['estoquelocal']['total']['fiscal']['saldoquantidade'], 0) }}
                                </div>
                                <div class='col-md-1 text-right text-muted'>
                                    <small>
                                        {{ formataNumero($item['estoquelocal']['total']['fiscal']['saldovalor'], 2) }}
                                    </small>
                                </div>
                            </a>
                        </div>
                </div>

                <!-- Variacoes do Produto -->
                <div id="collapseItem{{ $coditem }}" class="panel-collapse collapse">
                    <ul class="list-group list-group-condensed list-group-striped list-group-hover list-group-condensed">
                        @foreach ($item['estoquelocal'] as $codestoquelocal => $local)
                            <?php
                            if ($codestoquelocal == 'total')
                                continue;
                            ?>
                            <li class="list-group-item">
                                
                                <div class="row">
                                    <div class='col-md-1 text-muted'>
                                        
                                    </div>
                                    <div class='col-md-3 text-muted text-right'>
                                            {{ $local['estoquelocal'] }}
                                    </div>
                                    <div class='col-md-2 text-right'>
                                        {!! formataEstoqueMinimoMaximo($local['estoqueminimo'], $local['estoquemaximo'], $local['fisico']['saldoquantidade']) !!}
                                    </div>
                                    <div class='col-md-2 text-right'>
                                        {{ formataNumero($local['fisico']['saldoquantidade'], 0) }}
                                    </div>
                                    <div class='col-md-1 text-right text-muted'>
                                        <small>
                                            {{ formataNumero($local['fisico']['saldovalor'], 2) }}
                                        </small>
                                    </div>
                                    <div class='col-md-2 text-right'>
                                        {{ formataNumero($local['fiscal']['saldoquantidade'], 0) }}
                                    </div>
                                    <div class='col-md-1 text-right text-muted'>
                                        <small>
                                            {{ formataNumero($local['fiscal']['saldovalor'], 2) }}
                                        </small>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

            </div>
        @endforeach

    </div>
</div>

<?php //echo $model->appends(Request::all())->render();?>

@section('inscript')
<script type="text/javascript">
  $(document).ready(function() {
      
  });
</script>
@endsection
@stop
