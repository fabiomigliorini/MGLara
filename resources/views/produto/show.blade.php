@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="<?php echo url('produto');?>"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>             
            <li><a href="<?php echo url('produto/create');?>"><span class="glyphicon glyphicon-plus"></span> Novo</a></li>             
            <li><a href="<?php echo url("produto/$model->codproduto/edit");?>"><span class="glyphicon glyphicon-pencil"></span> Alterar</a></li> 
            <li><a href="<?php echo url("produto/$model->codproduto/juntar-barras");?>"><span class="glyphicon glyphicon-resize-small"></span> Juntar códigosde barra</a></li> 
            <li><a href="<?php echo url("produto/$model->codproduto/transferir-barras");?>"><span class="glyphicon glyphicon-transfer"></span> Transferir códigos de barra</a></li> 
            <li>
                {!! Form::open(['method' => 'DELETE', 'route' => ['produto.destroy', $model->codproduto]]) !!}
                <span class="glyphicon glyphicon-trash"></span>
                {!! Form::submit('Excluir') !!}
                {!! Form::close() !!}
            </li>
        </ul>
    </div>
</nav>


<br>
<div class="row">
    <div class="col-md-5">
        {!! Form::model(Request::all(), [
           
          'method' => 'POST', 
          'class' => 'form-inline',
          'id' => 'produto-busca-barras',
          'role' => 'search'
        ])!!}        
        <div class="form-group" style="width: 100%">
            <div class="input-group" style="width: 100%">
                <input type="text" name="" class="form-control" id="produto-busca-barras">
                <div class="input-group-addon"><i class="glyphicon glyphicon-search"></i></div>
            </div>
        </div>            
        {!! Form::close() !!}
    </div>
    <div class="col-md-7">
        select2
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-7">
        <div class="panel panel-warning">
            <div class="panel-body bg-warning">
                <h1 class="text-danger produtos-detalhes-produto">
                    {{ $model->produto }}
                    <span class="pull-right text-muted">{{ $model->UnidadeMedida->unidademedida }}</span>
                </h1>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <p class="mz"><strong>Código</strong></p>
                        {{ formataCodigo($model->codproduto, 6) }}
                    </div>
                    <div class="col-md-4">
                        <p class="mz"><strong>Marca</strong></p>
                        {{ $model->Marca->marca or '' }}
                    </div>
                    <div class="col-md-4">
                        <p class="mz"><strong>Referência</strong></p>
                        {{ $model->referencia }}
                    </div>
                </div>
            </div>
        </div>        
        <div class="panel panel-info produtos-detalhe-carousel">
            <div class="panel-body">
                @include('produto.carousel')
            </div>
        </div>        
    </div>
    <div class="col-md-5">
        <div class="panel panel-success">
            <div class="panel-body bg-success">
                <h2 class="produtos-detalhe-preco text-right pull-right text-success">{{ formataNumero($model->preco) }}</h2>
                <span class="text-muted text-left pull-left produtos-detalhe-cifrao">R$</span>
            </div>
        </div> 
        <div id="produto-detalhes">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#tab-produto-combinacoes" aria-controls="home" role="tab" data-toggle="tab">Combinações</a></li>
                <li role="presentation"><a href="#tab-produto-fiscal" aria-controls="profile" role="tab" data-toggle="tab">Fiscal</a></li>
                <li role="presentation"><a href="#tab-produto-notasfiscais" aria-controls="messages" role="tab" data-toggle="tab">Notas fiscais</a></li>
                <li role="presentation"><a href="#tab-produto-negocios" aria-controls="messages" role="tab" data-toggle="tab">Negócios</a></li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="tab-produto-combinacoes">
                    <div class="panel panel-info combinacoes">
                        <ul class="list-group bg-info">
                            <li class="list-group-item">
                                <div class="row item">
                                    <div class="col-md-3">
                                        {{ $model->UnidadeMedida->unidademedida }}
                                    </div>                            
                                    <div class="col-md-3">
                                        R$ <strong>{{ formataNumero($model->preco) }}</strong>
                                    </div>
                                    <div class="col-md-6">
                                    @foreach ($model->ProdutoBarras as $pb)
                                        <?php if(!empty($pb->codprodutoembalagem))
                                            continue;
                                        ?>
                                        <div class="row-fluid">
                                            {{$pb->barras}}
                                            <div class="pull-right">{{$pb->variacao}}</div>
                                        </div>
                                    @endforeach
                                    </div>      
                                </div>
                            </li>
                            @foreach($model->ProdutoEmbalagemS as $pe)
                            <li class="list-group-item bg-info">
                                <div class="row item">            
                                    <div class="col-md-3">
                                        {{ $pe->UnidadeMedida->unidademedida }}
                                        {{ $pe->UnidadeMedida->descricao }}
                                    </div>                            
                                    <div class="col-md-3">
                                        R$ {{ formataNumero($pe->preco ? $pe->preco : $pe->preco_calculado) }}
                                    </div>
                                    <div class="col-md-6">
                                    @foreach ($pe->ProdutoBarras as $pb)
                                        <div class="row-fluid">
                                            {{$pb->barras}}
                                            <div class="pull-right">{{$pb->variacao}}</div>
                                        </div>
                                    @endforeach
                                    </div>      
                                </div>    
                            </li>            
                            @endforeach        
                        </ul>                
                    </div>            
                </div>
                <div role="tabpanel" class="tab-pane fade" id="tab-produto-fiscal">
                    @include('produto.fiscal')
                </div>
                <div role="tabpanel" class="tab-pane fade" id="tab-produto-notasfiscais">
                    @include('produto.notasfiscais')
                </div>
                <div role="tabpanel" class="tab-pane fade" id="tab-produto-negocios">
                    @include('produto.negocios')
                </div>
            </div>
        </div>        
 
	<?php

	$arr_saldos = [];
    $arr_totais = [true => 0, false => 0];
	foreach ($model->EstoqueSaldoS as $es)
	{
        $arr_saldos[$es->EstoqueLocal->estoquelocal][$es->fiscal] = array(
            "saldoquantidade" => $es->saldoquantidade,
            "codestoquesaldo" => $es->codestoquesaldo,
        );
        $arr_totais[$es->fiscal] += $es->saldoquantidade;
	}
    
    //dd($arr_saldos);

	?>
        
        <div class='panel panel-info'>
            <div class="panel-heading">
                    <div class="row item">
                        <div class="col-md-6">Estoque</div>
                        <div class="col-md-3 text-right">Físico</div>
                        <div class="col-md-3 text-right">Fiscal</div>
                    </div>
            </div>            
            <ul class="list-group bg-infoo">
            @foreach($arr_saldos as $estoquelocal => $saldo)
                <li class="list-group-item">
                    <div class="row item">            
                        <div class="col-md-6">
                            {{ $estoquelocal }}
                        </div>

                        <div class="col-md-3 text-right">
                            @if(isset($saldo[false]))
                            <a href='{{ url("estoque-saldo/{$saldo[false]['codestoquesaldo']}") }}'>
                                {{ formataNumero($saldo[false]['saldoquantidade'], 0) }}
                            </a>
                            @endif
                        </div>
                
                        <div class="col-md-3 text-right">
                            @if(isset($saldo[true]))
                            <a href='{{ url("estoque-saldo/{$saldo[true]['codestoquesaldo']}") }}'>
                                {{ formataNumero($saldo[true]['saldoquantidade'], 0) }}
                            </a>
                            @endif
                        </div>
                    </div>            
                </li>
            @endforeach    
                <li class="list-group-item">
                    <div class="row item">            
                        <div class="col-md-6">
                            Total
                        </div>

                        <div class="col-md-3 text-right">
                            {{ formataNumero($arr_totais[false], 0) }}
                        </div>
                
                        <div class="col-md-3 text-right">
                            {{ formataNumero($arr_totais[true], 0) }}
                        </div>
                    </div>            
                </li>
            </ul>
        </div>
        
    </div>    
</div>
<hr>
@include('includes.autor')
<hr>
<br>
@section('inscript')
<script type="text/javascript">
  $(document).ready(function() {
      
  });
</script>
@endsection
@stop
