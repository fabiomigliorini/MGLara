<?php

use MGLara\Models\EstoqueLocal;
$locais = [''=>''] + EstoqueLocal::lists('estoquelocal', 'codestoquelocal')->all();
?>

<h2>
{!! 
    titulo(
        $model->EstoqueSaldo->EstoqueLocalProduto->codproduto,
        [
            ['url' => "produto/{$model->EstoqueSaldo->EstoqueLocalProduto->codproduto}", 'descricao' => $model->EstoqueSaldo->EstoqueLocalProduto->Produto->produto],
            ['descricao' => $model->EstoqueSaldo->EstoqueLocalProduto->Produto->UnidadeMedida->sigla],
            ['descricao' => 'R$ ' . formataNumero($model->EstoqueSaldo->EstoqueLocalProduto->Produto->preco)]
        ],
        $model->EstoqueSaldo->EstoqueLocalProduto->Produto->inativo
    ) 
!!}

<small class='pull-right'>
    <a href='{{ url("secao-produto/{$model->EstoqueSaldo->EstoqueLocalProduto->Produto->SubGrupoProduto->GrupoProduto->FamiliaProduto->codsecaoproduto}") }}' class=''>
        {!! $model->EstoqueSaldo->EstoqueLocalProduto->Produto->SubGrupoProduto->GrupoProduto->FamiliaProduto->SecaoProduto->secaoproduto!!}
    </a>
    »
    <a href='{{ url("familia-produto/{$model->EstoqueSaldo->EstoqueLocalProduto->Produto->SubGrupoProduto->GrupoProduto->codfamiliaproduto}") }}' class=''>
        {!! $model->EstoqueSaldo->EstoqueLocalProduto->Produto->SubGrupoProduto->GrupoProduto->FamiliaProduto->familiaproduto!!}
    </a>
    »
    <a href='{{ url("grupo-produto/{$model->EstoqueSaldo->EstoqueLocalProduto->Produto->SubGrupoProduto->codgrupoproduto}") }}' class=''>
        {!! $model->EstoqueSaldo->EstoqueLocalProduto->Produto->SubGrupoProduto->GrupoProduto->grupoproduto!!}
    </a>
    »
    <a href='{{ url("sub-grupo-produto/{$model->EstoqueSaldo->EstoqueLocalProduto->Produto->codsubgrupoproduto}") }}' class=''>
        {!! $model->EstoqueSaldo->EstoqueLocalProduto->Produto->SubGrupoProduto->subgrupoproduto!!}
    </a>
    »
    <a href='{{ url("marca/{$model->EstoqueSaldo->EstoqueLocalProduto->Produto->codmarca}") }}' class=''>
        {!! $model->EstoqueSaldo->EstoqueLocalProduto->Produto->Marca->marca !!}
    </a>
</small>
</h2>
<hr>
{!! Form::hidden('codestoquesaldo') !!}
{!! Form::hidden('data') !!}

