
{!! Form::hidden('codestoquesaldo') !!}
{!! Form::hidden('data') !!}

<h3>
    {!! 
        titulo(
            $model->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->codproduto ,
            [
                url("produto/{$model->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->codproduto}") => $model->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->Produto->produto,
                $model->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->variacao
            ],
            $model->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->Produto->inativo,
            6
        ) 
    !!}
    
    <small class="pull-right">
        <a href='{{ url("secao-produto/{$model->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->Produto->SubGrupoProduto->GrupoProduto->FamiliaProduto->codsecaoproduto}") }}' class=''>
            {!! $model->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->Produto->SubGrupoProduto->GrupoProduto->FamiliaProduto->SecaoProduto->secaoproduto!!}
        </a>
        »
        <a href='{{ url("familia-produto/{$model->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->Produto->SubGrupoProduto->GrupoProduto->codfamiliaproduto}") }}' class=''>
            {!! $model->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->Produto->SubGrupoProduto->GrupoProduto->FamiliaProduto->familiaproduto!!}
        </a>
        »
        <a href='{{ url("grupo-produto/{$model->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->Produto->SubGrupoProduto->codgrupoproduto}") }}' class=''>
            {!! $model->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->Produto->SubGrupoProduto->GrupoProduto->grupoproduto!!}
        </a>
        »
        <a href='{{ url("sub-grupo-produto/{$model->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->Produto->codsubgrupoproduto}") }}' class=''>
            {!! $model->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->Produto->SubGrupoProduto->subgrupoproduto!!}
        </a>
        »
        <a href='{{ url("marca/{$model->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->Produto->codmarca}") }}' class=''>
            {!! $model->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->Produto->Marca->marca !!}
        </a>
        @if (!empty($model->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->Produto->referencia))
            »
            {!! $model->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->Produto->referencia !!}
        @endif
        »
        R$ {!! formataNumero($model->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->Produto->preco) !!}
    </small>    
</h3>
<hr>



<?php

function decideIconeUltimaConferencia($data)
{
    if ($data == null)
        return 'glyphicon-remove-sign text-muted';
    
    $dias = $data->diffInDays();
    
    if ($dias > 30)
        return 'glyphicon-question-sign text-danger';
  
    if ($dias > 15)
        return 'glyphicon-question-sign text-warning';
    
    return 'glyphicon-ok-sign text-success';
}

function divSaldo($arr, $model, $codestoquelocal, $codprodutovariacao, $fiscal, $data) {
    ?>
    @if ((!empty($arr['codestoquesaldo'])) && ($model->codestoquesaldo == $arr['codestoquesaldo']))
        <div class="col-md-3">
            <div class='row'>
                <div class='col-md-6'>
                    <div class='input-group'>
                        {!! Form::text('quantidadeinformada', null, ['class'=> 'form-control text-right', 'style' => 'width: 100%', 'id'=>'quantidadeinformada', 'required'=>'required', 'placeholder'=>'Quantidade']) !!}
                        <div class='input-group-addon'>
                            <span class='glyphicon {{ decideIconeUltimaConferencia($arr['ultimaconferencia']) }}'></span>
                        </div>
                    </div>
                </div>
                <div class='col-md-6'>
                    {!! Form::text('customedioinformado', null, ['class'=> 'form-control text-right', 'style' => 'width: 100%', 'id'=>'customedioinformado', 'required'=>'required', 'placeholder'=>'Custo']) !!}
                </div>
            </div>
            <div class='pull-right'>
                <div class="btn-group" role="group">
                    <button type="submit" class="btn btn-primary">
                        <span class='glyphicon glyphicon-ok'></span>
                    </button>
                    <button type="reset" class="btn btn-danger">
                        <span class='glyphicon glyphicon-erase'></span>
                    </button>
                </div>
            </div>
        </div>

    @else
        <div class="col-md-1 text-right">
            @if (!empty($arr['codestoquesaldo']))
                <a href="{{ url("estoque-saldo/{$arr['codestoquesaldo']}") }}">
            @endif
            {{ formataNumero($arr['saldoquantidade'], 3) }}
            @if (!empty($arr['codestoquesaldo']))
                </a>
            @endif
            @if (!empty($codprodutovariacao) && (is_numeric($codestoquelocal)))
                <a href='{{ url("estoque-saldo-conferencia/create?codestoquelocal=$codestoquelocal&codprodutovariacao=$codprodutovariacao&fiscal=$fiscal&data=$data") }}'>
            @endif
            <span class='glyphicon {{ decideIconeUltimaConferencia($arr['ultimaconferencia']) }}'></span>
            @if (!empty($codprodutovariacao))
                </a>
            @endif
        </div>
        <div class="col-md-1 text-right">
            @if (!empty($arr['codestoquesaldo']))
                <a href="{{ url("estoque-saldo/{$arr['codestoquesaldo']}") }}">
            @endif
            {{ formataNumero($arr['customedio'], 6) }}
            @if (!empty($arr['codestoquesaldo']))
                </a>
            @endif
        </div>
        <div class="col-md-1 text-right">
            @if (!empty($arr['codestoquesaldo']))
                <a href="{{ url("estoque-saldo/{$arr['codestoquesaldo']}") }}">
            @endif
            {{ formataNumero($arr['saldovalor'], 2) }}
            @if (!empty($arr['codestoquesaldo']))
                </a>
            @endif
        </div>
    @endif
    <?php
}

