@extends('layouts.default')
@section('content')

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

function formSaldo($quantidadeinformada, $customedio, $ultimaconferencia, $sigla) {
    ?>
    <div class="col-md-3">
        <div class='row'>
            <div class='col-md-8'>
                <div class='input-group'>
                    {!! Form::number('quantidadeinformada', $quantidadeinformada, ['class'=> 'form-control input-sm text-right', 'step' => 0.001, 'style' => 'width: 100%', 'id'=>'quantidadeinformada', 'required'=>'required', 'placeholder'=>'Quantidade']) !!}
                    <div class='input-group-addon'>
                        {{ $sigla }}
                        &nbsp;
                        <span class='glyphicon {{ decideIconeUltimaConferencia($ultimaconferencia) }}'></span>
                    </div>
                </div>
            </div>
            <div class='col-md-8'>
                <div class='input-group'>
                    <div class='input-group-addon'>
                        R$
                    </div>
                    {!! Form::number('customedioinformado', $customedio, ['class'=> 'form-control input-sm text-right', 'step' => 0.000001, 'style' => 'width: 100%', 'id'=>'customedioinformado', 'required'=>'required', 'placeholder'=>'Custo']) !!}
                </div>
            </div>
            <div class="btn-group" role="group">
                <button type="submit" class="btn btn-primary">
                    <span class='glyphicon glyphicon-ok'></span>
                </button>
                <a href='{{ url('estoque-saldo-conferencia/create') }}' class='btn btn-danger'>
                    <span class='glyphicon glyphicon-erase'></span>
                </a>
            </div>
        </div>
    </div>
    <?php
    
}

function divSaldo($arr, $codestoquelocal, $codprodutovariacao, $fiscal) {
    ?>
    <div class="col-md-1 text-right">
        @if (!empty($arr['codestoquesaldo']))
            <a href="{{ url("estoque-saldo/{$arr['codestoquesaldo']}") }}">
        @endif
        {{ formataNumero($arr['saldoquantidade'], 3) }}
        @if (!empty($arr['codestoquesaldo']))
            </a>
        @endif
        @if (!empty($codprodutovariacao) && (is_numeric($codestoquelocal)))
            <a href='{{ url("estoque-saldo-conferencia/create?codestoquelocal=$codestoquelocal&codprodutovariacao=$codprodutovariacao&fiscal=$fiscal") }}'>
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
    <?php
}

function divDescricao($arr) {
    ?>
    <div class="col-md-2">
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
        <i class='text-muted'>{&nbsp;Sem&nbsp;Variação&nbsp;}</i>
        @endif
    </div>
    <?php
}

function divLocalizacao ($arr)
{
    ?>
    <div class="col-md-4 text-muted">
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
    </div>
    <?php
}

?>

<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="{{ url("estoque-saldo-conferencia") }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>
        </ul>
    </div>
</nav>

<h1>
    {!! 
        titulo(
            $pv->codproduto ,
            [
                url("produto/{$pv->codproduto}") => $pv->Produto->produto,
                $pv->variacao
            ],
            $pv->Produto->inativo,
            6
        ) 
    !!}
</h1>

<div>
    <a href='{{ url("secao-produto/{$pv->Produto->SubGrupoProduto->GrupoProduto->FamiliaProduto->codsecaoproduto}") }}' class=''>
        {!! $pv->Produto->SubGrupoProduto->GrupoProduto->FamiliaProduto->SecaoProduto->secaoproduto!!}
    </a>
    »
    <a href='{{ url("familia-produto/{$pv->Produto->SubGrupoProduto->GrupoProduto->codfamiliaproduto}") }}' class=''>
        {!! $pv->Produto->SubGrupoProduto->GrupoProduto->FamiliaProduto->familiaproduto!!}
    </a>
    »
    <a href='{{ url("grupo-produto/{$pv->Produto->SubGrupoProduto->codgrupoproduto}") }}' class=''>
        {!! $pv->Produto->SubGrupoProduto->GrupoProduto->grupoproduto!!}
    </a>
    »
    <a href='{{ url("sub-grupo-produto/{$pv->Produto->codsubgrupoproduto}") }}' class=''>
        {!! $pv->Produto->SubGrupoProduto->subgrupoproduto!!}
    </a>
    »
    <a href='{{ url("marca/{$pv->Produto->codmarca}") }}' class=''>
        {!! $pv->Produto->Marca->marca !!}
    </a>
    @if (!empty($pv->Produto->referencia))
        »
        {!! $pv->Produto->referencia !!}
    @endif
    »
    R$ {!! formataNumero($pv->Produto->preco) !!}
</div>    
<hr>
{!! Form::model(null, ['method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form-estoque-saldo-conferencia', 'route' => 'estoque-saldo-conferencia.store']) !!}

{!! Form::hidden('codestoquelocal', $codestoquelocal) !!}
{!! Form::hidden('codprodutovariacao', $codprodutovariacao) !!}
{!! Form::hidden('fiscal', ($fiscal?1:0)) !!}


<div>
    <div class="form-group">
        <div class="col-sm-1">
            {!! Form::label('data', 'Data Ajuste:') !!}
        </div>
        <div class="col-sm-2">
            {!! Form::datetimeLocal('data', $data, ['class'=> 'form-control input-sm text-center', 'id'=>'data', 'required'=>'required', 'placeholder'=>'Data Ajuste']) !!}
        </div>
    </div>
</div>

<br>

