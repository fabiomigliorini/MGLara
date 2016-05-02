@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
  <div class="container-fluid"> 
    <ul class="nav navbar-nav">
      <li>
        <a href="<?php echo url('grupo-produto/create');?>"><span class="glyphicon glyphicon-plus"></span> Novo</a>
      </li>
    </ul>
  </div>
</nav>
<h1 class="header">Grupos de Produtos</h1>
<hr>
<div class="row">
    <div class="col-md-6">
        <div class="marcas-pagination pull-left">{!! $model->appends(Request::all())->render() !!}</div>
    </div>
<?php

foreach($ess as $es)
{
    $arr_saldos[$es->codgrupoproduto][$es->codestoquelocal][$es->fiscal] = [
        'saldoquantidade' => $es->saldoquantidade,
        'saldovalor' => $es->saldovalor,
    ];
    
    if (!isset($arr_totais[$es->codestoquelocal][$es->fiscal]))
        $arr_totais[$es->codestoquelocal][$es->fiscal] = [
            'saldoquantidade' => 0,
            'saldovalor' => 0
        ];
    
    $arr_totais[$es->codestoquelocal][$es->fiscal]['saldoquantidade'] += $es->saldoquantidade;
    $arr_totais[$es->codestoquelocal][$es->fiscal]['saldovalor'] += $es->saldovalor;
}
?>
    <div class="col-md-6">
    {!! Form::model(Request::all(), ['route' => 'grupo-produto.index', 'method' => 'GET', 'class' => 'navbar-form navbar-right pull-right', 'role' => 'search', 'style'=>'margin:0', 'id' => 'grupo-produto-search']) !!}
        <div class="form-group">
            {!! Form::number('codgrupoproduto', null, ['class' => 'form-control', 'placeholder' => '#', 'style'=>'width:100px']) !!}
        </div>
        <div class="form-group">
          {!! Form::text('grupoproduto', null, ['class' => 'form-control', 'placeholder' => 'Nome do grupo']) !!}
        </div>
        <div class="form-group">
            <select class="form-control" name="inativo" id="inativo">
                <option value=""></option>
                <option value="0">Todos</option>
                <option value="1">Ativos</option>
                <option value="2">Inativos</option>
            </select>
        </div>      
      <button type="submit" class="btn btn-default">Buscar</button>
    {!! Form::close() !!}
    </div>
</div>
<br>
@if (count($model) > 0)
<table class="table table-striped table-condensed table-hover table-bordered small">
    <thead>
        <th colspan="2" class="col-sm-4">
            Grupo Produto
        </th>
        @foreach ($els as $el)
        <th colspan='2' class='text-center col-sm-1' style='border-left-width: 2px'>
            {{ $el->estoquelocal }}
        </th>
        @endforeach
    </thead>
    
    <tbody>
        @foreach($model as $row)
        <tr>
            <th rowspan="2">
                @if(!empty($row->codimagem))
                    <div class="pull-right foto-item-listagem">
                        <img class="img-responsive pull-right" alt="{{$row->grupoproduto}}" title="{{$row->grupoproduto}}" src='<?php echo URL::asset('public/imagens/'.$row->Imagem->observacoes);?>'>
                    </div>
                @endif                
                <a href="{{ url("grupo-produto/$row->codgrupoproduto") }}">{{$row->grupoproduto}}</a>
                @if(!empty($row->inativo))
                <br>
                <span class="label label-danger">Inativado em {{ formataData($row->inativo, 'L')}} </span>
                @endif                
            </th>
            <th>
                Físico
            </th>
            @foreach ($els as $el)
            <td class='text-right' style='border-left-width: 2px'>
                @if (isset($arr_saldos[$row->codgrupoproduto][$el->codestoquelocal][0]))
                    {{ formataNumero($arr_saldos[$row->codgrupoproduto][$el->codestoquelocal][0]['saldoquantidade'], 0) }}
                @endif
            </td>
            <td class='text-right'>
                @if (isset($arr_saldos[$row->codgrupoproduto][$el->codestoquelocal][0]))
                    {{ formataNumero($arr_saldos[$row->codgrupoproduto][$el->codestoquelocal][0]['saldovalor'], 2) }}
                @endif
            </td>
            @endforeach
        </tr>
        <tr>
            <th>
                Fiscal
            </th>
            @foreach ($els as $el)
            <td class='text-right' style='border-left-width: 2px'>
                @if (isset($arr_saldos[$row->codgrupoproduto][$el->codestoquelocal][1]))
                    {{ formataNumero($arr_saldos[$row->codgrupoproduto][$el->codestoquelocal][1]['saldoquantidade'], 0) }}
                @endif
            </td>
            <td class='text-right'>
                @if (isset($arr_saldos[$row->codgrupoproduto][$el->codestoquelocal][1]))
                    {{ formataNumero($arr_saldos[$row->codgrupoproduto][$el->codestoquelocal][1]['saldovalor'], 2) }}
                @endif
            </td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
    
    <tfoot>
        <tr>
            <th rowspan="2">
                Totais
            </th>
            <th>
                Físico
            </th>
            @foreach ($els as $el)
            <th class='text-right' style='border-left-width: 2px'>
                @if (isset($arr_totais[$el->codestoquelocal][0]))
                    {{ formataNumero($arr_totais[$el->codestoquelocal][0]['saldoquantidade'], 0) }}
                @endif
            </th>
            <th class='text-right'>
                @if (isset($arr_totais[$el->codestoquelocal][0]))
                    {{ formataNumero($arr_totais[$el->codestoquelocal][0]['saldovalor'], 2) }}
                @endif
            </th>
            @endforeach
        </tr>
        <tr>
            <th>
                Fiscal
            </th>
            @foreach ($els as $el)
            <th class='text-right' style='border-left-width: 2px'>
                @if (isset($arr_totais[$el->codestoquelocal][1]))
                    {{ formataNumero($arr_totais[$el->codestoquelocal][1]['saldoquantidade'], 0) }}
                @endif
            </th>
            <th class='text-right'>
                @if (isset($arr_totais[$el->codestoquelocal][1]))
                    {{ formataNumero($arr_totais[$el->codestoquelocal][1]['saldovalor'], 2) }}
                @endif
            </th>
            @endforeach
        </tr>
    </tfoot>
</table>
@endif 

@if (count($model) === 0)
    <h3>Nenhum registro encontrado!</h3>
@endif    

@section('inscript')
<style type="text/css">
    ul.pagination {
        margin: 0;
    }
</style>
<script type="text/javascript">
  $(document).ready(function() {
    $('ul.pagination').removeClass('hide');
    $('#grupo-produto-search').change(function() {
        this.submit();
    });
  });
</script>
@endsection
@stop

