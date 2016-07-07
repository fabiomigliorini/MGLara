<?php

use MGLara\Models\EstoqueLocal;
use MGLara\Models\EstoqueSaldoConferencia;

$locais = [''=>''] + EstoqueLocal::lists('estoquelocal', 'codestoquelocal')->all();
?>

{!! Form::hidden('codestoquesaldo') !!}
{!! Form::hidden('data') !!}

<div class='row'>
    <div class='col-md-6'>
        <h4>
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
        </h4>
        <hr>
        <h5>
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
            »
            R$ {!! formataNumero($model->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->Produto->preco) !!}
        </h5>
        <hr>
        <div class="form-group">
            <label for="data" class="control-label col-md-3">
                {!! Form::label('Saldo:') !!}
            </label>
            <div class="col-md-9 form-inline">
                <div class="input-group" style='width:160px'>
                    {!! Form::text('quantidadeinformada', null, ['class'=> 'form-control text-right', 'id'=>'quantidadeinformada', 'required'=>'required', 'placeholder'=>'Quantidade']) !!}
                    <span class="input-group-addon" id="basic-addon2">
                        {!! $model->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->Produto->UnidadeMedida->sigla !!}
                    </span>
                </div>
                
                {!! Form::text('customedioinformado', null, ['class'=> 'form-control text-right', 'id'=>'customedioinformado', 'required'=>'required', 'style'=>'width:160px', 'placeholder'=>'Custo']) !!}
            </div>
        </div>
        <div class="form-group">
            <label for="data" class="control-label col-md-3">
                {!! Form::label('Min/Max:') !!}
            </label>
            <div class="col-md-9 form-inline">
                {!! Form::text('estoqueminimo', $model->EstoqueSaldo->EstoqueLocalProdutoVariacao->estoqueminimo, ['class'=> 'form-control text-right', 'id'=>'estoqueminimo', 'style'=>'width:160px', 'placeholder'=>'Mínimo']) !!}
                {!! Form::text('estoquemaximo', $model->EstoqueSaldo->EstoqueLocalProdutoVariacao->estoquemaximo, ['class'=> 'form-control text-right', 'id'=>'estoquemaximo', 'style'=>'width:160px', 'placeholder'=>'Máximo']) !!}
            </div>
        </div>
        <div class="form-group">
            <label for="data" class="control-label col-md-3">
                {!! Form::label('Localização:') !!}
            </label>
            <div class="col-md-9 form-inline">
                {!! Form::text('corredor', $model->EstoqueSaldo->EstoqueLocalProdutoVariacao->corredor, ['class'=> 'form-control text-center', 'id'=>'corredor', 'style'=>'width:78px', 'placeholder'=>'Corredor']) !!}
                {!! Form::text('prateleira', $model->EstoqueSaldo->EstoqueLocalProdutoVariacao->prateleira, ['class'=> 'form-control text-center', 'id'=>'prateleira', 'style'=>'width:78px', 'placeholder'=>'Prateleira']) !!}
                {!! Form::text('coluna', $model->EstoqueSaldo->EstoqueLocalProdutoVariacao->coluna, ['class'=> 'form-control text-center', 'id'=>'coluna', 'style'=>'width:78px', 'placeholder'=>'Coluna']) !!}
                {!! Form::text('bloco', $model->EstoqueSaldo->EstoqueLocalProdutoVariacao->bloco, ['class'=> 'form-control text-center', 'id'=>'bloco', 'style'=>'width:78px', 'placeholder'=>'Bloco']) !!}
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-offset-3 col-md-9">
                {!! Form::submit('Salvar', array('class' => 'btn btn-primary', 'id'=>'btnSubmit')) !!}
                <a href='{{ url("estoque-saldo-conferencia/create?data=$data&fiscal=$fiscal&codestoquelocal=$codestoquelocal") }}' class='btn btn-danger'>Cancelar</a>
            </div>
        </div>
        
    </div>
    <div class='col-md-6'>
        <small>
            @include('estoque-saldo.resumo-produto', ['codproduto' => $model->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->codproduto, 'somentequantidade' => false, 'codestoquelocal_destaque' => $model->EstoqueSaldo->EstoqueLocalProdutoVariacao->codestoquelocal, 'codestoquesaldo_destaque' => $model->EstoqueSaldo->codestoquesaldo])
        </small>
    </div>
</div>
<hr>

