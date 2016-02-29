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
<h1 class="header">{{ $model->produto }}</h1>
<hr>
<div class="row">
    <div class="col-md-6">
        <table class="detail-view table table-striped table-condensed"> 
            <tbody>  
                <tr> 
                    <th class="col-md-3">#</th> 
                    <td class="col-md-9">{{ formataCodigo($model->codproduto) }}</td> 
                </tr>
                <tr> 
                    <th>Marca</th> 
                    <td>{{ $model->Marca->marca or '' }}</td> 
                </tr>
                <tr> 
                    <th>Referência</th> 
                    <td>{{ $model->referencia }}</td> 
                </tr>
                <tr> 
                    <th>Grupo</th> 
                    <td>{{ $model->SubGrupoProduto->GrupoProduto->grupoproduto or '' }}</td> 
                </tr>
                <tr> 
                    <th>Sub-Grupo</th> 
                    <td>{{ $model->SubGrupoProduto->subgrupoproduto or '' }}</td> 
                </tr>
                <tr> 
                    <th>Importado</th> 
                    <td>{{ $model->importado ? 'Sim':'Não' }}</td> 
                </tr>
            </tbody> 
        </table>
    </div>
    <div class="col-md-6">
        <table class="detail-view table table-striped table-condensed"> 
            <tbody>  
                <tr> 
                    <th class="col-md-3">Preço</th> 
                    <td class="col-md-9">{{ $model->preco }}</td> 
                </tr>
                <tr> 
                    <th>Unidade medida</th> 
                    <td>{{ $model->UnidadeMedida->sigla or '' }}</td> 
                </tr>
                <tr> 
                    <th>Tributação</th> 
                    <td>{{ $model->Tributacao->tributacao or ''}}</td> 
                </tr>
                <tr> 
                    <th>Tipo</th> 
                    <td>{{ $model->TipoProduto->tipoproduto or '' }}</td> 
                </tr>
                <tr> 
                    <th>Disponível no site</th> 
                    <td>{{ $model->site ? 'Sim' : 'Não'}}</td> 
                </tr>
                <tr> 
                    <th>Descrição site</th> 
                    <td>{{ $model->descricaosite }}</td> 
                </tr>
            </tbody> 
        </table>
    </div>    
</div>
<hr>
@include('includes.autor')
<hr>
<div class="panel panel-default">
    <div class="panel-heading">Combinações</div>
    <ul class="list-group">
        <li class="list-group-item">
            <div class="row item">
                <div class="col-md-3">
                    {{ $model->UnidadeMedida->unidademedida }}
                </div>                            
                <div class="col-md-1">
                    R$ {{ $model->preco }}
                </div>
                <div class="col-md-8">
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
        <li class="list-group-item">
            <div class="row item">            
                <div class="col-md-3">
                    {{ $pe->UnidadeMedida->unidademedida }}
                    {{ $pe->UnidadeMedida->descricao }}
                </div>                            
                <div class="col-md-1">
                    R$ {{ $pe->preco ? $pe->preco : $pe->preco_calculado }}
                </div>
                <div class="col-md-8">
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