<div class='row'>
    <div class='col-md-4'>
        <div class="form-group">
            <label for="data" class="control-label col-md-3">
                {!! Form::label('Saldo:') !!}
            </label>
            <div class="col-md-9 form-inline">
                {!! Form::text('quantidadeinformada', null, ['class'=> 'form-control text-right', 'id'=>'quantidadeinformada', 'required'=>'required', 'style'=>'width:120px', 'placeholder'=>'Quantidade']) !!}
                {!! Form::text('customedioinformado', null, ['class'=> 'form-control text-right', 'id'=>'customedioinformado', 'required'=>'required', 'style'=>'width:120px', 'placeholder'=>'Custo']) !!}
            </div>
        </div>
        <div class="form-group">
            <label for="data" class="control-label col-md-3">
                {!! Form::label('Min/Max:') !!}
            </label>
            <div class="col-md-9 form-inline">
                {!! Form::text('estoqueminimo', $model->EstoqueSaldo->EstoqueLocalProduto->estoqueminimo, ['class'=> 'form-control text-right', 'id'=>'estoqueminimo', 'style'=>'width:120px', 'placeholder'=>'Mínimo']) !!}
                {!! Form::text('estoquemaximo', $model->EstoqueSaldo->EstoqueLocalProduto->estoquemaximo, ['class'=> 'form-control text-right', 'id'=>'estoquemaximo', 'style'=>'width:120px', 'placeholder'=>'Máximo']) !!}
            </div>
        </div>
        <div class="form-group">
            <label for="data" class="control-label col-md-3">
                {!! Form::label('Localização:') !!}
            </label>
            <div class="col-md-9 form-inline">
                {!! Form::text('corredor', $model->EstoqueSaldo->EstoqueLocalProduto->corredor, ['class'=> 'form-control text-center', 'id'=>'corredor', 'style'=>'width:58px', 'placeholder'=>'Corredor']) !!}
                {!! Form::text('prateleira', $model->EstoqueSaldo->EstoqueLocalProduto->prateleira, ['class'=> 'form-control text-center', 'id'=>'prateleira', 'style'=>'width:58px', 'placeholder'=>'Prateleira']) !!}
                {!! Form::text('coluna', $model->EstoqueSaldo->EstoqueLocalProduto->coluna, ['class'=> 'form-control text-center', 'id'=>'coluna', 'style'=>'width:58px', 'placeholder'=>'Coluna']) !!}
                {!! Form::text('bloco', $model->EstoqueSaldo->EstoqueLocalProduto->bloco, ['class'=> 'form-control text-center', 'id'=>'bloco', 'style'=>'width:58px', 'placeholder'=>'Bloco']) !!}
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-offset-3 col-md-9">
                {!! Form::submit('Salvar', array('class' => 'btn btn-primary', 'id'=>'btnSubmit')) !!}
                <a href='{{ url("estoque-saldo-conferencia/create?data=$data&fiscal=$fiscal&codestoquelocal=$codestoquelocal") }}' class='btn btn-danger'>Cancelar</a>
            </div>
        </div>
        
    </div>
    <div class='col-md-8'>
        <?php
        
        $sql = "
            select 
                    el.codestoquelocal, 
                    el.estoquelocal, 
                    fisico.codestoquesaldo as codestoquesaldo_fisico, fisico.saldoquantidade as saldoquantidade_fisico, fisico.saldovalor as saldovalor_fisico, fisico.customedio as customedio_fisico,
                    fiscal.codestoquesaldo as codestoquesaldo_fiscal, fiscal.saldoquantidade as saldoquantidade_fiscal, fiscal.saldovalor as saldovalor_fiscal, fiscal.customedio as customedio_fiscal
            from tblestoquelocal el
            left join tblestoquelocalproduto elp on (elp.codestoquelocal = el.codestoquelocal and elp.codproduto = {$model->EstoqueSaldo->EstoqueLocalProduto->codproduto})
            left join tblestoquesaldo fisico on (fisico.codestoquelocalproduto = elp.codestoquelocalproduto and fisico.fiscal = false)
            left join tblestoquesaldo fiscal on (fiscal.codestoquelocalproduto = elp.codestoquelocalproduto and fiscal.fiscal = true)
            where el.inativo is null
            order by el.estoquelocal";
            
        use Illuminate\Support\Facades\DB;
        
        $regs = DB::select($sql);
        
        ?>
        <table class='table table-hover table-striped table-condensed'>
            <thead>
                <tr>
                    <th rowspan='2' class='col-md-1 text-left'>
                        Local
                    </th>
                    <th colspan='3' class='text-center'>
                        Físico
                    </th>
                    <th colspan='3' class='text-center'>
                        Fiscal
                    </th>
                </tr>
                <tr>
                    <th class='col-md-1 text-right'>
                        Quantidade
                    </th>
                    <th class='col-md-1 text-right'>
                        Valor
                    </th>
                    <th class='col-md-1 text-right'>
                        Custo Médio
                    </th>
                    <th class='col-md-1 text-right'>
                        Quantidade
                    </th>
                    <th class='col-md-1 text-right'>
                        Valor
                    </th>
                    <th class='col-md-1 text-right'>
                        Custo Médio
                    </th>
                </tr>
            </thead>
            @foreach ($regs as $reg)
                <tr>
                    <th>
                        {{ $reg->estoquelocal }}
                    </th>
                    <td class='text-right {{ ($reg->codestoquesaldo_fisico == $model->codestoquesaldo)?'info':'' }}'>
                        <a href='{{ url("estoque-saldo/$reg->codestoquesaldo_fisico") }}'>
                            {{ formataNumero($reg->saldoquantidade_fisico, 3) }}
                        </a>
                    </td>
                    <td class='text-right {{ ($reg->codestoquesaldo_fisico == $model->codestoquesaldo)?'info':'' }}'>
                        <a href='{{ url("estoque-saldo/$reg->codestoquesaldo_fisico") }}'>
                            {{ formataNumero($reg->saldovalor_fisico, 2) }}
                        </a>
                    </td>
                    <td class='text-right {{ ($reg->codestoquesaldo_fisico == $model->codestoquesaldo)?'info':'' }}'>
                        {{ formataNumero($reg->customedio_fisico, 6) }}
                    </td>
                    <td class='text-right {{ ($reg->codestoquesaldo_fiscal == $model->codestoquesaldo)?'info':'' }}'>
                        <a href='{{ url("estoque-saldo/$reg->codestoquesaldo_fiscal") }}'>
                            {{ formataNumero($reg->saldoquantidade_fiscal, 3) }}
                        </a>
                    </td>
                    <td class='text-right {{ ($reg->codestoquesaldo_fiscal == $model->codestoquesaldo)?'info':'' }}'>
                        <a href='{{ url("estoque-saldo/$reg->codestoquesaldo_fiscal") }}'>
                            {{ formataNumero($reg->saldovalor_fiscal, 2) }}
                        </a>
                    </td>
                    <td class='text-right {{ ($reg->codestoquesaldo_fiscal == $model->codestoquesaldo)?'info':'' }}'>
                        {{ formataNumero($reg->customedio_fiscal, 6) }}
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
<hr>