function divDescricao($arr, $model) {
    ?>
    <div class="col-md-1">
        @if (is_array($arr['variacao'] ))
            <b>
            @if (!empty($arr['estoquelocal'] ))
                {{ $arr['estoquelocal'] }}
            @else
                Total
            @endif
            </b>
        @elseif (!empty($arr['variacao'] ))
            {{ $arr['variacao'] }}
        @else
            <i class='text-muted'>{ Sem Variação }</i>
        @endif
    </div>
    

    <div class="col-md-5 text-muted">
        @if (isset($arr['fisico']['codestoquesaldo']) && in_array($model->codestoquesaldo, [$arr['fisico']['codestoquesaldo'], $arr['fiscal']['codestoquesaldo']]))
            <div class='row'>
                <div class='col-md-2'>
                    {!! Form::number('corredor', $model->EstoqueSaldo->EstoqueLocalProdutoVariacao->corredor, ['class'=> 'form-control text-center', 'style'=>'width: 100%', 'id'=>'corredor', 'step' => 1, 'min' => 0, 'placeholder'=>'Corredor']) !!}
                </div>
                <div class='col-md-2'>
                        {!! Form::number('prateleira', $model->EstoqueSaldo->EstoqueLocalProdutoVariacao->prateleira, ['class'=> 'form-control text-center', 'style'=>'width: 100%', 'id'=>'prateleira', 'step' => 1, 'min' => 0, 'placeholder'=>'Prateleira']) !!}
                </div>
                <div class='col-md-2'>
                        {!! Form::number('coluna', $model->EstoqueSaldo->EstoqueLocalProdutoVariacao->coluna, ['class'=> 'form-control text-center', 'style'=>'width: 100%', 'id'=>'coluna', 'step' => 1, 'min' => 0, 'placeholder'=>'Coluna']) !!}
                </div>
                <div class='col-md-2'>
                        {!! Form::number('bloco', $model->EstoqueSaldo->EstoqueLocalProdutoVariacao->bloco, ['class'=> 'form-control text-center', 'style'=>'width: 100%', 'id'=>'bloco', 'step' => 1, 'min' => 0, 'placeholder'=>'Bloco']) !!}
                </div>
                <div class='col-md-2'>
                    {!! Form::number('estoqueminimo', $model->EstoqueSaldo->EstoqueLocalProdutoVariacao->estoqueminimo, ['class'=> 'form-control text-right', 'style' => 'width: 100%', 'step' => 1, 'min' => 0, 'id'=>'estoqueminimo', 'placeholder'=>'Mín']) !!}
                </div>
                <div class='col-md-2'>
                    {!! Form::number('estoquemaximo', $model->EstoqueSaldo->EstoqueLocalProdutoVariacao->estoquemaximo, ['class'=> 'form-control text-right', 'style' => 'width: 100%', 'step' => 1, 'min' => 0, 'id'=>'estoquemaximo', 'placeholder'=>'Máx']) !!}
                </div>
            </div>
        @else
            @if (isset($arr['corredor']))
                {{ formataLocalEstoque($arr['corredor'], $arr['prateleira'], $arr['coluna'], $arr['bloco']) }}
            @endif
            <div class='pull-right'>
                @if (!empty($arr['estoqueminimo']))
                    @if ($arr['estoqueminimo'] > $arr['fisico']['saldoquantidade'])
                        <b class='text-danger'>
                    @endif
                    {{ formataNumero($arr['estoqueminimo'], 0) }} <span class='glyphicon glyphicon-arrow-down'></span>
                    @if ($arr['estoqueminimo'] > $arr['fisico']['saldoquantidade'])
                        </b>
                    @endif
                @endif
                @if (!empty($arr['estoquemaximo']))
                    @if ($arr['estoquemaximo'] < $arr['fisico']['saldoquantidade'])
                        <b class='text-danger'>
                    @endif
                    {{ formataNumero($arr['estoquemaximo'], 0) }} <span class='glyphicon glyphicon-arrow-up'></span>
                    @if ($arr['estoquemaximo'] < $arr['fisico']['saldoquantidade'])
                        </b>
                    @endif
                @endif
            </div>            
        @endif
    </div>
    <?php
}