<div class='row-fluid'>
    <small>
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

            @foreach($estoque['local'] as $codestoquelocal_linha => $arrLocal)
                <div class="panel panel-default panel-condensed">

                    <!-- Total Local -->
                    <div class="panel-heading">
                        <a data-toggle="collapse" href="#collapseEstoqueLocal{{ $codestoquelocal_linha }}">
                            <div class="row">
                                {{ divDescricao($arrLocal) }}
                                {{ divLocalizacao($arrLocal) }}
                                {{ divSaldo($arrLocal['fisico'], $codestoquelocal_linha, null, 0) }}
                                {{ divSaldo($arrLocal['fiscal'], $codestoquelocal_linha, null, 1) }}
                            </div>
                        </a>
                    </div>

                    <!-- Variacoes do Produto -->
                    <div id="collapseEstoqueLocal{{ $codestoquelocal_linha }}" class="panel-collapse collapse {{ ($codestoquelocal_linha == $codestoquelocal)?'in':'' }}">
                        <ul class="list-group list-group-hover list-group-condensed">
                            @foreach ($arrLocal['variacao'] as $codprodutovariacao_linha => $arrVar)
                                <li class="list-group-item {{ (($codestoquelocal_linha == $codestoquelocal) && ($codprodutovariacao_linha == $codprodutovariacao))?'list-group-item-success':'' }}">
                                    <div class="row">
                                        {{ divDescricao($arrVar) }}
                                        @if (($codestoquelocal == $codestoquelocal_linha) && ($codprodutovariacao == $codprodutovariacao_linha))
                                            <div class="col-md-4 text-muted">
                                                <div class='row'>
                                                    <div class='col-md-3'>
                                                        {!! Form::number('corredor', $corredor, ['class'=> 'form-control input-sm text-center', 'style'=>'width: 100%', 'id'=>'corredor', 'step' => 1, 'min' => 0, 'placeholder'=>'Corredor']) !!}
                                                    </div>
                                                    <div class='col-md-3'>
                                                            {!! Form::number('prateleira', $prateleira, ['class'=> 'form-control input-sm text-center', 'style'=>'width: 100%', 'id'=>'prateleira', 'step' => 1, 'min' => 0, 'placeholder'=>'Prateleira']) !!}
                                                    </div>
                                                    <div class='col-md-3'>
                                                            {!! Form::number('coluna', $coluna, ['class'=> 'form-control input-sm text-center', 'style'=>'width: 100%', 'id'=>'coluna', 'step' => 1, 'min' => 0, 'placeholder'=>'Coluna']) !!}
                                                    </div>
                                                    <div class='col-md-3'>
                                                            {!! Form::number('bloco', $bloco, ['class'=> 'form-control input-sm text-center', 'style'=>'width: 100%', 'id'=>'bloco', 'step' => 1, 'min' => 0, 'placeholder'=>'Bloco']) !!}
                                                    </div>
                                                </div>
                                                <div class='row'>
                                                    <div class='col-md-6'>
                                                        <div class='input-group' style="width: 100%">
                                                            {!! Form::number('estoqueminimo', $estoqueminimo, ['class'=> 'form-control input-sm text-right', 'style' => 'width: 100%', 'step' => 1, 'min' => 0, 'id'=>'estoqueminimo', 'placeholder'=>'Mín']) !!}
                                                            <div class='input-group-addon'>
                                                                <span class='glyphicon glyphicon-arrow-down'></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class='col-md-6'>
                                                        <div class='input-group' style="width: 100%">
                                                            {!! Form::number('estoquemaximo', $estoquemaximo, ['class'=> 'form-control input-sm text-right', 'style' => 'width: 100%', 'step' => 1, 'min' => 0, 'id'=>'estoquemaximo', 'placeholder'=>'Máx']) !!}
                                                            <div class='input-group-addon'>
                                                                <span class='glyphicon glyphicon-arrow-up'></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            {{ divLocalizacao($arrVar) }}
                                        @endif

                                        @if (($codestoquelocal == $codestoquelocal_linha) && ($codprodutovariacao == $codprodutovariacao_linha) && ($fiscal == false))
                                            {{ formSaldo($quantidadeinformada, $customedio, $arrVar['fisico']['ultimaconferencia'], $pv->Produto->UnidadeMedida->sigla) }}
                                        @else
                                            {{ divSaldo($arrVar['fisico'], $codestoquelocal_linha, $codprodutovariacao_linha, 0) }}
                                        @endif

                                        @if (($codestoquelocal == $codestoquelocal_linha) && ($codprodutovariacao == $codprodutovariacao_linha) && ($fiscal == true))
                                            {{ formSaldo($quantidadeinformada, $customedio, $arrVar['fisico']['ultimaconferencia'], $pv->Produto->UnidadeMedida->sigla) }}
                                        @else
                                            {{ divSaldo($arrVar['fiscal'], $codestoquelocal_linha, $codprodutovariacao_linha, 1) }}
                                        @endif

                                    </div>              
                                </li>
                            @endforeach
                        </ul>
                    </div>

                </div>
            @endforeach

        </div>
    </div>
    </small>
</div>

@section('inscript')
<script type="text/javascript">
$(document).ready(function() {

    $('#barras').focus();
    $('#corredor').focus();
   
    $('#form-estoque-saldo-conferencia').on("submit", function(e){
        var currentForm = this;
        e.preventDefault();
        bootbox.confirm("Tem certeza que deseja salvar?", function(result) {
            if (result) {
                currentForm.submit();
            }
        });
    });    
    
    $('#fiscal').bootstrapSwitch('state', <?php echo (!empty($fiscal) ? 'true' : 'false'); ?>);
    
});

</script>
@endsection



@stop