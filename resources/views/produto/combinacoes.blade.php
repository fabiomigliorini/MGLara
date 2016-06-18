<div class="panel panel-info combinacoes">
    <ul class="list-group group-list-striped group-list-hover">
        <li class="list-group-item">
            <strong>Códigos de barra</strong>
            <span class="pull-right"><a href="{{ url("produto-barra/create?codproduto={$model->codproduto}") }}"><i class="glyphicon glyphicon-plus"></i> Novo</a></span>
        </li>
        @foreach($model->ProdutoBarraS as $pb)
        <li class="list-group-item">
            <div class="row item">
                <div class="col-md-2">
                    {{ $pb->ProdutoEmbalagem->UnidadeMedida->sigla or $pb->Produto->UnidadeMedida->sigla}}
                </div>
                <div class="col-md-4">
                    {{ $pb->barras }}
                </div>
                <div class="col-md-3">
                    {{ $pb->variacao }}
                </div>
                <div class="col-md-3">
                    <span class="pull-right">
                        <a href="{{ url("produto-barra/$pb->codprodutobarra/edit") }}"><i class="glyphicon glyphicon-pencil"></i></a>
                        &nbsp;&nbsp;
                        <a class="delete-barra" href="" data-pe="{{$pb->codprodutobarra}}"><i class="glyphicon glyphicon-trash"></i></a>
                    </span>                                          
                </div>
            </div>
        </li>
        @endforeach

        <li class="list-group-item">
            <strong>Embalagens</strong>
            <span class="pull-right"><a href="{{ url("produto-embalagem/create?codproduto={$model->codproduto}") }}"><i class="glyphicon glyphicon-plus"></i> Novo</a></span>
        </li>
        @foreach($model->ProdutoEmbalagemS as $pe)
        <li class="list-group-item" id="pe{{ $pe->codprodutoembalagem }}">
            <div class="row item">            
                <div class="col-md-4">
                    {{ $pe->descricao }}
                </div>                            
                <div class="col-md-4">
                @if (empty($pe->preco))
                    <div class="text-right text-muted">
                        {{ formataNumero($pe->preco_calculado) }}
                    </div>
                @else
                    <div class="text-right text-success">
                        {{ formataNumero($pe->preco_calculado) }}
                    </div>	
                @endif
                </div>
                <div class="col-md-4">
                    <div class="row-fluid">
                        <span class="pull-right">
                            <a href="{{ url("produto-embalagem/$pe->codprodutoembalagem/edit") }}"><i class="glyphicon glyphicon-pencil"></i></a>
                            &nbsp;&nbsp;
                            <a href="" class="delete-barra" data-pe="pe{{ $pe->codprodutoembalagem }}"><i class="glyphicon glyphicon-trash"></i></a>
                        </span>                                                                                
                    </div>
                </div>      
            </div>    
        </li>            
        @endforeach        
    </ul>                
</div>            
