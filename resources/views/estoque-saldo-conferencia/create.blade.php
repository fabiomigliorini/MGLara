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

function divSaldo($arr, $codestoquelocal, $codprodutovariacao, $fiscal) {
    ?>
    <div class="col-md-2 text-right">
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
    <div class="col-md-2 text-muted">
        @if (isset($arr['corredor']) && isset($arr['prateleira']) && isset($arr['coluna']) && isset($arr['bloco']))
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



<br>

<div class='row-fluid'>
    <small>
    <div id='div-estoque'>
        <div class="panel-group">

            <div class="panel panel-default panel-condensed">

                <!-- Titulo -->
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-4">
                        </div>
                        <div class="col-md-4 text-center">
                            <b>Físico</b>
                        </div>
                        <div class="col-md-4 text-center">
                            <b>Fiscal</b>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <b>Local</b>
                        </div>
                        <div class="col-md-2">
                            <b>Corredor</b>
                            <b class='pull-right'>
                                Min <span class='glyphicon glyphicon-arrow-down'></span> 
                                Max <span class='glyphicon glyphicon-arrow-up'></span> 
                            </b>
                        </div>
                        <div class="col-md-2 text-right">
                            <b>Quantidade</b>
                        </div>
                        <div class="col-md-1 text-right">
                            <b>Custo</b>
                        </div>
                        <div class="col-md-1 text-right">
                            <b>Valor</b>
                        </div>
                        <div class="col-md-2 text-right">
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
                                        {{ divLocalizacao($arrVar) }}
                                        {{ divSaldo($arrVar['fisico'], $codestoquelocal_linha, $codprodutovariacao_linha, 0) }}
                                        {{ divSaldo($arrVar['fiscal'], $codestoquelocal_linha, $codprodutovariacao_linha, 1) }}
                                    </div>
                                    @if (($codestoquelocal == $codestoquelocal_linha) && ($codprodutovariacao == $codprodutovariacao_linha))
                                        <br>
                                        <div class='row'>
                                            <div class='col-md-10 col-md-offset-1'>
                                                <div class='panel panel-success'>
                                                    @include ('estoque-saldo-conferencia.form')
                                                </div>
                                            </div>
                                        </div>
                                    @endif
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
    
    $('#form-estoque-saldo-conferencia').on("submit", function(e){
        $('#btnSubmit').attr('disabled', 'disabled');
        var currentForm = this;
        e.preventDefault();
        bootbox.confirm("Tem certeza que deseja salvar?", function(result) {
            if (result) {
                currentForm.submit();
            } else {
                $('#btnSubmit').removeAttr('disabled');
            }
        });
    });    
    
});

</script>
@endsection



@stop