?>

<div id='div-estoque'>
    <div class="panel-group">

        <div class="panel panel-default panel-condensed">

            <!-- Titulo -->
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-6">
                    </div>
                    <div class="col-md-3 text-center">
                        <b>Físico</b>
                    </div>
                    <div class="col-md-3 text-center">
                        <b>Fiscal</b>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <b>Local</b>
                    </div>
                    <div class="col-md-4">
                        <b>Corredor</b>
                        <b class='pull-right'>
                            Min <span class='glyphicon glyphicon-arrow-down'></span> 
                            Max <span class='glyphicon glyphicon-arrow-up'></span> 
                        </b>
                    </div>
                    <div class="col-md-1 text-right">
                        <b>Quantidade</b>
                    </div>
                    <div class="col-md-1 text-right">
                        <b>Custo</b>
                    </div>
                    <div class="col-md-1 text-right">
                        <b>Valor</b>
                    </div>
                    <div class="col-md-1 text-right">
                        <b>Quantidade</b>
                    </div>
                    <div class="col-md-1 text-right">
                        <b>Custo</b>
                    </div>
                    <div class="col-md-1 text-right">
                        <b>Valor</b>
                    </div>
                </div>
            </div>

        </div>

        @foreach($estoque['local'] as $codestoquelocal => $arrLocal)
            <?php
                if ($codestoquelocal == $model->EstoqueSaldo->EstoqueLocalProdutoVariacao->codestoquelocal)
                    $class = 'in';
                else $class = '';
            ?>
            <div class="panel panel-default panel-condensed">

                <!-- Total Local -->
                <div class="{{ ($codestoquelocal == 'total')?'panel-footer':'panel-body' }}">
                    <a data-toggle="collapse" href="#collapseEstoqueLocal{{ $codestoquelocal }}">
                        <div class="row">
                            {{ divDescricao($arrLocal, $model) }}
                            {{ divSaldo($arrLocal['fisico'], $model, $codestoquelocal, null, false, $data) }}
                            {{ divSaldo($arrLocal['fiscal'], $model, $codestoquelocal, null, true, $data) }}
                        </div>
                    </a>
                </div>

                <!-- Variacoes do Produto -->
                <div id="collapseEstoqueLocal{{ $codestoquelocal }}" class="panel-collapse collapse {{ $class }}">
                    <ul class="list-group list-group-condensed list-group-striped list-group-hover list-group-condensed">

                        @foreach ($arrLocal['variacao'] as $codprodutovariacao => $arrVar)
                            <li class="list-group-item">
                                <div class="row">
                                    {{ divDescricao($arrVar, $model) }}
                                    {{ divSaldo($arrVar['fisico'], $model, $codestoquelocal, $codprodutovariacao, false, $data) }}
                                    {{ divSaldo($arrVar['fiscal'], $model, $codestoquelocal, $codprodutovariacao, true, $data) }}
                                </div>              
                            </li>
                        @endforeach
                    </ul>
                </div>

            </div>
        @endforeach

    </div>
</div>
