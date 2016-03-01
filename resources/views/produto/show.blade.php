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
<hr>
<div class="row">
    <div class="col-md-7">
        <div class="panel panel-default">
            <div class="panel-body">
                <h1 class="text-danger produtos-detalhes-produto">{{ $model->produto }}</h1>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <p class="mz"><strong>Código</strong></p>
                        {{ formataCodigo($model->codproduto) }}
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
        <div class="panel panel-info">
            <div class="panel-body">
                <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                  <!-- Indicators -->
                  <ol class="carousel-indicators">
                    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                    <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                  </ol>

                  <!-- Wrapper for slides -->
                  <div class="carousel-inner" role="listbox">
                    <div class="item active">
                        <img src="/MGsis/images/produto/000904/509828266b56db1b082f0200.jpeg" alt="" style="width:100%; max-height: 450px">
                    </div>
                    <div class="item">
                        <img src="/MGsis/images/produto/000904/image-1.jpg" alt="" style="width:100%; max-height: 450px">
                    </div>
                  </div>

                  <!-- Controls -->
                  <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                  </a>
                  <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                  </a>
                </div>
            </div>
        </div>        

    </div>
    <div class="col-md-5">
        <div class="panel panel-success">
            <div class="panel-body bg-success">
                <h2 class="produtos-detalhe-preco text-right pull-right text-success">{{ $model->preco }}</h2>
                <span class="text-muted text-left pull-left produtos-detalhe-cifrao">R$</span>
            </div>
        </div> 
        <div class="panel panel-info combinacoes">
            <ul class="list-group bg-info">
                <li class="list-group-item">
                    <div class="row item">
                        <div class="col-md-3">
                            {{ $model->UnidadeMedida->unidademedida }}
                        </div>                            
                        <div class="col-md-3">
                            R$ <strong>{{ $model->preco }}</strong>
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
                            R$ {{ $pe->preco ? $pe->preco : $pe->preco_calculado }}
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
</div>
<hr>
@include('includes.autor')
<hr>
<br>
<div id="produto-detalhes">
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#tab-produto-fiscal" aria-controls="fiscal" role="tab" data-toggle="tab">Fiscal</a></li>
        <li role="presentation"><a href="#tab-produto-imagens" aria-controls="imagens" role="tab" data-toggle="tab">Imagens</a></li>
        <li role="presentation"><a href="#tab-produto-notasfiscais" aria-controls="notasfiscais" role="tab" data-toggle="tab">Notas Fiscais</a></li>
        <li role="presentation"><a href="#tab-produto-negocios" aria-controls="negocios" role="tab" data-toggle="tab">Negócios</a></li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade in active" id="tab-produto-fiscal">
            @include('produto.fiscal')
        </div>
        <div role="tabpanel" class="tab-pane fade" id="tab-produto-imagens">
            @include('produto.imagens')
        </div>
        <div role="tabpanel" class="tab-pane fade" id="tab-produto-notasfiscais">
            @include('produto.notasfiscais')
        </div>
        <div role="tabpanel" class="tab-pane fade" id="tab-produto-negocios">
            @include('produto.negocios')
        </div>
    </div>
</div>
@stop