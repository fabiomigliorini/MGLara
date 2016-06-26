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
            <small>{{ formataCodigo($model->EstoqueSaldo->EstoqueLocalProduto->codproduto, 6) }}</small>
            {!! 
                titulo(
                    NULL ,
                    [
                        ['url' => "produto/{$model->EstoqueSaldo->EstoqueLocalProduto->codproduto}", 'descricao' => $model->EstoqueSaldo->EstoqueLocalProduto->Produto->produto],
                    ],
                    $model->EstoqueSaldo->EstoqueLocalProduto->Produto->inativo
                ) 
            !!}
        </h4>
        <hr>
        <h5>
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
            »
            R$ {!! formataNumero($model->EstoqueSaldo->EstoqueLocalProduto->Produto->preco) !!}
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
                        {!! $model->EstoqueSaldo->EstoqueLocalProduto->Produto->UnidadeMedida->sigla !!}
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
                {!! Form::text('estoqueminimo', $model->EstoqueSaldo->EstoqueLocalProduto->estoqueminimo, ['class'=> 'form-control text-right', 'id'=>'estoqueminimo', 'style'=>'width:160px', 'placeholder'=>'Mínimo']) !!}
                {!! Form::text('estoquemaximo', $model->EstoqueSaldo->EstoqueLocalProduto->estoquemaximo, ['class'=> 'form-control text-right', 'id'=>'estoquemaximo', 'style'=>'width:160px', 'placeholder'=>'Máximo']) !!}
            </div>
        </div>
        <div class="form-group">
            <label for="data" class="control-label col-md-3">
                {!! Form::label('Localização:') !!}
            </label>
            <div class="col-md-9 form-inline">
                {!! Form::text('corredor', $model->EstoqueSaldo->EstoqueLocalProduto->corredor, ['class'=> 'form-control text-center', 'id'=>'corredor', 'style'=>'width:78px', 'placeholder'=>'Corredor']) !!}
                {!! Form::text('prateleira', $model->EstoqueSaldo->EstoqueLocalProduto->prateleira, ['class'=> 'form-control text-center', 'id'=>'prateleira', 'style'=>'width:78px', 'placeholder'=>'Prateleira']) !!}
                {!! Form::text('coluna', $model->EstoqueSaldo->EstoqueLocalProduto->coluna, ['class'=> 'form-control text-center', 'id'=>'coluna', 'style'=>'width:78px', 'placeholder'=>'Coluna']) !!}
                {!! Form::text('bloco', $model->EstoqueSaldo->EstoqueLocalProduto->bloco, ['class'=> 'form-control text-center', 'id'=>'bloco', 'style'=>'width:78px', 'placeholder'=>'Bloco']) !!}
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
        <table class='table table-hover table-striped table-condensed table-bordered'>
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
            <?php
            $total['saldoquantidade_fisico'] = 0;
            $total['saldovalor_fisico'] = 0;
            $total['customedio_fisico'] = 0;
            $total['saldoquantidade_fiscal'] = 0;
            $total['saldovalor_fiscal'] = 0;
            $total['customedio_fiscal'] = 0;
            $linha = 0;
            ?>
            @foreach ($regs as $reg)
                <?php
                $linha++;
                $total['saldoquantidade_fisico'] += $reg->saldoquantidade_fisico;
                $total['saldovalor_fisico'] += $reg->saldovalor_fisico;
                $total['saldoquantidade_fiscal'] += $reg->saldoquantidade_fiscal;
                $total['saldovalor_fiscal'] += $reg->saldovalor_fiscal;
                
                $esc_fisico = EstoqueSaldoConferencia::where('codestoquesaldo', $reg->codestoquesaldo_fisico)->limit(5)->orderBy('criacao', 'desc')->get();
                $esc_fiscal = EstoqueSaldoConferencia::where('codestoquesaldo', $reg->codestoquesaldo_fiscal)->limit(5)->orderBy('criacao', 'desc')->get();
                
                $label_fisico = 'label-default';
                $label_fiscal = 'label-default';
                $dias_fisico = NULL;
                $dias_fiscal = NULL;
                
                if (isset($esc_fisico[0]))
                {
                    $dias_fisico = $esc_fisico[0]->criacao->diffInDays();
                    
                    if ($dias_fisico > 30)
                        $label_fisico = 'label-danger';
                    elseif ($dias_fisico > 15)
                        $label_fisico = 'label-warning';
                    else
                        $label_fisico = 'label-success';
                }
                
                if (isset($esc_fiscal[0]))
                {
                    $dias_fiscal = $esc_fiscal[0]->criacao->diffInDays();
                    
                    if ($dias_fiscal > 30)
                        $label_fiscal = 'label-danger';
                    elseif ($dias_fiscal > 15)
                        $label_fiscal = 'label-warning';
                    else
                        $label_fiscal = 'label-success';
                }
                
                ?>
                <tr>
                    <th>
                        {{ $reg->estoquelocal }}
                    </th>
                    <td class='text-right {{ ($reg->codestoquesaldo_fisico == $model->codestoquesaldo)?'info':'' }}'>
                        <a href='{{ url("estoque-saldo/$reg->codestoquesaldo_fisico") }}'>
                            {{ formataNumero($reg->saldoquantidade_fisico, 3) }}
                        </a>
                        <a class="label pull-left {{ $label_fisico }}" role="button" data-toggle="collapse" href="#trVerificacao{{ $linha }}" aria-expanded="false" aria-controls="trVerificacao{{ $linha }}">
                            &nbsp;
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
                        <a class="label pull-left {{ $label_fiscal }}" role="button" data-toggle="collapse" href="#trVerificacao{{ $linha }}" aria-expanded="false" aria-controls="trVerificacao{{ $linha }}">
                            &nbsp;
                        </a>
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
                <tr class="collapse" id="trVerificacao{{ $linha }}">
                    <td>
                       
                    </td>
                    <td colspan='3'>
                        @foreach ($esc_fisico as $esc)
                            <div class='row'>
                                <div class='col-md-3 text-right'>
                                    {{ formataNumero($esc->quantidadeinformada, 3) }} <br>
                                </div>
                                <div class='col-md-3 text-right'>
                                    {{ formataNumero($esc->customedioinformado, 6) }}
                                </div>
                                <div class='col-md-6 text-muted'>
                                    {{ $esc->UsuarioCriacao->usuario }} em <br>
                                    {{ formataData($esc->criacao, 'L') }}
                                </div>
                            </div>
                        @endforeach
                    </td>
                    <td colspan='3'>
                        @foreach ($esc_fiscal as $esc)
                            <div class='row'>
                                <div class='col-md-3 text-right'>
                                    {{ formataNumero($esc->quantidadeinformada, 3) }} <br>
                                </div>
                                <div class='col-md-3 text-right'>
                                    {{ formataNumero($esc->customedioinformado, 6) }}
                                </div>
                                <div class='col-md-6 text-muted'>
                                    {{ $esc->UsuarioCriacao->usuario }} em <br>
                                    {{ formataData($esc->criacao, 'L') }}
                                </div>
                            </div>
                        @endforeach
                    </td>
                </tr>
            @endforeach
            <?php
            if ($total['saldoquantidade_fisico'] <> 0)
                $total['customedio_fisico'] = $total['saldovalor_fisico'] / $total['saldoquantidade_fisico'];
            if ($total['saldoquantidade_fiscal'] <> 0)
                $total['customedio_fiscal'] = $total['saldovalor_fiscal'] / $total['saldoquantidade_fiscal'];
            ?>
            <tfoot>
                <tr>
                    <th class='col-md-1'>
                        Total
                    </th>
                    <th class='col-md-1 text-right'>
                        {{ formataNumero($total['saldoquantidade_fisico'], 3) }}
                    </th>
                    <th class='col-md-1 text-right'>
                        {{ formataNumero($total['saldovalor_fisico'], 2) }}
                    </th>
                    <th class='col-md-1 text-right'>
                        {{ formataNumero($total['customedio_fisico'], 6) }}
                    </th>
                    <th class='col-md-1 text-right'>
                        {{ formataNumero($total['saldoquantidade_fiscal'], 3) }}
                    </th>
                    <th class='col-md-1 text-right'>
                        {{ formataNumero($total['saldovalor_fiscal'], 2) }}
                    </th>
                    <th class='col-md-1 text-right'>
                        {{ formataNumero($total['customedio_fiscal'], 6) }}
                    </th>
                </tr>                
            </tfoot>
        </table>
            </small>
    </div>
</div>
<hr>

